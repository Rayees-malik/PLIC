<?php

namespace App\Console\Commands;

use App\Actions\Database\DailyCleanupAction;
use Illuminate\Console\Command;

class DailyCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs daily cleanup tasks';

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
        $this->info('Running daily cleanup tasks...');

        $action = app(DailyCleanupAction::class);
        $action->execute();

        return 0;
    }
}
