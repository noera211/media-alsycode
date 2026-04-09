<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
    <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="siswa" {{ old('role', $user->role ?? '') === 'siswa' ? 'selected' : '' }}>Siswa</option>
        <option value="guru"  {{ old('role', $user->role ?? '') === 'guru'  ? 'selected' : '' }}>Guru</option>
    </select>
</div>
