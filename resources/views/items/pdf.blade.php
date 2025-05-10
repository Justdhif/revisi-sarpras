<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <h3>Laporan Data Barang</h3>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Image</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td>
                        @if ($item->image_url)
                            <img src="{{ public_path($item->image_url) }}" width="60">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $item->description }}</td>
                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
