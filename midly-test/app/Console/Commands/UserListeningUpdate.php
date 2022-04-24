<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PlayedTracksController;
class UserListeningUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userListening:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the user listening table every 24hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->trackController = new PlayedTracksController();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
       $this->trackController->fetchUsersRecentTracks();
    }
}
