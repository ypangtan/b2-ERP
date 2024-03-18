<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\{
    DB,
};

use App\Models\{
    PackageOrder,
    RankingBonus,
    SponsorBonus,
    User,
};

use Helper;

use Carbon\Carbon;

class CalculateDirectBonus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:direct-bonus
        {date? : Calculate from which date, default is yesterday}
        {--dryrun : Whether the calculated result should store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Direct Bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $runDate = $this->argument( 'date' );
        $isDryRun = $this->option( 'dryrun' );

        if ( !empty( $runDate ) ) {
            if ( !strtotime( $runDate ) ) {
                $this->info( 'Invalid date' );
                return 0;
            }

            $startDate = Carbon::parse( $runDate, 'Asia/Kuala_Lumpur' )->startOfDay()->timezone( 'UTC' );
            $endDate = Carbon::parse( $runDate, 'Asia/Kuala_Lumpur' )->endOfDay()->timezone( 'UTC' );
            $runDate = Carbon::parse( $runDate, 'Asia/Kuala_Lumpur' )->startOfDay()->timezone( 'UTC' )->format( 'Y-m-d' );
        } else {
            $startDate = Carbon::now( 'Asia/Kuala_Lumpur' )->subDays( 1 )->startOfDay()->timezone( 'UTC' );
            $endDate = Carbon::now( 'Asia/Kuala_Lumpur' )->subDays( 1 )->endOfDay()->timezone( 'UTC' );
            $runDate = Carbon::now( 'Asia/Kuala_Lumpur' )->subDays( 1 )->startOfDay()->timezone( 'UTC' )->format( 'Y-m-d' );
        }

        $this->info( $runDate );
        $this->info( $startDate );
        $this->info( $endDate );

        $checkExist = SponsorBonus::where( 'release_date', '>=', $startDate )
            ->where( 'release_date', '<=', $endDate )
            ->where( 'type', 1 )
            ->count();

        // 检查是否已计算
        if ( $checkExist > 0 && $isDryRun == false ) {
            $this->info( PHP_EOL . 'Direct Bonus Calculated' . PHP_EOL );
            return 0;
        }

        $runningType = $isDryRun ? 'Dry Running' : 'Running';

        $this->info( PHP_EOL . $runningType . ' Direct Bonus for ' . $runDate . PHP_EOL );

        $data = [];

        $lists = PackageOrder::with( [
            'user',
            'user.uplines.referral',
        ] )->select( 'id', 'user_id', DB::raw( 'IFNULL( amount, 0 ) as total' ) )
            ->where( 'created_at', '>=', $startDate )
            ->where( 'created_at', '<=', $endDate )
            ->where( 'type', 1 )
            ->get();

        $this->output->progressStart( count( $lists ) );

        DB::beginTransaction();
        
        try {

            foreach ( $lists as $list ) {

                // 没有上线不用给
                if ( empty( $list->user->referral_id ) ) {
                    continue;
                }

                $referrals = $list->user->uplines;

                $difference = $maxLevel = 0;

                for ( $i = count( $referrals ) - 1; $i >= 0; $i-- ) {

                    $referral = $referrals[$i]->referral;

                    $this->info( PHP_EOL . $referral->email );

                    $rankingBonus = RankingBonus::where( 'ranking_id', '<=', $referral->ranking_id )
                        ->where( 'type', 1 )
                        ->where( 'status', 10 )
                        ->orderBy( 'id', 'DESC' )
                        ->first();
                        
                    // 不符合的跳过
                    if ( !$rankingBonus ) {
                        continue;
                    }

                    // 级差
                    if ( $i == count( $referrals ) - 1 ) {
                        $maxLevel = $rankingBonus->ranking_id;
                    }

                    if ( $rankingBonus->ranking_id < $maxLevel ) {
                        continue;
                    }

                    $maxLevel = $rankingBonus->ranking_id;

                    $differenceAmount = $rankingBonus->percentage - $difference;
                    $oldDifference = $difference;
                    $difference = $rankingBonus->percentage;

                    $bonus = $list->total * $differenceAmount / 100;

                    if ( $bonus > 0 ) {

                        // 下面显示用的
                        array_push( $data, [
                            'trigger_by' => $list->user->email,
                            'name' => $referral->email,
                            'amount' => $list->total,
                            'rate' => $rankingBonus->percentage . ' - ' . number_format( $oldDifference , 4 ) . ' = ' . number_format( $differenceAmount, 4 ),
                            'bonus_amount' => $bonus,
                        ] );

                        $remark = '##{direct_bonus}## [ ' . $user->email . ' ]';

                        SponsorBonus::create( [
                            'user_id' => $referral->id,
                            'from_user_id' => $list->user->id,
                            'from_type_id' => $list->id,
                            'from_type' => 'App\Models\PackageOrder',
                            'is_free' => 0,
                            'type' => 1,
                            'status' => 0,
                            'date' => $startDate,
                            'original_amount' => $order->amount,
                            'interest_rate' => $differenceAmount,
                            'interest_amount' => $bonus,
                            'release_amount' => $bonus,
                            'release_date' => $startDate,
                            'remark' => $remark
                        ] );
                    }

                    if ( $maxLevel == 4 ) {
                        break;
                    }
                }
            }

        } catch ( \Throwable $th ) {
            
            $this->info( PHP_EOL . 'Calculate Direct Bonus failed!' . PHP_EOL );

            return 0;
        }

        $this->output->progressFinish();

        if ( !$isDryRun ) {
            DB::commit();
        }

        if ( $data ) {
            $headers = [ 'Trigger By', 'User', 'Amount', 'Rate (%)', 'Bonus Amount' ];

            $this->table( $headers, $data );
        }
        
        $this->info( PHP_EOL . 'Calculate Direct Bonus successfully!' . PHP_EOL );

        return 0;
    }
}
