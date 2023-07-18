<?php

namespace App\Console;

use App\Console\Commands\AS400MigrateCommand;
use App\Console\Commands\DailyCleanupCommand;
use App\Console\Commands\ImportBrandDeductions;
use App\Console\Commands\UpdateFutureLandedCosts;
use App\Console\Commands\UpdateKyolicCommand;
use App\Jobs\Api\V1\CacheRetrievedProducts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AS400MigrateCommand::class,
        ImportBrandDeductions::class,
        UpdateFutureLandedCosts::class,
        UpdateKyolicCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateFutureLandedCosts::class)
            ->timezone('America/Toronto')
            ->dailyAt('05:00')
            ->pingHoneybadgerOnSuccess('B2IQBW');

        $schedule->command(AS400MigrateCommand::class)
            ->timezone('America/Toronto')
            ->dailyAt('07:15')
            ->environments(['production'])
            ->pingHoneybadgerOnSuccess('pMI60o')
            ->then(function () {
                CacheRetrievedProducts::dispatch();
            });

        $schedule->command(UpdateKyolicCommand::class)
            ->timezone('America/Toronto')
            ->sundays()
            ->at('07:45')
            ->environments(['production'])
            ->pingHoneybadgerOnSuccess('JEIo25');

        $schedule->command(ImportBrandDeductions::class)
            ->timezone('America/Toronto')
            ->dailyAt('22:00')
            ->environments(['production'])
            ->pingHoneybadgerOnSuccess('GQIZ7O');

        $schedule->command(DailyCleanupCommand::class)
            ->timezone('America/Toronto')
            ->dailyAt('22:15')
            ->environments(['production', 'development'])
            ->pingHoneybadgerOnSuccessFromConfig('plic.checkins.daily_cleanup_command');

        $schedule->command('telescope:prune')
            ->timezone('America/Toronto')
            ->dailyAt('03:00')
            ->environments(['production'])
            ->pingHoneybadgerOnSuccess('9LI4x6');

        $schedule->command('telescope:prune --hours=72')
            ->timezone('America/Toronto')
            ->dailyAt('03:00')
            ->environments(['development'])
            ->pingHoneybadgerOnSuccess('j6IWbb');
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
