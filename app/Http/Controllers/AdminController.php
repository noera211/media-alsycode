<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\PblActivity;
use App\Models\PblSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'   => User::count(),
            'total_guru'    => User::where('role', 'guru')->count(),
            'total_siswa'   => User::where('role', 'siswa')->count(),
            'total_materi'  => Materi::count(),
            'total_pbl'     => PblActivity::count(),
            'pending_nilai' => PblSubmission::whereNull('nilai')->count(),
        ];

        $recentUsers = User::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('role', '!=', 'admin')
            ->when($search, fn($q) => $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            }))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users', compact('users', 'search'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:guru,siswa',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'role'  => 'required|in:guru,siswa',
        ]);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users')
            ->with('success', "User berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|min:6']);
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.users')
            ->with('success', 'Password berhasil direset.');
    }

    public function destroy(User $user)
    {
        // Cegah admin menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus secara permanen!');
    }
}
