<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Kegiatan</title>
</head>
<body>
    <!-- Pesan Sukses -->
@if (session('success'))
    <div style="color: green; font-weight: bold; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

<!-- Pesan Error -->
@if ($errors->any())
    <div style="color: red; font-weight: bold; margin-bottom: 15px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <h1>Daftar Kegiatan</h1>

    <!-- Tombol Tambah Baru -->
    <a href="{{ route('activities.create') }}" style="display:inline-block; margin-bottom:15px; padding:8px 12px; background:#007BFF; color:white; text-decoration:none; border-radius:4px;">+ Tambah Kegiatan Baru</a>

    <!-- Form Search & Filter (Task 2) -->
    <form method="GET" action="{{ route('activities.index') }}" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
        <input type="text" name="search" placeholder="Cari Judul / Kode..." value="{{ request('search') }}">
        
        <select name="category_id">
            <option value="">-- Semua Kategori --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select name="status">
            <option value="">-- Semua Status --</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>

        <select name="sort">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('activities.index') }}">Reset</a>
    </form>

    <!-- Tabel Daftar Kegiatan -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $activity)
            <tr>
                <td>{{ $activity->title }}</td>
                <td>{{ $activity->category?->name ?? '-' }}</td>
                <td>{{ $activity->start_at }}</td>
                <td>{{ ucfirst($activity->status) }}</td>
                <td>
                    <a href="{{ route('activities.show', $activity) }}">Detail</a> | 
                    <a href="{{ route('activities.edit', $activity) }}">Ubah</a>

                    <!-- Tombol Publish (Muncul hanya jika status 'draft') -->
                    @if($activity->status === 'draft')
                        | 
                        <form action="{{ route('activities.publish', $activity) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Publish</button>
                        </form>
                    @endif

                    <!-- Tombol Complete (Muncul hanya jika status 'published') -->
                    @if($activity->status === 'published')
                        | 
                        <form action="{{ route('activities.complete', $activity) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Complete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Data kegiatan tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div style="margin-top: 15px;">
        {{ $activities->links() }}
    </div>
</body>
</html>