<?php

namespace App\Http\Controllers;

use App\Models\TestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    public function index()
    {
        $this->authorizeGuru();
        $questions = TestQuestion::orderBy('created_at', 'desc')->get();
        return view('bank-soal.index', compact('questions'));
    }

    public function store(Request $request)
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

        // Perbaikan: Bungkus argumen $request->only ke dalam array []
        TestQuestion::create(array_merge(
            $request->only(['question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer']),
            ['created_by' => Auth::id()]
        ));

        return redirect()->route('bank-soal.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update(Request $request, TestQuestion $question)
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

        // Perbaikan: Bungkus argumen $request->only ke dalam array []
        $question->update(
            $request->only(['question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'])
        );

        return redirect()->route('bank-soal.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(TestQuestion $question)
    {
        $this->authorizeGuru();
        $question->delete();
        return redirect()->route('bank-soal.index')
            ->with('success', 'Soal berhasil dihapus.');
    }

    private function authorizeGuru(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user || (!$user->isGuru() && !$user->isAdmin())) {
            abort(403);
        }
    }
}