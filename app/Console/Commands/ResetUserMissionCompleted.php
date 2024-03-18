<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\{
    DB,
};

use App\Models\{
    User,
};

class ResetUserMissionCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

        $users = User::where( 'mission_completed', 1 )->get();

        foreach ( $users as $user ) {

            $user->mission_completed = 0;
            $user->save();
        }

        return 0;
    }
}
