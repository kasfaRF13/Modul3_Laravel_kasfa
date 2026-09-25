<!DOCTYPE html>
<html lang="id">
<head><title>Ubah Kegiatan</title></head>
<body>
    <h1>Ubah Kegiatan</h1>
    
    <form action="{{ route('activities.update', $activity) }}" method="POST">
        @csrf
        @method('PUT')
        <!-- Memanggil form partial yang sama -->
        @include('activities._form')
        
        <br><br>
        <button type="submit">Perbarui Kegiatan</button>
    </form>
    
    <br>
    <a href="{{ route('activities.show', $activity) }}">Batal dan Kembali ke Detail</a>
</body>
</html>