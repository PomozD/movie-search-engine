<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DBController;

class UpdateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-database-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dbController = new DBController();
        $result = $dbController->getAllMovies();

        $this->info(json_encode($result));

        $dbController->updateMovieTypes();
        $dbController->updateMovieCountries();
        $dbController->updateMovieGenres();
    }
}
