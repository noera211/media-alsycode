<?php

namespace App\Http\Controllers;

use App\Models\PblSubmission;
use App\Models\QuestionSet;
use App\Models\QuestionSetResult;
use App\Models\StudentGrade;
use App\Models\SubjectInfo;
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
        /** @var User $user */
        $user = Auth::user();

        $subjectInfo = SubjectInfo::first();
        $evaluationSet = null;
        if ($subjectInfo && $subjectInfo->current_evaluation_set_id) {
            $evaluationSet = QuestionSet::withCount('questions')->find($subjectInfo->current_evaluation_set_id);
        }
        $sets = QuestionSet::withCount('questions')->orderByDesc('created_at')->get();

        if ($user && $user->isGuru()) {
            $siswaList = User::where('role', 'siswa')
                ->where('is_active', true)->get();

            $siswaIds = $siswaList->pluck('id');

            $gradeMap = StudentGrade::whereIn('student_id', $siswaIds)
                ->get()->keyBy('student_id');

            $submissionMap = PblSubmission::with('activity')
                ->whereIn('student_id', $siswaIds)
                ->whereNotNull('nilai')
                ->orderByDesc('nilai')
                ->get()
                ->groupBy('student_id');

            if ($evaluationSet) {
                $testMap = QuestionSetResult::where('question_set_id', $evaluationSet->id)
                    ->whereIn('student_id', $siswaIds)
                    ->latest('taken_at')
                    ->get()
                    ->groupBy('student_id')
                    ->map(fn($results) => $results->first());
            } else {
                $testMap = TestResult::whereIn('student_id', $siswaIds)
                    ->orderByDesc('taken_at')
                    ->get()
                    ->groupBy('student_id')
                    ->map(fn($results) => $results->first());
            }

            return view('nilai.guru', compact(
                'siswaList', 'gradeMap', 'submissionMap', 'testMap', 'subjectInfo', 'evaluationSet', 'sets'
            ));
        }

        // Siswa
        $submissions = PblSubmission::with('activity')
            ->where('student_id', $user->id)
            ->whereNotNull('nilai')
            ->orderByDesc('nilai')->get();

        $grade         = StudentGrade::where('student_id', $user->id)->first();
        $nilaiPbl      = $grade?->nilai_pbl;
        $catatanPbl    = $grade?->catatan_pbl;

        // Cek apakah siswa boleh test:
        // - Belum punya record grade → boleh (default)
        // - is_test_open = true → boleh
        // - is_test_open = false → sudah dikunci
        $isTestOpen = $grade === null || $grade->is_test_open;

        $lastTestResult = null;
        $nilaiEvaluasi = null;
        if ($evaluationSet) {
            $lastTestResult = QuestionSetResult::where('question_set_id', $evaluationSet->id)
                ->where('student_id', $user->id)
                ->latest('taken_at')->first();
            $nilaiEvaluasi = $lastTestResult ? $lastTestResult->persentase : null;
        }

        $nilaiAkhir = null;
        if ($nilaiPbl !== null && $nilaiEvaluasi !== null) {
            $nilaiAkhir = round(($nilaiPbl + $nilaiEvaluasi) / 2);
        }

        $questions = $evaluationSet ? $evaluationSet->questions : collect();

        return view('nilai.siswa', compact(
            'submissions', 'lastTestResult', 'questions',
            'nilaiPbl', 'catatanPbl', 'nilaiEvaluasi', 'nilaiAkhir', 'isTestOpen', 'evaluationSet'
        ));
    }

    // ─── Guru: Input Nilai PBL Manual ────────────────────────────────────

    public function updateNilaiPbl(Request $request, User $siswa)
    {
        $this->authorizeGuru();

        $request->validate([
            'nilai_pbl'   => 'nullable|integer|min:0|max:100',
            'catatan_pbl' => 'nullable|string|max:255',
        ]);

        StudentGrade::updateOrCreate(
            ['student_id' => $siswa->id],
            [
                'nilai_pbl'   => $request->nilai_pbl,
                'catatan_pbl' => $request->catatan_pbl,
            ]
        );

        return back()->with('success', 'Nilai PBL berhasil disimpan.');
    }

    // ─── Guru: Toggle Akses Test Siswa ───────────────────────────────────

    public function toggleTest(User $siswa)
    {
        $this->authorizeGuru();

        $grade = StudentGrade::firstOrCreate(
            ['student_id' => $siswa->id],
            ['is_test_open' => true]
        );

        $grade->update(['is_test_open' => !$grade->is_test_open]);

        $status = $grade->is_test_open ? 'dibuka' : 'dikunci';

        return back()->with('success', "Akses test {$siswa->name} berhasil {$status}.");
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
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !$user->isSiswa()) abort(403);

        // Cek apakah siswa boleh mengerjakan test
        $grade = StudentGrade::where('student_id', $user->id)->first();
        $isTestOpen = $grade === null || $grade->is_test_open;

        if (!$isTestOpen) {
            return back()->with('error', 'Akses test kamu sudah dikunci. Hubungi guru untuk membuka kembali.');
        }

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

        // Kunci test setelah selesai mengerjakan
        StudentGrade::updateOrCreate(
            ['student_id' => $user->id],
            ['is_test_open' => false]
        );

        return redirect()->route('nilai.index')
            ->with('success', "Test selesai! Skor Anda: {$result->persentase}/100");
    }

    public function updateEvaluationSet(Request $request)
    {
        $this->authorizeGuru();

        $request->validate([
            'evaluation_set_id' => 'nullable|exists:question_sets,id',
        ]);

        $subjectInfo = SubjectInfo::firstOrCreate([]);
        $subjectInfo->current_evaluation_set_id = $request->evaluation_set_id;

        if ($request->evaluation_set_id) {
            $set = QuestionSet::withCount('questions')->find($request->evaluation_set_id);
            if (!$set || $set->questions_count === 0) {
                return back()->with('error', 'Pilih kumpulan soal yang sudah memiliki soal.');
            }
        }

        $subjectInfo->save();

        return back()->with('success', 'Set evaluasi berhasil diperbarui.');
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