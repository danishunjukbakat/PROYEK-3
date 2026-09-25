<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aktivitas</title>
</head>
<body>

    <h1>Detail Aktivitas</h1>

    <p>
        <strong>Judul:</strong>
        {{ $activity->title }}
    </p>

    <p>
        <strong>Deskripsi:</strong>
        {{ $activity->description ?? '-' }}
    </p>

    <p>
        <strong>Tanggal:</strong>
        {{ $activity->activity_date }}
    </p>

    <p>
        <strong>Kategori:</strong>
        {{ $activity->category }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ $activity->status }}
    </p>

    <a href="{{ route('activities.index') }}">
        Kembali ke Daftar Aktivitas
    </a>

</body>
</html>
