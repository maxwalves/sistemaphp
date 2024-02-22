<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\BackupDatabase;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        // ...
        BackupDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Agendar o backup diariamente às 02:00 AM
        $schedule->command('backup:database')->dailyAt('2:00')->timezone('America/Sao_Paulo');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
