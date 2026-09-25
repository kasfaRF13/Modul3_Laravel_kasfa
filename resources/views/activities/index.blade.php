<!DOCTYPE html>
<html lang="id">
<head><title>Daftar Kegiatan</title></head>
<body>
    <h1>Daftar Kegiatan</h1>
    
    <!-- Tombol Tambah Baru -->
    <a href="{{ route('activities.create') }}" style="display:inline-block; margin-bottom:15px; padding:5px 10px; background:#007BFF; color:white; text-decoration:none;">+ Tambah Kegiatan Baru</a>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th> <!-- Kolom Baru untuk tombol -->
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $activity)
            <tr>
                <td>{{ $activity->title }}</td>
                <td>{{ $activity->category }}</td>
                <td>{{ $activity->activity_date }}</td>
                <td>{{ $activity->status }}</td>
                <td>
                    <!-- Tombol Detail dan Ubah -->
                    <a href="{{ route('activities.show', $activity) }}">Detail</a> | 
                    <a href="{{ route('activities.edit', $activity) }}">Ubah</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>