<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Kegiatan</title></head>
<body>
    <h1>Tambah Kegiatan Baru</h1>

    @if (session('success'))
        <div style="color: green; font-weight: bold; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif
    
    <form action="{{ route('activities.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium">Judul Aktivitas</label>
            <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium">Deskripsi</label>
            <textarea name="description" id="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Simpan Aktivitas
        </button>
    </form>
    
    <br>
    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
</body>
</html>