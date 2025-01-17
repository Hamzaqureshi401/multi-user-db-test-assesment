<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateBothDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:both';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations on both shared and standard databases';

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Shared Database Migration
    config(['database.migrations' => 'migrations']);
    $this->info("Running migrations on the shared database...");
    $this->call('migrate', ['--database' => 'shared']);

    // Standard Database Migration
    config(['database.migrations' => 'migrations']);
    $this->info("Running migrations on the standard database...");
    $this->call('migrate', ['--database' => 'standard']);

    $this->info("Migrations completed on both databases.");
}

}
