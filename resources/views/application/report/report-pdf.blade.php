<!DOCTYPE html>
<html>
<head>
    <title>Transaction Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Transaksi</h1>
    <p>Bulan: {{ $search_terms['transaction_month'] ?? 'All' }}</p>
    <p>Tahun: {{ $search_terms['transaction_year'] ?? 'All' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nomor Transaksi</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- Increment the index by 1 to start from 1 instead of 0 -->
                    <td>{{ $transaction->formatted_transaction_date }}</td>
                    <td>{{ $transaction->transaction_number }}</td>
                    <td>Rp {{ $transaction->formatted_final_total }}</td>
                    <td>{{ $transaction->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>