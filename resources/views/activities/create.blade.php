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

    @if ($errors->any())
        <div style="color: red; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('activities.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="category_id">Kategori</label><br>
            <select name="category_id" id="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="code">Kode Kegiatan</label><br>
            <input type="text" name="code" id="code" maxlength="30" value="{{ old('code') }}" required>
        </div>

        <div class="mb-4">
            <label for="title">Judul Aktivitas</label><br>
            <input type="text" name="title" id="title" maxlength="150" value="{{ old('title') }}" required>
        </div>

        <div class="mb-4">
            <label for="description">Deskripsi</label><br>
            <textarea name="description" id="description">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="start_at">Waktu Mulai</label><br>
            <input type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at') }}" required>
        </div>

        <div class="mb-4">
            <label for="end_at">Waktu Selesai</label><br>
            <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at') }}" required>
        </div>

        <div class="mb-4">
            <label for="location">Lokasi</label><br>
            <input type="text" name="location" id="location" maxlength="255" value="{{ old('location') }}" required>
        </div>

        <div class="mb-4">
            <label for="capacity">Kapasitas (1-500)</label><br>
            <input type="number" name="capacity" id="capacity" min="1" max="500" value="{{ old('capacity') }}" required>
        </div>

        <button type="submit">Simpan Aktivitas</button>
    </form>

    <br>
    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
</body>
</html>