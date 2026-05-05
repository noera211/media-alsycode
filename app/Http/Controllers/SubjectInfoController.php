<?php

namespace App\Http\Controllers;

use App\Models\SubjectInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectInfoController extends Controller
{
    public function edit()
    {
        $this->authorizeGuru();
        
        // Menggunakan first() untuk mengambil 1 baris data saja, bukan get()
        $info = SubjectInfo::first(); 
        
        return view('subject-info.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $this->authorizeGuru();

        $data = $request->validate([
            'mata_pelajaran'      => 'required|string|max:100',
            'kelas'               => 'required|string|max:20',
            'deskripsi'           => 'nullable|string|max:1000',
            'tujuan_pembelajaran' => 'nullable|string|max:3000',
        ]);

        // Cari data pertama
        $info = SubjectInfo::first();

        // Jika data sudah ada, lakukan update. Jika belum ada, buat data baru.
        if ($info) {
            $info->update($data);
        } else {
            SubjectInfo::create($data);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Informasi mata pelajaran berhasil diperbarui.');
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