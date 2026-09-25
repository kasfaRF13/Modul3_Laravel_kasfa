<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Kegiatan</title></head>
<body>
    <h1>Tambah Kegiatan Baru</h1>
    
    <form action="{{ route('activities.store') }}" method="POST">
        @csrf
        <!-- Memanggil form partial tadi -->
        @include('activities._form')
        
        <br><br>
        <button type="submit">Simpan Kegiatan</button>
    </form>
    
    <br>
    <a href="{{ route('activities.index') }}">Kembali ke Daftar</a>
</body>
</html>