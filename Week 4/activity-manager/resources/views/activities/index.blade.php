
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Aktivitas</title>
</head>
<body>
    <h1>Daftar Aktivitas</h1>

    @if (session('success'))
    <p>{{ session('success') }}</p>
@endif

@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

<form action="{{ route('activities.index') }}" method="GET">

    <label for="search">Search:</label>

    <input
        type="text"
        name="search"
        id="search"
        value="{{ $search ?? '' }}"
        placeholder="Cari code atau title"
    >

    <label for="category_id">Kategori:</label>

    <select name="category_id" id="category_id">
        <option value="">Semua Kategori</option>

        @foreach ($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ $categoryId == $category->id ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <label for="status">Status:</label>

    <select name="status" id="status">
        <option value="">Semua Status</option>

        @foreach ($allowedStatuses as $item)
            <option
                value="{{ $item }}"
                {{ $status == $item ? 'selected' : '' }}
            >
                {{ $item }}
            </option>
        @endforeach
    </select>

    <label for="sort">Urutan:</label>

<select name="sort" id="sort">
    <option
        value="oldest"
        {{ $sort === 'oldest' ? 'selected' : '' }}
    >
        Terlama
    </option>

    <option
        value="newest"
        {{ $sort === 'newest' ? 'selected' : '' }}
    >
        Terbaru
    </option>
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
                        <td>{{ $activity->start_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $activity->category->name }}</td>
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

                            <!-- Tombol Publish -->
                            @if ($activity->status === 'draft')
                                <form
                                    action="{{ route('activities.publish', $activity) }}"
                                    method="POST"
                                    style="display: inline;"
                                >
                                    @csrf
                                    <button type="submit">Publish</button>
                                </form>
                            @endif

                            @if ($activity->status === 'published')
                                <form
                                    action="{{ route('activities.complete', $activity) }}"
                                    method="POST"
                                    style="display: inline;"
                                >
                                    @csrf
                                    <button type="submit">Complete</button>
                                </form>
                            @endif

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
        {{ $activities->links() }}
    @endif
</body>
</html>
