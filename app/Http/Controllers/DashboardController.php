<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriProgress;
use App\Models\PblActivity;
use App\Models\PblSubmission;
use App\Models\LevelSetting;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user           = Auth::user();
        $totalMateri    = Materi::count();
        $levelSettings  = LevelSetting::pluck('min_materi', 'difficulty');

        if ($user->isSiswa()) {
            $completedCount = $user->completedMateriCount();
            $progressPct    = $totalMateri > 0
                ? round(($completedCount / $totalMateri) * 100)
                : 0;

            $accessible = $this->getAccessible($completedCount, $levelSettings);

            $lastResult = TestResult::where('student_id', $user->id)
                ->latest('taken_at')->first();

            $submissionCount = PblSubmission::where('student_id', $user->id)->count();

            return view('dashboard.siswa', compact(
                'completedCount', 'totalMateri', 'progressPct',
                'accessible', 'levelSettings', 'lastResult', 'submissionCount'
            ));
        }

        // Guru / Admin melihat statistik kelas
        $totalSiswa      = User::where('role', 'siswa')->where('is_active', true)->count();
        $totalPbl        = PblActivity::count();
        $pendingGrading  = PblSubmission::whereNull('nilai')->count();
        $recentSubmissions = PblSubmission::with(['student', 'activity'])
            ->orderByDesc('submitted_at')->limit(5)->get();

        return view('dashboard.guru', compact(
            'totalSiswa', 'totalMateri', 'totalPbl',
            'pendingGrading', 'recentSubmissions'
        ));
    }

    private function getAccessible(int $count, $settings): array
    {
        $accessible = [];
        foreach ($settings as $diff => $min) {
            if ($count >= $min) $accessible[] = $diff;
        }
        return $accessible;
    }
}
