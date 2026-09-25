<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aktivitas</title>
</head>
<body>
    <h1>Edit Aktivitas</h1>

    <form action="{{ route('activities.update', $activity->id) }}" method="POST">
        @csrf
        @method('PUT')

       <label for="title">Judul Aktivitas</label><br>
<input type="text" id="title" name="title"
       value="{{ $activity->title }}"><br><br>

<label for="description">Deskripsi</label><br>
<textarea id="description" name="description">{{ $activity->description }}</textarea><br><br>

<label for="activity_date">Tanggal Aktivitas</label><br>
<input type="date" id="activity_date" name="activity_date"
       value="{{ $activity->activity_date->format('Y-m-d') }}"><br><br>

<label for="category">Kategori</label><br>
<input type="text" id="category" name="category"
       value="{{ $activity->category }}"><br><br>

<label for="status">Status</label><br>
<select id="status" name="status">
            <option value="Planned" {{ $activity->status == 'Planned' ? 'selected' : '' }}>Planned</option>
            <option value="Ongoing" {{ $activity->status == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="Done" {{ $activity->status == 'Done' ? 'selected' : '' }}>Done</option>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
