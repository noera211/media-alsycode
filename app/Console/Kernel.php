<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // 🧹 Cleanup old submissions setiap bulan
        $schedule->command('submissions:cleanup')
            ->monthly()
            ->onFailure(function () {
                Log::error('Cleanup submissions task failed');
            })
            ->onSuccess(function () {
                Log::info('Cleanup submissions task completed successfully');
            });

        // 📊 Log storage status setiap hari jam 02:00
        $schedule->call(function () {
            $storagePath = storage_path('app');

            $diskFree = disk_free_space($storagePath);
            $diskTotal = disk_total_space($storagePath);

            // Cegah error jika hosting tidak mengizinkan akses info disk
            if ($diskFree === false || $diskTotal === false) {
                Log::warning('Unable to read disk space information');
                return;
            }

            $usagePercent = round((($diskTotal - $diskFree) / $diskTotal) * 100, 2);

            Log::info('Storage status check', [
                'free_mb' => round($diskFree / 1024 / 1024, 2),
                'total_mb' => round($diskTotal / 1024 / 1024, 2),
                'usage_percent' => $usagePercent,
                'warning' => $usagePercent > 80 ? 'Storage almost full!' : 'OK',
            ]);
        })->daily()->at('02:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}