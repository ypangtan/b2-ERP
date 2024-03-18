<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\{
    DB,
};


use App\Models\{
    Package,
    PackageOrder,
    SponsorBonus,
    User,
};

use Carbon\Carbon;

class CalculateBuyBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:buy-back
        {batch : Batch 1 - 1 to 15, Batch 2 - 16 - 31}
        {date? : Calculate from which date, default is yesterday}
        {--dryrun : Whether the calculated result should store}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Buy Back';

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
        $batch = $this->argument( 'batch' );
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

        // Here new to use other way to check
        $checkExist = SponsorBonus::where( 'release_date', '>=', $startDate )
            ->where( 'release_date', '<=', $endDate )
            ->where( 'type', 10 )
            ->count();

        // 检查是否已计算
        if ( $checkExist > 0 && $isDryRun == false ) {
            $this->info( PHP_EOL . 'Buy Back Calculated' . PHP_EOL );
            return 0;
        }

        $this->info( $runDate );
        $this->info( $startDate );
        $this->info( $endDate );

        $runningType = $isDryRun ? 'Dry Running' : 'Running';

        $this->info( PHP_EOL . $runningType . ' Direct Bonus for ' . $runDate . PHP_EOL );

        $data = [];

        DB::beginTransaction();

        $packageOrder = PackageOrder::with( [
            'user',
        ] )->lockForUpdate()
            ->select( 'user_details.fullname', 'package_orders.*' )
            ->leftJoin( 'user_details', 'user_details.user_id', '=', 'package_orders.user_id' );

        if ( $batch == 1 ) {

            $packageOrder->whereRaw( DB::raw(
                '
                (
                    (DAY(package_orders.created_at) = 1 AND HOUR(package_orders.created_at) >= 16)
                    OR
                    (DAY(package_orders.created_at) >= 1 AND DAY(package_orders.created_at) <= 15)
                    OR
                    (DAY(package_orders.created_at) = 15 AND HOUR(package_orders.created_at) < 16)
                )
                '
            ) );

        } else if ( $batch == 2 ) {

            $packageOrder->whereRaw( DB::raw(
                '
                (
                    (DAY(package_orders.created_at) = 16 AND HOUR(package_orders.created_at) >= 16)
                    OR
                    (DAY(package_orders.created_at) >= 16 AND DAY(package_orders.created_at) <= 31)
                    OR
                    (DAY(package_orders.created_at) = 31 AND HOUR(package_orders.created_at) < 16)
                )
                '
            ) );
        }

        $packageOrder->where( 'package_orders.created_at', '<', Carbon::createFromFormat( 'Y-m-d H:i:s', '2024-01-01 00:00:00', 'Asia/Kuala_Lumpur' )->timezone( 'UTC' ) );

        $packageOrder->orderBy( 'package_orders.created_at' );

        $packageOrders = $packageOrder->get();

        $this->output->progressStart( count( $packageOrders ) );

        try {
        
            foreach ( $packageOrders as $po ) {

                $user = $po->user;

                $bonus = 0;
                $rate = 0;

                // Check for user mission completed status, 有才能buyback
                if ( $user->mission_completed == 1 ) {

                    $bonus = $po->monthly_buy_back;
                    $rate = $po->monthly_buy_back_rate;
                }

                SponsorBonus::create( [
                    'user_id' => $po->user_id,
                    'from_user_id' => $po->user_id,
                    'from_type_id' => $po->id,
                    'from_type' => 'App\Models\PackageOrder',
                    'is_free' => 0,
                    'type' => 10,
                    'status' => 0,
                    'date' => $startDate,
                    'original_amount' => $po->amount,
                    'interest_rate' => $rate,
                    'interest_amount' => $rate > 0 ? $po->monthly_buy_back : 0,
                    'release_amount' => $rate > 0 ? $po->monthly_buy_back : 0,
                    'release_date' => $startDate,
                    'remark' => '##{monthly_buyback}## [ ' . date( 'Y-m-d' ) . ' ]',
                ] );

                // 下面显示用的
                array_push( $data, [
                    'trigger_by' => $user->email,
                    'name' => $user->email,
                    'reference' => $po->reference,
                    'amount' => $po->amount,
                    'rate' => $rate,
                    'bonus_amount' => $bonus,
                ] );

                // 不管有没有buy back成功，每次trigger + 1，只能24次
                $po->total_released += 1;
                $po->save();

                // 出局，减 active_amount
                if ( $po->total_released == 24 ) {
                    $user->active_amount -= $po->amount;

                    $package = Package::where( 'min_price', '<=', $user->active_amount )
                        ->orderBy( 'min_price', 'DESC' )
                        ->first();

                    if ( $user->package_id > $package->id ) {
                        $user->package_id = $package->id;
                    }
                }

                $user->save();
            }

        } catch ( \Throwable $th ) {
            
            $this->info( PHP_EOL . 'Calculate Buy Back failed!' . PHP_EOL );

            return 0;
        }

        // Reset all user mission status
        if ( $batch == 2 ) {
            DB::statement( 'UPDATE users SET mission_completed = 0' );
        }

        $this->output->progressFinish();

        if ( !$isDryRun ) {
            DB::commit();
        }

        if ( $data ) {
            $headers = [ 'Trigger By', 'User', 'Reference', 'Amount', 'Rate (%)', 'Bonus Amount' ];

            $this->table( $headers, $data );
        }
        
        $this->info( PHP_EOL . 'Calculate Buy Back successfully!' . PHP_EOL );

        return 0;
    }
}
