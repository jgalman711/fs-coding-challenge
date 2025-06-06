<?php

namespace App\Console\Commands;

use App\Services\UserImporterService;
use Illuminate\Console\Command;

class ImportUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-user {nat?} {results?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from API';

    /**
     * Execute the console command.
     */
    public function handle(UserImporterService $userImporterService)
    {
        $userImporterService->import($this->argument('results'), $this->argument('nat'));
    }
}
