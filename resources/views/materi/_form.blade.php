<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
    <input type="text" name="title" value="{{ old('title', $materi->title ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
    <input type="text" name="description" value="{{ old('description', $materi->description ?? '') }}" required
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
            <option value="teks"  {{ old('type', $materi->type ?? '') === 'teks'  ? 'selected' : '' }}>Teks</option>
            <option value="video" {{ old('type', $materi->type ?? '') === 'video' ? 'selected' : '' }}>Video</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
        <input type="text" name="duration" value="{{ old('duration', $materi->duration ?? '') }}" placeholder="15 menit"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Konten Teks</label>
    <textarea name="content" rows="5" placeholder="Tulis isi materi di sini..."
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">{{ old('content', $materi->content ?? '') }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Link YouTube (opsional)</label>
    <input type="url" name="video_url" value="{{ old('video_url', $materi->video_url ?? '') }}" placeholder="https://youtube.com/watch?v=..."
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">URL PDF (opsional)</label>
    <input type="url" name="pdf_file" value="{{ old('pdf_file', $materi->pdf_file ?? '') }}" placeholder="https://example.com/file.pdf"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:outline-none">
    <p class="text-xs text-gray-400 mt-1">Masukkan URL publik file PDF</p>
</div>
