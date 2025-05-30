<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Detail Pengembalian Barang</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        img {
            width: 80px;
        }
    </style>
</head>

<body>
    <h2>Detail Pengembalian Barang</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Nama Barang</th>
                <th>SKU Unit</th>
                <th>Kondisi</th>
                <th>Foto</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Tanggal Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnDetails as $detail)
                <tr>
                    <td>{{ $detail->id }}</td>
                    <td>{{ $detail->returnRequest->borrowRequest->user->username ?? '-' }}</td>
                    <td>{{ $detail->itemUnit->item->name ?? '-' }}</td>
                    <td>{{ $detail->itemUnit->sku ?? '-' }}</td>
                    <td>{{ $detail->condition }}</td>
                    <td>
                        @if ($detail->photo)
                            <img src="{{ public_path('storage/' . $detail->photo) }}" alt="Foto">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->notes }}</td>
                    <td>{{ $detail->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
