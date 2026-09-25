<div>
    <label for="title">Judul:</label>
    <input type="text" id="title" name="title" value="{{ old('title', $activity->title ?? '') }}">
    @error('title') <p style="color:red; margin:0;">{{ $message }}</p> @enderror
</div>
<br>
<div>
    <label for="description">Deskripsi:</label>
    <textarea id="description" name="description">{{ old('description', $activity->description ?? '') }}</textarea>
    @error('description') <p style="color:red; margin:0;">{{ $message }}</p> @enderror
</div>
<br>
<div>
    <label for="activity_date">Tanggal:</label>
    <input type="date" id="activity_date" name="activity_date" value="{{ old('activity_date', isset($activity) ? $activity->activity_date->format('Y-m-d') : '') }}">
    @error('activity_date') <p style="color:red; margin:0;">{{ $message }}</p> @enderror
</div>
<br>
<div>
    <label for="category">Kategori:</label>
    <input type="text" id="category" name="category" value="{{ old('category', $activity->category ?? '') }}">
    @error('category') <p style="color:red; margin:0;">{{ $message }}</p> @enderror
</div>
<br>
<div>
    <label for="status">Status:</label>
    <select name="status" id="status">
        @foreach (['Planned', 'Ongoing', 'Done'] as $status)
            <option value="{{ $status }}" @selected(old('status', $activity->status ?? 'Planned') === $status)>
                {{ $status }}
            </option>
        @endforeach
    </select>
    @error('status') <p style="color:red; margin:0;">{{ $message }}</p> @enderror
</div>