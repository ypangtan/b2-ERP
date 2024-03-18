<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{
    User,
    WalletOld,
    WalletTransOld,
    WalletTransTypeOld,
};

class MigrateUserWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user-wallet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        activity()->disableLogging();

        $wtos = WalletTransOld::with( [
            'type'
        ] )->get();

        $this->info( 'Total Record: ' . count( $wtos ) );

        foreach ( $wtos as $wto ) {

            $rawAction = explode( '_', $wto->type->action );
            $action = $rawAction[1];
            $walletType = $rawAction[0];

            $walletTypeMapper = [
                'cv' => 1,
                'bv' => 2,
                'pv' => 3,
            ];

            $userWallet = UserWallet::where( 'user_id', $wto->uid )
                ->where( 'type', $walletTypeMapper[$walletType] )
                ->first();

            WalletService::transact( $userWallet, [
                'amount' => $action == 'deduct' ? ( $wto->trans_amount * -1 ) : $wto->trans_amount,
                'transaction_type' => 1,
            ] );
        }

        return 0;
    }
}
