<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Peminjaman</title>
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
    </style>
</head>

<body>
    <h2>Data Peminjaman</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Status</th>
                <th>Tanggal Kembali (Harapan)</th>
                <th>Disetujui Oleh</th>
                <th>Catatan</th>
                <th>Tanggal Dibuat</th>
                <th>Barang Dipinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowRequests as $borrow)
                <tr>
                    <td>{{ $borrow->id }}</td>
                    <td>{{ $borrow->user->username ?? '-' }}</td>
                    <td>{{ ucfirst($borrow->status) }}</td>
                    <td>{{ $borrow->return_date_expected }}</td>
                    <td>{{ $borrow->approver->username ?? '-' }}</td>
                    <td>{{ $borrow->notes }}</td>
                    <td>{{ $borrow->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @foreach ($borrow->borrowDetails as $detail)
                            {{ $detail->itemUnit->item->name ?? '-' }} (SKU: {{ $detail->itemUnit->sku ?? '-' }}, Qty:
                            {{ $detail->quantity }})<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
