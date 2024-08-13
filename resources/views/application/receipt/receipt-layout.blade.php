<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            width: 30mm;
            /* height: 58mm; */
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .receipt {
            width: 30mm;
            /* height: 58mm; */
            /* padding: 0.2mm; */
        }

        .receipt h1 {
            text-align: center;
            font-size: 10px;
            margin: 0.5mm 0;
        }

        .receipt p {
            text-align: center;
            margin: 0.5mm 0;
            font-size: 5px;
        }

        .details table {
            font-size: 5px;
            width: 100%;
        }

        .details table td {
            text-align: center;
        }

        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 0.5mm 0;
        }

        .body-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .body-container table td {
            /* border-bottom: 1px solid #ddd; */
            padding: 0.5mm;
            text-align: left;
            font-size: 5px;
        }

        .body-container table th {
            background-color: #f9f9f9;
        }

        .total table {
            font-size: 5px;
            width: 100%;
        }

        .total table td {
            text-align: center;
        }

        /* 
        .total p {
            display: flex;
            justify-content: space-between;
            font-size: 4px;
        }

        .total p strong {
            font-size: 5px;
        }

        .total p span {
            font-weight: bold;
        } */
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header-container">
            <h1>{{ $setting->store_name }}</h1>
            <p>{{ $setting->store_address }}</p>
            <p>{{ $setting->store_phone_number_one }} / {{ $setting->store_phone_number_two }}</p>
            <hr>
            <div class="details">
                <table>
                    <tr>
                        <td>
                            <span>Tanggal</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>{{$data->formatted_create_date}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Kasir</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>{{ $data->creator}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>No. Transaksi</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>{{ $data->transaction_number }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <hr>
        </div>
        <div class="body-container">
            <table>
                <thead>
                    <tr>
                        <td>Item</td>
                        <td>Jml Satuan</td>
                        <td>Harga</td>
                        <td>Pot</td>
                        <td>Total</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->items as $item)
                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_quantity}} {{$item->item_quantity_unit }}</td>
                            <td>{{ $item->formatted_item_price}}</td>
                            <td>{{ $item->disc_percent}}%</td>
                            <td>{{ $item->formatted_item_total_price}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
        </div>
        <div class="footer-container">
            <div class="total">
                <table>
                    <tr>
                        <td>
                            <span>Subtotal</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>Rp {{ $data->formatted_subtotal }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Pajak ({{ $data->tax_percentage }}%)</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>Rp {{ $data->formatted_tax_total }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Biaya lain</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <span>Rp {{ $data->formatted_other_fees }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Total</span>
                        </td>
                        <td>
                            <span>:</span>
                        </td>
                        <td>
                            <strong><span>Rp {{ $data->formatted_final_total_after_additional }}</span></strong>
                        </td>
                    </tr>
                </table>
                {{-- <p>Subtotal: <span>Rp {{ $data->formatted_subtotal }}</span></p>
                <p>Pajak ({{ $data->tax_percentage }}%): <span>Rp {{ $data->formatted_tax_total }}</span></p>
                <p>Biaya lain: <span>Rp {{ $data->formatted_other_fees }}</span></p>
                <p><strong>Total: <span>Rp {{ $data->formatted_final_total_after_additional }}</span></strong></p> --}}
            </div>
            <hr>
            <p>Terima kasih atas kunjungan Anda!</p>
        </div>
    </div>
</body>
</html>