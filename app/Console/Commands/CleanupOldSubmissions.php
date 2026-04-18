<?php

namespace App\Console\Commands;

use App\Models\PblSubmission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOldSubmissions extends Command
{
    protected $signature = 'submissions:cleanup {--days=365 : Hapus submission lebih tua dari X hari}';
    protected $description = 'Hapus submission dan file yang sudah lama untuk menghemat storage';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("🔍 Mencari submission yang lebih tua dari {$days} hari...");

        $oldSubmissions = PblSubmission::where('submitted_at', '<', $cutoffDate)
            ->whereNotNull('file_path')
            ->get();

        $deletedCount = 0;
        $totalSize = 0;

        foreach ($oldSubmissions as $submission) {
            try {
                if (Storage::disk('local')->exists($submission->file_path)) {
                    $size = Storage::disk('local')->size($submission->file_path);
                    Storage::disk('local')->delete($submission->file_path);
                    $totalSize += $size;
                    $deletedCount++;
                }
                $submission->update(['file_path' => null]);
            } catch (\Exception $e) {
                $this->error("❌ Gagal menghapus: {$submission->file_path} - {$e->getMessage()}");
            }
        }

        $totalSizeMB = round($totalSize / (1024 * 1024), 2);
        $this->info("✅ Berhasil menghapus {$deletedCount} file ({$totalSizeMB} MB)");
        $this->line("📊 Storage tersisa: " . $this->getStorageInfo());
    }

    private function getStorageInfo()
    {
        $storagePath = storage_path('app');
        $free = disk_free_space($storagePath);
        $total = disk_total_space($storagePath);
        $used = $total - $free;

        $usedMB = round($used / (1024 * 1024), 2);
        $freeMB = round($free / (1024 * 1024), 2);
        $totalMB = round($total / (1024 * 1024), 2);

        return "{$usedMB} MB / {$totalMB} MB ({$freeMB} MB free)";
    }
}
