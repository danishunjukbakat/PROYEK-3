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
        <label for="title">Judul Aktivitas</label><br>
<input type="text" id="title" name="title"><br><br>

<label for="description">Deskripsi</label><br>
<textarea id="description" name="description"></textarea><br><br>

<label for="activity_date">Tanggal Aktivitas</label><br>
<input type="date" id="activity_date" name="activity_date"><br><br>

<label for="category">Kategori</label><br>
<input type="text" id="category" name="category"><br><br>

<label for="status">Status</label><br>
<select id="status" name="status">
            <option value="Planned">Planned</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Done">Done</option>
        </select><br><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
