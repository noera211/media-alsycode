<?php

namespace App\Http\Controllers;

use App\Models\LevelSetting;
use App\Models\Materi;
use App\Models\MateriProgress;
use App\Models\PblActivity;
use App\Models\PblSubmission;
use App\Models\SubmissionAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PblController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $activities = PblActivity::with('relatedMateri')
            ->orderBy('difficulty')
            ->get();

        $levelSettings = LevelSetting::pluck('min_materi', 'difficulty');
        $materiList    = Materi::orderBy('title')->get();

        $completedCount = 0;
        $accessible     = ['Mudah', 'Sedang', 'Sulit'];
        $submittedIds   = [];

        if ($user && $user->isSiswa()) {
            $completedCount = $user->completedMateriCount();
            $accessible = [];

            foreach ($levelSettings as $diff => $min) {
                if ($completedCount >= $min) {
                    $accessible[] = $diff;
                }
            }

            $submittedIds = PblSubmission::where('student_id', $user->id)
                ->pluck('activity_id')
                ->toArray();
        }

        return view('pbl.index', compact(
            'activities',
            'levelSettings',
            'completedCount',
            'accessible',
            'submittedIds',
            'materiList'
        ));
    }

    public function show(PblActivity $pblActivity)
    {
        $pblActivity->load('relatedMateri');

        /** @var User $user */
        $user = Auth::user();

        if ($user && $user->isSiswa()) {
            $levelSettings  = LevelSetting::pluck('min_materi', 'difficulty');
            $completedCount = $user->completedMateriCount();
            $minRequired    = $levelSettings[$pblActivity->difficulty] ?? 1;

            if ($completedCount < $minRequired) {
                return redirect()
                    ->route('pbl.index')
                    ->with('error', 'Selesaikan lebih banyak materi untuk membuka aktivitas ini.');
            }
        }

        $submission = null;
        $submissions = null;

        if ($user && $user->isSiswa()) {
            $submission = PblSubmission::where('activity_id', $pblActivity->id)
                ->where('student_id', $user->id)
                ->first();
        }

        if ($user && ($user->isGuru() || $user->isAdmin())) {
            $submissions = PblSubmission::with('student')
                ->where('activity_id', $pblActivity->id)
                ->orderByDesc('submitted_at')
                ->get();
        }

        return view('pbl.show', compact(
            'pblActivity',
            'submission',
            'submissions'
        ));
    }

    public function store(Request $request)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'topic'          => 'required|string|max:255',
            'difficulty'     => 'required|in:Mudah,Sedang,Sulit',
            'problem'        => 'required|string',
            'related_materi' => 'required|integer|exists:materi,id',
        ]);

        $data['created_by'] = Auth::id();

        PblActivity::create($data);

        return redirect()
            ->route('pbl.index')
            ->with('success', 'Aktivitas PBL berhasil ditambahkan.');
    }

    public function update(Request $request, PblActivity $pblActivity)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'topic'          => 'required|string|max:255',
            'difficulty'     => 'required|in:Mudah,Sedang,Sulit',
            'problem'        => 'required|string',
            'related_materi' => 'required|integer|exists:materi,id',
        ]);

        $pblActivity->update($data);

        return redirect()
            ->route('pbl.index')
            ->with('success', 'Aktivitas PBL berhasil diperbarui.');
    }

    public function destroy(PblActivity $pblActivity)
    {
        $this->authorizeGuru();

        $pblActivity->delete();

        return redirect()
            ->route('pbl.index')
            ->with('success', 'Aktivitas berhasil dihapus.');
    }

    public function submit(Request $request, PblActivity $pblActivity)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->isSiswa()) {
            abort(403);
        }

        $request->validate([
            'answer' => 'nullable|string',
            'file'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        $existing = PblSubmission::where('activity_id', $pblActivity->id)
            ->where('student_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('pbl.show', $pblActivity)
                ->with('error', 'Anda sudah mengumpulkan jawaban untuk aktivitas ini.');
        }

        $storageCheck = $this->checkStorageQuota();

        if (!$storageCheck['available']) {
            return redirect()
                ->route('pbl.show', $pblActivity)
                ->with('error', $storageCheck['message']);
        }

        $filePath = null;

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'local');
        }

        $submission = PblSubmission::create([
            'activity_id'  => $pblActivity->id,
            'student_id'   => $user->id,
            'answer'       => $request->answer,
            'file_path'    => $filePath,
            'submitted_at' => now(),
        ]);

        SubmissionAuditLog::create([
            'user_id'       => $user->id,
            'submission_id' => $submission->id,
            'action'        => 'uploaded',
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
            'description'   => 'Siswa mengumpulkan jawaban',
        ]);

        return redirect()
            ->route('pbl.show', $pblActivity)
            ->with('success', 'Jawaban berhasil dikumpulkan!');
    }

    public function updateSubmit(Request $request, PblActivity $pblActivity, PblSubmission $submission)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->isSiswa()) {
            abort(403);
        }

        if ($submission->student_id !== $user->id) {
            abort(403);
        }

        if ($submission->nilai !== null) {
            return redirect()
                ->route('pbl.show', $pblActivity)
                ->with('error', 'Jawaban tidak dapat diubah setelah diberi nilai.');
        }

        $request->validate([
            'answer' => 'nullable|string',
            'file'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        if ($request->hasFile('file')) {
            $storageCheck = $this->checkStorageQuota();

            if (!$storageCheck['available']) {
                return redirect()
                    ->route('pbl.show', $pblActivity)
                    ->with('error', $storageCheck['message']);
            }
        }

        $filePath = $submission->file_path;

        if ($request->hasFile('file')) {
            $newFile = $request->file('file')->store('submissions', 'local');

            if ($newFile) {
                if ($filePath && Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                }

                $filePath = $newFile;
            }
        }

        $submission->update([
            'answer'       => $request->answer,
            'file_path'    => $filePath,
            'submitted_at' => now(),
        ]);

        SubmissionAuditLog::create([
            'user_id'       => $user->id,
            'submission_id' => $submission->id,
            'action'        => 'updated',
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
            'description'   => 'Siswa mengubah jawaban yang sudah dikumpulkan',
        ]);

        return redirect()
            ->route('pbl.show', $pblActivity)
            ->with('success', 'Jawaban berhasil diperbarui.');
    }

    public function grade(Request $request, PblSubmission $submission)
    {
        $this->authorizeGuru();

        $request->validate([
            'feedback' => 'nullable|string',
            'nilai'    => 'nullable|integer|min:0|max:100',
        ]);

        $submission->update([
            'feedback'  => $request->feedback,
            'nilai'     => $request->nilai,
            'graded_at' => now(),
        ]);

        return redirect()
            ->route('pbl.show', $submission->activity_id)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    public function updateLevelSettings(Request $request)
    {
        $this->authorizeGuru();

        $request->validate([
            'mudah'  => 'required|integer|min:0',
            'sedang' => 'required|integer|min:0',
            'sulit'  => 'required|integer|min:0',
        ]);

        LevelSetting::where('difficulty', 'Mudah')
            ->update(['min_materi' => $request->mudah, 'updated_by' => Auth::id()]);

        LevelSetting::where('difficulty', 'Sedang')
            ->update(['min_materi' => $request->sedang, 'updated_by' => Auth::id()]);

        LevelSetting::where('difficulty', 'Sulit')
            ->update(['min_materi' => $request->sulit, 'updated_by' => Auth::id()]);

        return redirect()
            ->route('pbl.index')
            ->with('success', 'Pengaturan level berhasil disimpan.');
    }

    public function downloadSubmission(Request $request, PblSubmission $submission)
    {
        /** @var User $user */
        $user = Auth::user();

        $isOwner = $user && $user->isSiswa() && $submission->student_id === $user->id;
        $isGuru  = $user && ($user->isGuru() || $user->isAdmin());

        if (!$isOwner && !$isGuru) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        if (!$submission->file_path || !Storage::disk('local')->exists($submission->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        SubmissionAuditLog::create([
            'user_id'       => $user->id,
            'submission_id' => $submission->id,
            'action'        => 'downloaded',
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent'),
            'description'   => $isOwner
                ? 'Siswa mengunduh file miliknya'
                : 'Guru/Admin mengunduh file siswa',
        ]);

        $extension = pathinfo($submission->file_path, PATHINFO_EXTENSION);

        $fileName = 'submission_' . $submission->id;

        if ($extension) {
            $fileName .= '.' . $extension;
        }

        return Storage::disk('local')->download($submission->file_path, $fileName);
    }

    private function checkStorageQuota()
    {
        $storagePath = storage_path('app');
        $diskFree = disk_free_space($storagePath);
        $minFreeRequired = 50 * 1024 * 1024;

        if ($diskFree === false) {
            return [
                'available' => true,
                'message'   => '',
            ];
        }

        if ($diskFree < $minFreeRequired) {
            return [
                'available' => false,
                'message'   => '⚠️ Storage server hampir penuh! Hubungi administrator.',
            ];
        }

        return [
            'available' => true,
            'message'   => '',
        ];
    }

    public function getStorageInfo()
    {
        $storagePath = storage_path('app');

        $diskFree  = disk_free_space($storagePath);
        $diskTotal = disk_total_space($storagePath);

        if ($diskFree === false || $diskTotal === false) {
            return [
                'error' => 'Tidak dapat membaca informasi storage.',
            ];
        }

        $diskUsed = $diskTotal - $diskFree;

        $totalSubmissions = PblSubmission::whereNotNull('file_path')->count();

        $totalFileSize = 0;

        foreach (PblSubmission::whereNotNull('file_path')->get() as $submission) {
            if (Storage::disk('local')->exists($submission->file_path)) {
                $totalFileSize += Storage::disk('local')->size($submission->file_path);
            }
        }

        return [
            'total_submissions'   => $totalSubmissions,
            'total_file_size_mb'  => round($totalFileSize / 1024 / 1024, 2),
            'disk_used_mb'        => round($diskUsed / 1024 / 1024, 2),
            'disk_free_mb'        => round($diskFree / 1024 / 1024, 2),
            'disk_total_mb'       => round($diskTotal / 1024 / 1024, 2),
            'disk_usage_percent'  => round(($diskUsed / $diskTotal) * 100, 2),
        ];
    }

    private function authorizeGuru(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || (!$user->isGuru() && !$user->isAdmin())) {
            abort(403);
        }
    }
}