<?php

namespace App\Http\Controllers;

use App\Models\LevelSetting;
use App\Models\MateriProgress;
use App\Models\PblActivity;
use App\Models\PblSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PblController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user          = Auth::user();
        $activities    = PblActivity::orderBy('difficulty')->get();
        $levelSettings = LevelSetting::pluck('min_materi', 'difficulty');

        $completedCount = 0;
        $accessible     = ['Mudah', 'Sedang', 'Sulit']; // guru bisa lihat semua
        $submittedIds   = [];

        if ($user && $user->isSiswa()) {
            $completedCount = $user->completedMateriCount();
            $accessible     = [];
            foreach ($levelSettings as $diff => $min) {
                if ($completedCount >= $min) $accessible[] = $diff;
            }
            $submittedIds = PblSubmission::where('student_id', $user->id)
                ->pluck('activity_id')->toArray();
        }

        return view('pbl.index', compact(
            'activities', 'levelSettings', 'completedCount',
            'accessible', 'submittedIds'
        ));
    }

    public function show(PblActivity $pblActivity)
    {
        /** @var User $user */
        $user = Auth::user();

        // Cek lock untuk siswa
        if ($user && $user->isSiswa()) {
            $levelSettings  = LevelSetting::pluck('min_materi', 'difficulty');
            $completedCount = $user->completedMateriCount();
            $minRequired    = $levelSettings[$pblActivity->difficulty] ?? 1;

            if ($completedCount < $minRequired) {
                return redirect()->route('pbl.index')
                    ->with('error', 'Selesaikan lebih banyak materi untuk membuka aktivitas ini.');
            }
        }

        $submission = null;
        if ($user && $user->isSiswa()) {
            $submission = PblSubmission::where('activity_id', $pblActivity->id)
                ->where('student_id', $user->id)->first();
        }

        $submissions = null;
        if ($user && $user->isGuru()) {
            $submissions = PblSubmission::with('student')
                ->where('activity_id', $pblActivity->id)
                ->orderByDesc('submitted_at')->get();
        }

        return view('pbl.show', compact('pblActivity', 'submission', 'submissions'));
    }

    public function store(Request $request)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'topic'          => 'required|string|max:255',
            'difficulty'     => 'required|in:Mudah,Sedang,Sulit',
            'problem'        => 'required|string',
            'related_materi' => 'required|string|max:255',
        ]);

        $data['created_by'] = Auth::id();
        PblActivity::create($data);

        return redirect()->route('pbl.index')->with('success', 'Aktivitas PBL berhasil ditambahkan.');
    }

    public function update(Request $request, PblActivity $pblActivity)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'topic'          => 'required|string|max:255',
            'difficulty'     => 'required|in:Mudah,Sedang,Sulit',
            'problem'        => 'required|string',
            'related_materi' => 'required|string|max:255',
        ]);

        $pblActivity->update($data);

        return redirect()->route('pbl.index')->with('success', 'Aktivitas PBL berhasil diperbarui.');
    }

    public function destroy(PblActivity $pblActivity)
    {
        $this->authorizeGuru();
        $pblActivity->delete();
        return redirect()->route('pbl.index')->with('success', 'Aktivitas berhasil dihapus.');
    }

    // Siswa mengumpulkan jawaban
    public function submit(Request $request, PblActivity $pblActivity)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) abort(403);

        $request->validate([
            'answer' => 'nullable|string',
            'file'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:5120',
        ]);

        // Cegah double submission
        $existing = PblSubmission::where('activity_id', $pblActivity->id)
            ->where('student_id', $user->id)->first();
        if ($existing) {
            return redirect()->route('pbl.show', $pblActivity)
                ->with('error', 'Anda sudah mengumpulkan jawaban untuk aktivitas ini.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        PblSubmission::create([
            'activity_id'  => $pblActivity->id,
            'student_id'   => $user->id,
            'answer'       => $request->answer,
            'file_path'    => $filePath,
            'submitted_at' => now(),
        ]);

        return redirect()->route('pbl.show', $pblActivity)
            ->with('success', 'Jawaban berhasil dikumpulkan!');
    }

    // Guru memberi feedback & nilai
    public function grade(Request $request, PblSubmission $submission)
    {
        $this->authorizeGuru();

        $request->validate([
            'feedback' => 'nullable|string',
            'nilai'    => 'nullable|integer|min:0|max:100',
        ]);

        $submission->update([
            'feedback'   => $request->feedback,
            'nilai'      => $request->nilai,
            'graded_at'  => now(),
        ]);

        return redirect()->route('pbl.show', $submission->activity_id)
            ->with('success', 'Penilaian berhasil disimpan.');
    }

    // Update level settings (guru/admin)
    public function updateLevelSettings(Request $request)
    {
        $this->authorizeGuru();

        $request->validate([
            'mudah'  => 'required|integer|min:0',
            'sedang' => 'required|integer|min:0',
            'sulit'  => 'required|integer|min:0',
        ]);

        LevelSetting::where('difficulty', 'Mudah')->update(['min_materi' => $request->mudah, 'updated_by' => Auth::id()]);
        LevelSetting::where('difficulty', 'Sedang')->update(['min_materi' => $request->sedang, 'updated_by' => Auth::id()]);
        LevelSetting::where('difficulty', 'Sulit')->update(['min_materi' => $request->sulit, 'updated_by' => Auth::id()]);

        return redirect()->route('pbl.index')->with('success', 'Pengaturan level berhasil disimpan.');
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
