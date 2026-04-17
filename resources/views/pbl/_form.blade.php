<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Aktivitas</label>
    <input type="text" name="title" value="{{ old('title', $pblActivity->title ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Topik</label>
    <input type="text" name="topic" value="{{ old('topic', $pblActivity->topic ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan</label>
        <select name="difficulty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @foreach(['Mudah', 'Sedang', 'Sulit'] as $d)
            <option value="{{ $d }}" {{ old('difficulty', $pblActivity->difficulty ?? '') === $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Materi Terkait</label>
        <select name="related_materi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">-- Pilih Materi --</option>
            @if(isset($materiList))
                @foreach($materiList as $materi)
                    <option value="{{ $materi->id }}" {{ old('related_materi', $pblActivity->related_materi ?? '') == $materi->id ? 'selected' : '' }}>
                        {{ $materi->title }}
                    </option>
                @endforeach
            @endif
        </select>
        <p class="text-xs text-gray-400 mt-1">Pilih materi yang terkait dengan aktivitas ini</p>
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Masalah</label>
    <textarea name="problem" rows="6" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('problem', $pblActivity->problem ?? '') }}</textarea>
</div>
