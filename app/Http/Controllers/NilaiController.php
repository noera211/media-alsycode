<?php

namespace App\Http\Controllers;

use App\Models\PblSubmission;
use App\Models\TestQuestion;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    // ─── Halaman Nilai ───────────────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();

        if ($user->isGuru()) {
            // Guru lihat semua siswa + nilai PBL mereka
            $siswaList = User::where('role', 'siswa')
                ->where('is_active', true)->get();

            // Ambil submission terakhir tiap siswa
            $nilaiMap = PblSubmission::whereIn('student_id', $siswaList->pluck('id'))
                ->whereNotNull('nilai')
                ->orderByDesc('graded_at')
                ->get()
                ->groupBy('student_id');

            $questions = TestQuestion::all();

            return view('nilai.guru', compact('siswaList', 'nilaiMap', 'questions'));
        }

        // Siswa: lihat nilai sendiri
        $submissions = PblSubmission::with('activity')
            ->where('student_id', $user->id)
            ->orderByDesc('submitted_at')->get();

        $lastTestResult = TestResult::where('student_id', $user->id)
            ->latest('taken_at')->first();

        $questions = TestQuestion::all();

        return view('nilai.siswa', compact('submissions', 'lastTestResult', 'questions'));
    }

    // ─── Guru: Update Nilai Siswa ────────────────────────────────────────

    public function updateNilai(Request $request, PblSubmission $submission)
    {
        $this->authorizeGuru();

        $request->validate([
            'nilai'    => 'nullable|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'nilai'      => $request->nilai,
            'feedback'   => $request->feedback,
            'graded_at'  => now(),
        ]);

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    // ─── Guru: CRUD Bank Soal ────────────────────────────────────────────

    public function storeQuestion(Request $request)
    {
        $this->authorizeGuru();

        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        TestQuestion::create(array_merge(
            $request->only('question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'),
            ['created_by' => Auth::id()]
        ));

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, TestQuestion $question)
    {
        $this->authorizeGuru();

        $request->validate([
            'question'       => 'required|string',
            'option_a'       => 'required|string|max:255',
            'option_b'       => 'required|string|max:255',
            'option_c'       => 'required|string|max:255',
            'option_d'       => 'required|string|max:255',
            'correct_answer' => 'required|in:A,B,C,D',
        ]);

        $question->update($request->only('question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'));

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroyQuestion(TestQuestion $question)
    {
        $this->authorizeGuru();
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // ─── Siswa: Kumpulkan Jawaban Test ───────────────────────────────────

    public function submitTest(Request $request)
    {
        $user      = Auth::user();
        if (!$user->isSiswa()) abort(403);

        $questions = TestQuestion::all();
        $answers   = $request->input('answers', []);
        $score     = 0;
        $detail    = [];

        foreach ($questions as $q) {
            $chosen  = $answers[$q->id] ?? null;
            $correct = ($chosen === $q->correct_answer);
            if ($correct) $score++;
            $detail[] = [
                'question_id'    => $q->id,
                'chosen'         => $chosen,
                'correct_answer' => $q->correct_answer,
                'is_correct'     => $correct,
            ];
        }

        $result = TestResult::create([
            'student_id'      => $user->id,
            'score'           => $score,
            'total_questions' => $questions->count(),
            'answers'         => $detail,
            'taken_at'        => now(),
        ]);

        return redirect()->route('nilai.index')
            ->with('test_result_id', $result->id)
            ->with('success', "Test selesai! Skor Anda: {$result->persentase}/100");
    }

    private function authorizeGuru(): void
    {
        if (!Auth::user()->isGuru() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }
}
