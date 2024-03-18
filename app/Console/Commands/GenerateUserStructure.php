<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\{
    User,
    UserStructure,
};

class GenerateUserStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:user-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user structure after migration';

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

        $users = User::get();

        $this->info( 'Total User: ' . count( $users ) . PHP_EOL );

        $this->output->progressStart( count( $users ) );

        foreach ( $users as $user ) {
            
            if ( $user->referral_id ) {

                $sponsor = User::find( $user->referral_id );

                $user->referral_structure = $sponsor->referral_structure . '|' . $sponsor->id;
                $user->save();

                $referralArray = explode( '|', $user->referral_structure );
                $referralLevel = count( $referralArray );
                for ( $i = $referralLevel - 1; $i >= 0; $i-- ) {
                    if ( $referralArray[$i] != '-' ) {
                        UserStructure::create( [
                            'user_id' => $user->id,
                            'referral_id' => $referralArray[$i],
                            'level' => $referralLevel - $i
                        ] );
                    }
                }
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        $this->info( 'Generate User Structure Completed!' );

        return 0;
    }
}
