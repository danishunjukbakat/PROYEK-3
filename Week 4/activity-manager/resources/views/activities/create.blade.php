<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aktivitas</title>
</head>
<body>
    <h1>Tambah Aktivitas</h1>

    <form action="{{ route('activities.store') }}" method="POST">
    @csrf

    <label for="code">Kode Aktivitas</label><br>
    <input type="text" id="code" name="code"
           value="{{ old('code') }}"><br><br>

    <label for="title">Judul Aktivitas</label><br>
    <input type="text" id="title" name="title"
           value="{{ old('title') }}"><br><br>

    <label for="description">Deskripsi</label><br>
    <textarea id="description" name="description">{{ old('description') }}</textarea><br><br>

    <label for="category_id">Kategori</label><br>
    <select id="category_id" name="category_id">
        <option value="">-- Pilih Kategori --</option>

        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select><br><br>

    <label for="start_at">Waktu Mulai</label><br>
    <input type="datetime-local" id="start_at" name="start_at"
           value="{{ old('start_at') }}"><br><br>

    <label for="end_at">Waktu Selesai</label><br>
    <input type="datetime-local" id="end_at" name="end_at"
           value="{{ old('end_at') }}"><br><br>

    <label for="location">Lokasi</label><br>
    <input type="text" id="location" name="location"
           value="{{ old('location') }}"><br><br>

    <label for="capacity">Kapasitas</label><br>
    <input type="number" id="capacity" name="capacity"
           value="{{ old('capacity') }}"><br><br>

    <button type="submit">Simpan</button>
</form>
</body>
</html>
