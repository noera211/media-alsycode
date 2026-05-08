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
    // ─── Helper: Hitung Nilai PBL Final dari Submission Siswa ────────────
    // Algoritma:
    //   1. Ambil nilai tertinggi dari masing-masing level (Mudah, Sedang, Sulit)
    //   2. Rata-rata ketiga nilai tersebut = Nilai PBL Final
    //   3. Jika suatu level tidak ada submission dinilai, nilainya 0

    private function hitungNilaiPblFinal(int $studentId): ?int
    {
        $submissions = PblSubmission::with('activity')
            ->where('student_id', $studentId)
            ->whereNotNull('nilai')
            ->get();

        if ($submissions->isEmpty()) {
            return null;
        }

        $levels = ['Mudah', 'Sedang', 'Sulit'];
        $nilaiPerLevel = [];

        foreach ($levels as $level) {
            $maxNilai = $submissions
                ->filter(fn($s) => $s->activity && $s->activity->difficulty === $level)
                ->max('nilai');

            // Jika tidak ada submission untuk level ini, nilai = 0
            $nilaiPerLevel[$level] = $maxNilai ?? 0;
        }

        // Rata-ratakan ketiga level
        $total = array_sum($nilaiPerLevel);
        return (int) round($total / count($levels));
    }

    // ─── Halaman Nilai ───────────────────────────────────────────────────

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $subjectInfo   = SubjectInfo::first();
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

            // Ambil semua submission bernilai, group per siswa lalu per difficulty
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

            // Hitung Nilai PBL Final otomatis per siswa untuk tampilan guru
            $nilaiPblFinalMap = [];
            foreach ($siswaList as $siswa) {
                $nilaiPblFinalMap[$siswa->id] = $this->hitungNilaiPblFinal($siswa->id);
            }

            return view('nilai.guru', compact(
                'siswaList', 'gradeMap', 'submissionMap', 'testMap',
                'subjectInfo', 'evaluationSet', 'sets', 'nilaiPblFinalMap'
            ));
        }

        // ─── Siswa ───────────────────────────────────────────────────────
        $submissions = PblSubmission::with('activity')
            ->where('student_id', $user->id)
            ->whereNotNull('nilai')
            ->orderByDesc('nilai')->get();

        $grade      = StudentGrade::where('student_id', $user->id)->first();
        $isTestOpen = $grade === null || $grade->is_test_open;

        // Hitung Nilai PBL Final otomatis untuk siswa
        $nilaiPblFinal = $this->hitungNilaiPblFinal($user->id);

        // Detail nilai per level untuk ditampilkan ke siswa
        $nilaiPerLevel = [];
        foreach (['Mudah', 'Sedang', 'Sulit'] as $level) {
            $maxNilai = $submissions
                ->filter(fn($s) => $s->activity && $s->activity->difficulty === $level)
                ->max('nilai');
            $nilaiPerLevel[$level] = $maxNilai; // null jika belum ada
        }

        $lastTestResult = null;
        $nilaiEvaluasi  = null;
        if ($evaluationSet) {
            $lastTestResult = QuestionSetResult::where('question_set_id', $evaluationSet->id)
                ->where('student_id', $user->id)
                ->latest('taken_at')->first();
            $nilaiEvaluasi = $lastTestResult ? $lastTestResult->persentase : null;
        }

        $nilaiAkhir = null;
        if ($nilaiPblFinal !== null && $nilaiEvaluasi !== null) {
            $nilaiAkhir = round(($nilaiPblFinal + $nilaiEvaluasi) / 2);
        }

        $questions = $evaluationSet ? $evaluationSet->questions : collect();

        return view('nilai.siswa', compact(
            'submissions', 'lastTestResult', 'questions',
            'nilaiPblFinal', 'nilaiPerLevel', 'nilaiEvaluasi', 'nilaiAkhir',
            'isTestOpen', 'evaluationSet'
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

        $grade      = StudentGrade::where('student_id', $user->id)->first();
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

    // ─── Guru: Export Nilai ke Excel ─────────────────────────────────────

    public function exportNilai()
    {
        $this->authorizeGuru();

        $subjectInfo   = SubjectInfo::first();
        $evaluationSet = null;
        if ($subjectInfo && $subjectInfo->current_evaluation_set_id) {
            $evaluationSet = QuestionSet::find($subjectInfo->current_evaluation_set_id);
        }

        $siswaList = User::where('role', 'siswa')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($evaluationSet) {
            $testMap = QuestionSetResult::where('question_set_id', $evaluationSet->id)
                ->latest('taken_at')
                ->get()
                ->groupBy('student_id')
                ->map(fn($r) => $r->first());
        } else {
            $testMap = TestResult::orderByDesc('taken_at')
                ->get()
                ->groupBy('student_id')
                ->map(fn($r) => $r->first());
        }

        // Buat CSV manual (tanpa package eksternal)
        $filename = 'nilai-siswa-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($siswaList, $testMap) {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel agar karakter UTF-8 terbaca
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header baris
            fputcsv($file, [
                'No',
                'Nama Siswa',
                'Nilai PBL - Mudah (Tertinggi)',
                'Nilai PBL - Sedang (Tertinggi)',
                'Nilai PBL - Sulit (Tertinggi)',
                'Nilai PBL Final (Rata-rata)',
                'Nilai Evaluasi',
                'Nilai Akhir',
            ]);

            foreach ($siswaList as $i => $siswa) {
                // Ambil submission bernilai per siswa
                $submissions = PblSubmission::with('activity')
                    ->where('student_id', $siswa->id)
                    ->whereNotNull('nilai')
                    ->get();

                $nilaiMudah  = $submissions->filter(fn($s) => $s->activity?->difficulty === 'Mudah')->max('nilai');
                $nilaiSedang = $submissions->filter(fn($s) => $s->activity?->difficulty === 'Sedang')->max('nilai');
                $nilaiSulit  = $submissions->filter(fn($s) => $s->activity?->difficulty === 'Sulit')->max('nilai');

                // Hitung PBL Final (level yang tidak ada = 0)
                $nilaiPblFinal = null;
                if ($submissions->isNotEmpty()) {
                    $nilaiPblFinal = (int) round(
                        (($nilaiMudah ?? 0) + ($nilaiSedang ?? 0) + ($nilaiSulit ?? 0)) / 3
                    );
                }

                $testResult    = $testMap[$siswa->id] ?? null;
                $nilaiEvaluasi = $testResult ? $testResult->persentase : null;

                $nilaiAkhir = null;
                if ($nilaiPblFinal !== null && $nilaiEvaluasi !== null) {
                    $nilaiAkhir = round(($nilaiPblFinal + $nilaiEvaluasi) / 2);
                }

                fputcsv($file, [
                    $i + 1,
                    $siswa->name,
                    $nilaiMudah  ?? '-',
                    $nilaiSedang ?? '-',
                    $nilaiSulit  ?? '-',
                    $nilaiPblFinal  ?? '-',
                    $nilaiEvaluasi  ?? '-',
                    $nilaiAkhir     ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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