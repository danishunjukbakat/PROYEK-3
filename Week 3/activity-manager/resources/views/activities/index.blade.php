
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Aktivitas</title>
</head>
<body>
    <h1>Daftar Aktivitas</h1>

    <form action="{{ route('activities.index') }}" method="GET">
    <label for="status">Filter Status:</label>

    <select name="status" id="status">
        <option value="">Semua</option>

        @foreach ($allowedStatuses as $item)
            <option
                value="{{ $item }}"
                {{ $status === $item ? 'selected' : '' }}
            >
                {{ $item }}
            </option>
        @endforeach
    </select>

    <button type="submit">Filter</button>
</form>

    <a href="{{ route('activities.create') }}">
        + Tambah Aktivitas
    </a>

    @if ($activities->isEmpty())
        <p>Belum ada aktivitas.</p>
    @else
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($activities as $activity)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $activity->title }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->activity_date->format('d-m-Y') }}</td>
                        <td>{{ $activity->category }}</td>
                        <td>{{ $activity->status }}</td>

                        <td>
                            <!-- Tombol Detail -->
                            <a href="{{ route('activities.show', $activity->id) }}">
                                Detail
                            </a>

                            <!-- Tombol Edit -->
                            <a href="{{ route('activities.edit', $activity->id) }}">
                                Edit
                            </a>

                            <!-- Tombol Hapus -->
                            <form
                                action="{{ route('activities.destroy', $activity->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus aktivitas ini?')"
                                style="display: inline;"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
