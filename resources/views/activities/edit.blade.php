<!DOCTYPE html>
<html lang="id">
<head><title>Ubah Kegiatan</title></head>
<body>
    @if ($errors->any())
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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