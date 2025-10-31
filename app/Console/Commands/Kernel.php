<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // 🚀 Jalankan sinkronisasi produk TikTok setiap 1 jam
        $schedule->call(function () {
            app(\App\Http\Controllers\ProductController::class)->index();
        })->hourly()
          ->name('tiktok_auto_sync')
          ->withoutOverlapping()
          ->onOneServer()
          ->appendOutputTo(storage_path('logs/tiktok_sync.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Daftarkan command custom dari folder Commands
        $this->load(__DIR__.'/Commands');

        // Tambahkan file routes/console.php jika ada
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }

}
