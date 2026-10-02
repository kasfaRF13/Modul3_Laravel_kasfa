<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Kegiatan</title>
</head>
<body>
    <h1>Daftar Kegiatan</h1>

    <!-- Form Search & Filter -->
    <form method="GET" action="{{ route('activities.index') }}" style="margin-bottom: 20px; display: flex; gap: 10px;">
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
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Waktu Mulai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $activity)
                <tr>
                    <td>{{ $activity->code }}</td>
                    <td>{{ $activity->title }}</td>
                    <td>{{ $activity->category->name ?? '-' }}</td>
                    <td>{{ ucfirst($activity->status) }}</td>
                    <td>{{ $activity->start_at }}</td>
                    <td>
                        <!-- Tombol Publish -->
                        @if($activity->status === 'draft')
                            <form action="{{ route('activities.publish', $activity) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit">Publish</button>
                            </form>
                        @endif

                        <!-- Tombol Complete -->
                        @if($activity->status === 'published')
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
                    <td colspan="6">Data tidak ditemukan.</td>
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