<!DOCTYPE html>
<html>

<head>
    <title>Item Units PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        img {
            width: 60px;
            height: 60px;
        }
    </style>
</head>

<body>
    <h2>Daftar Item Units</h2>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Condition</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>QR Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemUnits as $unit)
                <tr>
                    <td>{{ $unit->sku }}</td>
                    <td>{{ $unit->condition }}</td>
                    <td>{{ $unit->status }}</td>
                    <td>{{ $unit->quantity }}</td>
                    <td>
                        @if ($unit->qr_image_url)
                            <img src="{{ public_path('storage/' . $unit->qr_image_url) }}" alt="QR">
                        @else
                            Tidak Ada
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
