<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\{
    DB,
};

use App\Models\{
    Package,
    PackageOrder,
    User,
};

use Carbon\Carbon;

class MigrateOrderFromWalletTrans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:order-wt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate order from wallet_trans table';

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
        DB::table( 'package_orders' )->truncate();

        User::where( 'id', '>', 0 )
            ->update( [
                'capital' => 0,
                'active_amount' => 0,
            ] );

        $walletTrans = DB::table( 'wallet_trans' )
            ->where( 'trans_type', 1 )
            ->get();

        $packages = Package::get();

        foreach ( $walletTrans as $wt ) {

            $user = User::where( 'old_id', $wt->uid )->first();
            $user->capital += $wt->trans_amount;
            $user->active_amount += $wt->trans_amount;
            $user->save();

            $package = Package::where( 'min_price', '<=', $user->active_amount )
                ->orderBy( 'min_price', 'DESC' )
                ->first();

            $newPackageOrder = new PackageOrder();
            $newPackageOrderArray = [
                'package_id' => $package->id,
                'user_id' => $wt->uid,
                'reference' => $wt->trans_id,
                'amount' => $wt->trans_amount,
                'monthly_buy_back' => $wt->trans_amount * $package->monthly_buy_back / 100,
                'monthly_buy_back_rate' => $package->monthly_buy_back,
                'type' => 1,
                'status' => 10,
                'created_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $wt->reg_date, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
                'updated_at' => Carbon::createFromFormat( 'Y-m-d H:i:s', $wt->reg_date, 'Asia/Kuala_Lumpur' )->timezone( 'UTC' )->format( 'Y-m-d H:i:s' ),
            ];

            $newPackageOrder->forceFill( $newPackageOrderArray );
            $newPackageOrder->save();
        }

        return 0;
    }
}
