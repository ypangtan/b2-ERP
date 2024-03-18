<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\{
    UserService,
};

use App\Models\{
    User,
};

class GenerateUniq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:uniq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate uniq for those empty';

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
        $users = User::whereNull( 'uniq' )->get();

        foreach ( $users as $user ) {
            $user->uniq = UserService::generateUniq();
            $user->save();
        }

        return 0;
    }
}
