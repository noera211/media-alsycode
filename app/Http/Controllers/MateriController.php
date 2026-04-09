<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\MateriProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $materiList = Materi::orderBy('created_at')->get();

        // Ambil status progress milik siswa yang login
        $statusMap = [];
        if ($user->isSiswa()) {
            $progresses = MateriProgress::where('user_id', $user->id)->get();
            foreach ($progresses as $p) {
                $statusMap[$p->materi_id] = $p->status;
            }
        }

        $totalMateri    = $materiList->count();
        $completedCount = count(array_filter($statusMap, fn($s) => $s === 'selesai'));
        $progressPct    = $totalMateri > 0 ? round(($completedCount / $totalMateri) * 100) : 0;

        return view('materi.index', compact(
            'materiList', 'statusMap', 'completedCount', 'totalMateri', 'progressPct'
        ));
    }

    public function show(Materi $materi)
    {
        $user   = Auth::user();
        $status = null;

        if ($user->isSiswa()) {
            $progress = MateriProgress::where('user_id', $user->id)
                ->where('materi_id', $materi->id)->first();
            $status = $progress?->status ?? 'belum';
        }

        return view('materi.show', compact('materi', 'status'));
    }

    public function store(Request $request)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:teks,video',
            'duration'    => 'required|string|max:50',
            'content'     => 'nullable|string',
            'video_url'   => 'nullable|url',
            'pdf_file'    => 'nullable|url',
        ]);

        $data['created_by'] = Auth::id();
        Materi::create($data);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function update(Request $request, Materi $materi)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:teks,video',
            'duration'    => 'required|string|max:50',
            'content'     => 'nullable|string',
            'video_url'   => 'nullable|url',
            'pdf_file'    => 'nullable|url',
        ]);

        $materi->update($data);

        return redirect()->route('materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        $this->authorizeGuru();
        $materi->delete();
        return redirect()->route('materi.index')->with('success', 'Materi berhasil dihapus.');
    }

    // Siswa menandai status materi (belum/sedang/selesai)
    public function updateStatus(Request $request, Materi $materi)
    {
        $request->validate(['status' => 'required|in:belum,sedang,selesai']);
        $user = Auth::user();

        if (!$user->isSiswa()) {
            abort(403);
        }

        $progress = MateriProgress::firstOrNew([
            'user_id'   => $user->id,
            'materi_id' => $materi->id,
        ]);

        $progress->status = $request->status;
        $progress->completed_at = $request->status === 'selesai' ? now() : null;
        $progress->save();

        return redirect()->route('materi.show', $materi)
            ->with('success', 'Status materi diperbarui.');
    }

    private function authorizeGuru(): void
    {
        if (!Auth::user()->isGuru() && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
