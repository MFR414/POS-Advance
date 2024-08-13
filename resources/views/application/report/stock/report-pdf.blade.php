<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Stok</h1>
        @foreach ($groupedStockCards as $productName => $stockCards)
            <h2>{{ $productName }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tipe Stok</th>
                        <th>Stok Awal</th>
                        <th>Stok Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockCards as $stockCard)
                        <tr>
                            <td>{{ $stockCard->formatted_created_at }}</td>
                            <td>{{ $stockCard->type }}</td>
                            <td>{{ $stockCard->stock_before }}</td>
                            <td>{{ $stockCard->stock_after }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</body>
</html>