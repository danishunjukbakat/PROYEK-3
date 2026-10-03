<h1>Daftar Category</h1>

@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Slug</th>
            <th>Jumlah Activity</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->activities_count }}</td>
                <td>
                    <form
                        action="{{ route('categories.destroy', $category) }}"
                        method="POST"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>