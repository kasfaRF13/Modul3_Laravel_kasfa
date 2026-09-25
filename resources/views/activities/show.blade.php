<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kegiatan</title>
</head>
<body>
    <h1>Detail Kegiatan</h1>
    <ul>
        <li><strong>Judul:</strong> {{ $activity->title }}</li>
        <li><strong>Deskripsi:</strong> {{ $activity->description }}</li>
        <li><strong>Kategori:</strong> {{ $activity->category }}</li>
        <li><strong>Tanggal:</strong> {{ $activity->activity_date->format('d-m-Y') }}</li>
        <li><strong>Status:</strong> {{ $activity->status }}</li>
    </ul>
    <br>
    
    <br>
    <a href="{{ route('activities.edit', $activity) }}">Ubah Kegiatan</a>
    
    <form action="{{ route('activities.destroy', $activity) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus Kegiatan</button>
    </form>
    <br><br>

    <a href="/activities">Kembali ke Daftar</a>
</body>
</html>