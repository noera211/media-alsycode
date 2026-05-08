<?php

namespace App\Http\Controllers;

use App\Models\QuestionSet;
use App\Models\QuestionSetResult;
use App\Models\QuestionSetUnlock;
use App\Models\TestQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionSetController extends Controller
{
    /* -------------------------------------------------------
       GURU: index - daftar semua kumpulan soal
    ------------------------------------------------------- */
    public function index()
    {
        $this->authorizeGuru();

        $sets = QuestionSet::withCount('questions')
            ->with(['creator'])
            ->orderByDesc('created_at')
            ->get();

        $subjectInfo = \App\Models\SubjectInfo::first();
        $evaluationSet = null;
        if ($subjectInfo && $subjectInfo->current_evaluation_set_id) {
            $evaluationSet = $sets->firstWhere('id', $subjectInfo->current_evaluation_set_id);
        }

        return view('kumpulan-soal.index', compact('sets', 'evaluationSet'));
    }

    /* -------------------------------------------------------
       GURU: create form
    ------------------------------------------------------- */
    public function create()
    {
        $this->authorizeGuru();
        $questions = TestQuestion::orderByDesc('created_at')->get();
        return view('kumpulan-soal.create', compact('questions'));
    }

    /* -------------------------------------------------------
       GURU: store kumpulan soal baru
    ------------------------------------------------------- */
    public function store(Request $request)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:pretest,posttest,ulangan,latihan',
            'description'  => 'nullable|string|max:500',
            'question_ids' => 'required|array|min:1',
            'question_ids.*' => 'exists:test_questions,id',
        ]);

        DB::transaction(function () use ($data) {
            $set = QuestionSet::create([
                'name'        => $data['name'],
                'type'        => $data['type'],
                'description' => $data['description'] ?? null,
                'created_by'  => Auth::id(),
            ]);

            $pivot = [];
            foreach ($data['question_ids'] as $order => $qid) {
                $pivot[$qid] = ['order' => $order];
            }
            $set->questions()->sync($pivot);
        });

        return redirect()->route('kumpulan-soal.index')
            ->with('success', 'Kumpulan soal berhasil dibuat.');
    }

    /* -------------------------------------------------------
       GURU: show detail + riwayat semua siswa
    ------------------------------------------------------- */
    public function show(QuestionSet $kumpulanSoal)
    {
        $this->authorizeGuru();

        $set = $kumpulanSoal->load(['questions', 'results.student']);

        // Riwayat dikelompok per siswa, ambil semua attempt
        $riwayat = QuestionSetResult::where('question_set_id', $set->id)
            ->with('student')
            ->orderByDesc('taken_at')
            ->get();

        return view('kumpulan-soal.show', compact('set', 'riwayat'));
    }

    /* -------------------------------------------------------
       GURU: edit form
    ------------------------------------------------------- */
    public function edit(QuestionSet $kumpulanSoal)
    {
        $this->authorizeGuru();

        $set = $kumpulanSoal->load('questions');
        $questions = TestQuestion::orderByDesc('created_at')->get();
        $selectedIds = $set->questions->pluck('id')->toArray();

        return view('kumpulan-soal.edit', compact('set', 'questions', 'selectedIds'));
    }

    /* -------------------------------------------------------
       GURU: update
    ------------------------------------------------------- */
    public function update(Request $request, QuestionSet $kumpulanSoal)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:pretest,posttest,ulangan,latihan',
            'description'    => 'nullable|string|max:500',
            'question_ids'   => 'required|array|min:1',
            'question_ids.*' => 'exists:test_questions,id',
        ]);

        DB::transaction(function () use ($data, $kumpulanSoal) {
            $kumpulanSoal->update([
                'name'        => $data['name'],
                'type'        => $data['type'],
                'description' => $data['description'] ?? null,
            ]);

            $pivot = [];
            foreach ($data['question_ids'] as $order => $qid) {
                $pivot[$qid] = ['order' => $order];
            }
            $kumpulanSoal->questions()->sync($pivot);
        });

        return redirect()->route('kumpulan-soal.index')
            ->with('success', 'Kumpulan soal berhasil diperbarui.');
    }

    /* -------------------------------------------------------
       GURU: hapus
    ------------------------------------------------------- */
    public function destroy(QuestionSet $kumpulanSoal)
    {
        $this->authorizeGuru();
        $kumpulanSoal->delete();
        return redirect()->route('kumpulan-soal.index')
            ->with('success', 'Kumpulan soal berhasil dihapus.');
    }

    /* -------------------------------------------------------
       SISWA: daftar kumpulan soal yang tersedia
    ------------------------------------------------------- */
    public function indexSiswa()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sets = QuestionSet::withCount('questions')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($set) use ($user) {
                $set->last_result = QuestionSetResult::where('question_set_id', $set->id)
                    ->where('student_id', $user->id)
                    ->latest('taken_at')
                    ->first();

                $set->attempt_count = QuestionSetResult::where('question_set_id', $set->id)
                    ->where('student_id', $user->id)
                    ->count();

                return $set;
            });

        return view('kumpulan-soal.siswa-index', compact('sets'));
    }

    /* -------------------------------------------------------
   SISWA: halaman pengerjaan soal
------------------------------------------------------- */
    public function take(QuestionSet $kumpulanSoal)
    {
        /** @var User $user */
        $user = Auth::user();
        $set  = $kumpulanSoal->load('questions');

        if ($set->questions->isEmpty()) {
            return back()->with('error', 'Kumpulan soal ini belum memiliki soal.');
        }

        $existingResult = QuestionSetResult::where('question_set_id', $set->id)
            ->where('student_id', $user->id)
            ->exists();

        $isRemedial = false;

        if ($existingResult) {
            // Cek 1: unlock via tabel QuestionSetUnlock (kumpulan soal biasa)
            $isUnlockedViaTable = QuestionSetUnlock::where('student_id', $user->id)
                ->where('question_set_id', $set->id)
                ->exists();

            // Cek 2: unlock via is_test_open khusus set evaluasi aktif
            $subjectInfo     = \App\Models\SubjectInfo::first();
            $isEvaluationSet = $subjectInfo
                && $subjectInfo->current_evaluation_set_id == $set->id;

            $isUnlockedViaGrade = false;
            if ($isEvaluationSet) {
                $grade              = \App\Models\StudentGrade::where('student_id', $user->id)->first();
                $isUnlockedViaGrade = $grade && $grade->is_test_open;
            }

            // Jika tidak lolos salah satu pun → blokir
            if (!$isUnlockedViaTable && !$isUnlockedViaGrade) {
                return back()->with('error', 'Anda sudah menyelesaikan set ini. Guru dapat membuka akses untuk pengerjaan ulang.');
            }

            // Hapus record unlock table jika dipakai (one-time)
            if ($isUnlockedViaTable) {
                QuestionSetUnlock::where('student_id', $user->id)
                    ->where('question_set_id', $set->id)
                    ->delete();
            }

            $isRemedial = true;
        }

        // Jika remedial, acak urutan value opsi (key A/B/C/D tetap, isi diacak)
        $shuffledOptions = [];
        if ($isRemedial) {
            foreach ($set->questions as $q) {
                $originalOptions = $q->options;
                $values          = array_values($originalOptions);
                shuffle($values);
                $keys                      = array_keys($originalOptions);
                $shuffledOptions[$q->id]   = array_combine($keys, $values);
            }
        }

        return view('kumpulan-soal.take', compact('set', 'isRemedial', 'shuffledOptions'));
    }

    /* -------------------------------------------------------
   SISWA: submit jawaban
------------------------------------------------------- */
    public function submit(Request $request, QuestionSet $kumpulanSoal)
    {
        $set = $kumpulanSoal->load('questions');

        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'in:A,B,C,D,E',
        ]);

        $answers = $request->input('answers', []);
        $score   = 0;

        // Ambil shuffled_options jika mode remedial
        $shuffledOptions = null;
        if ($request->filled('shuffled_options')) {
            $shuffledOptions = json_decode($request->input('shuffled_options'), true);
        }

        foreach ($set->questions as $q) {
            $chosenKey = $answers[$q->id] ?? null;

            if ($chosenKey === null) {
                continue;
            }

            if ($shuffledOptions && isset($shuffledOptions[$q->id])) {
                // Mode remedial: cari value yang dipilih di opsi teracak,
                // lalu cocokkan ke key asli
                $shuffled        = $shuffledOptions[$q->id];
                $chosenValue     = $shuffled[$chosenKey] ?? null;
                $originalOptions = $q->options;
                $originalKey     = array_search($chosenValue, $originalOptions);
                $correct         = ($originalKey !== false && $originalKey === $q->correct_answer);
            } else {
                $correct = ($chosenKey === $q->correct_answer);
            }

            if ($correct) {
                $score++;
            }
        }

        // Simpan shuffled_options ke database agar review bisa sinkron
        $result = QuestionSetResult::create([
            'question_set_id'  => $set->id,
            'student_id'       => Auth::id(),
            'score'            => $score,
            'total_questions'  => $set->questions->count(),
            'answers'          => $answers,
            'shuffled_options' => $shuffledOptions, // null jika bukan remedial
            'taken_at'         => now(),
        ]);

        // Jika ini set evaluasi aktif, kunci kembali is_test_open setelah selesai
        $subjectInfo = \App\Models\SubjectInfo::first();
        if ($subjectInfo && $subjectInfo->current_evaluation_set_id == $set->id) {
            \App\Models\StudentGrade::updateOrCreate(
                ['student_id' => Auth::id()],
                ['is_test_open' => false]
            );
        }

        return redirect()->route('kumpulan-soal.result', $result->id);
    }

    /* -------------------------------------------------------
       GURU: unlock student attempt untuk retry
    ------------------------------------------------------- */
    public function unlockSetAttempt(User $siswa, QuestionSet $set)
    {
        $this->authorizeGuru();

        QuestionSetUnlock::firstOrCreate([
            'student_id' => $siswa->id,
            'question_set_id' => $set->id,
        ]);

        return back()->with('success', 'Akses pengerjaan ulang untuk ' . $siswa->name . ' pada set "' . $set->name . '" berhasil dibuka.');
    }

    /* -------------------------------------------------------
       SISWA: halaman hasil
    ------------------------------------------------------- */
    public function result(QuestionSetResult $result)
    {
        // Pastikan siswa hanya bisa lihat hasilnya sendiri
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($result->student_id !== Auth::id() && !$user->isGuru() && !$user->isAdmin()) {
            abort(403);
        }

        $result->load(['questionSet.questions']);

        return view('kumpulan-soal.result', compact('result'));
    }

    /* -------------------------------------------------------
       SISWA: riwayat semua attempt milik sendiri
    ------------------------------------------------------- */
    public function riwayatSiswa()
    {
        $riwayat = QuestionSetResult::where('student_id', Auth::id())
            ->with('questionSet')
            ->orderByDesc('taken_at')
            ->get();

        return view('kumpulan-soal.riwayat-siswa', compact('riwayat'));
    }

    /* -------------------------------------------------------
       Helper
    ------------------------------------------------------- */
    private function authorizeGuru(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user || (!$user->isGuru() && !$user->isAdmin())) {
            abort(403);
        }
    }
}
