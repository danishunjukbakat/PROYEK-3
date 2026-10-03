<h1>Edit Activity</h1>

@if ($errors->any())
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

<form action="{{ route('activities.update', $activity) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="code">Kode Aktivitas</label><br>
    <input
        type="text"
        id="code"
        name="code"
        value="{{ old('code', $activity->code) }}"
    >
    <br><br>

    <label for="title">Judul Aktivitas</label><br>
    <input
        type="text"
        id="title"
        name="title"
        value="{{ old('title', $activity->title) }}"
    >
    <br><br>

    <label for="description">Deskripsi</label><br>
    <textarea
        id="description"
        name="description"
    >{{ old('description', $activity->description) }}</textarea>
    <br><br>

    <label for="category_id">Kategori</label><br>
    <select id="category_id" name="category_id">
        <option value="">-- Pilih Kategori --</option>

        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ old('category_id', $activity->category_id) == $category->id ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <br><br>

    <label for="start_at">Waktu Mulai</label><br>
    <input
        type="datetime-local"
        id="start_at"
        name="start_at"
        value="{{ old('start_at', $activity->start_at->format('Y-m-d\TH:i')) }}"
    >
    <br><br>

    <label for="end_at">Waktu Selesai</label><br>
    <input
        type="datetime-local"
        id="end_at"
        name="end_at"
        value="{{ old('end_at', $activity->end_at->format('Y-m-d\TH:i')) }}"
    >
    <br><br>

    <label for="location">Lokasi</label><br>
    <input
        type="text"
        id="location"
        name="location"
        value="{{ old('location', $activity->location) }}"
    >
    <br><br>

    <label for="capacity">Kapasitas</label><br>
    <input
        type="number"
        id="capacity"
        name="capacity"
        value="{{ old('capacity', $activity->capacity) }}"
    >
    <br><br>

    

    <button type="submit">Update</button>
</form>