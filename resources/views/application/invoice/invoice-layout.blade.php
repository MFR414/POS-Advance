<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>

        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: table
        }

        .header-container {
            padding: 20px;
        }

        .header-container .table-header {
            border: none;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .table-header #company {
            font-weight: 300;
            margin: 0px;
            font-size: 12pt
        }

        .table-header #title {
            font-weight: bolder;
            margin: 0px;
            font-size: 15pt;
        }

        .table-header tr td span {
            font-size: 9pt;
        }

        .item-container .item-table {
            border: none;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            min-width: 300px;
        }

        .table-sign{
            border: none;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 0;
        }

        .item-container .item-table, .item-tr{
            border: 1px solid black;
            border-collapse: collapse;
        }

        .item-container .item-table tr td{
            font-size: 9pt !important;
        }

        .footer-container .footer-table {
            border: none;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
            table-layout: fixed;
        }

        .footer-container .footer-table tr td {
            font-size: 9pt !important;
        }

        .align-right {
            text-align: right;
        }

    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <div class="header-container">
                <table class="table-header">
                    <tr>
                        <td>
                            <span id="title">FAKTUR PENJUALAN</span>
                        </td>
                        <td>
                            <span>No Transaksi : {{ $data->transaction_number }}</span>
                        </td>
                        <td>
                            <span>Dept : UTM</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="company">TIGA WARNA Digital Printing</span>
                        </td>
                        <td>
                            <span>Tanggal : {{ $data->formatted_create_date }}</span>
                        </td>
                        <td>
                            <span>User : {{ $data->creator}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>JALAN SULTAN AGUNG 52 KEPANJEN</span>
                        </td>
                        <td>
                            <span>Kode Sales : {{ $data->sales_code}}</span>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span> 0341 3904542 / 082132418501</span>
                        </td>
                        <td>
                            <span> Pelanggan : {{ $data->customer_name}}</span>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <span> Alamat : {{ $data->customer_address}}</span>
                        </td>
                        <td>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="body">
            <div class="item-container">
                <table class="item-table">
                    <thead>
                        <tr class="item-tr">
                            <td>No</td>
                            <td>Kode Item</td>
                            <td>Nama Item</td>
                            <td>Jml Satuan</td>
                            <td>Harga</td>
                            <td>Pot</td>
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->item_quantity}}{{$item->item_quantity_unit }}</td>
                                <td>{{ $item->formatted_item_price}}</td>
                                <td>{{ $item->disc_percent}}%</td>
                                <td>{{ $item->formatted_item_total_price}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <div class="footer-container">
                <table class="footer-table">
                    <tr>
                        <td style="width:10%">Keterangan : </td>
                        <td style="width:15%"></td>
                        <td colspan="2">Jml Item : </td>
                        <td class="align-right">{{ $data->item_total }}</td>
                        <td>Sub Total :</td>
                        <td class="align-right">{{ $data->formatted_subtotal }}</td>
                    </tr>
                    <tr>
                        <td rowspan="5" colspan="2" style="width:25%">
                            <table class="table-sign">
                                <tr>
                                    <td>Hormat Kami</td>
                                    <td>Penerima</td>
                                </tr>
                                <tr>
                                    <td><br></td>
                                    <td><br></td>
                                </tr>
                                <tr>
                                    <td>(..............)</td>
                                    <td>(..............)</td>
                                </tr>
                            </table>
                        </td>
                        <td>Potongan : </td>
                        <td class="align-right">{{ $data->discount_percentage }}%</td>
                        <td class="align-right">{{ $data->formatted_discount_total }}</td>
                        <td>Total Akhir :</td>
                        <td class="align-right">{{ $data->formatted_final_total_after_additional }}</td>
                    </tr>
                    <tr>
                        <!-- The first cell in this row is merged with the cell above it -->
                        <td>Pajak : </td>
                        <td class="align-right">{{ $data->tax_percentage }}%</td>
                        <td class="align-right">{{ $data->formatted_tax_total }}</td>
                        <td>DP PO :</td>
                        <td class="align-right">{{ $data->formatted_dp_po }}</td>
                    </tr>
                    <tr>
                        <!-- The first cell in this row is merged with the cell above it -->
                        <td colspan="2">Biaya Lain : </td>
                        <td class="align-right">{{ $data->formatted_other_fees }}</td>
                        <td>Tunai :</td>
                        <td class="align-right">{{ $data->formatted_cash }}</td>
                    </tr>
                    <tr>
                        <!-- The first cell in this row is merged with the cell above it -->
                        <td colspan="2"></td>
                        <td></td>
                        <td>Kredit :</td>
                        <td class="align-right">0,00</td>
                    </tr>
                    <tr>
                        <!-- The first cell in this row is merged with the cell above it -->
                        <td colspan="2"></td>
                        <td></td>
                        <td>K. Debit :</td>
                        <td class="align-right">0,00</td>
                    </tr>
                    <tr>
                        <td>Terbilang : </td>
                        <td colspan="2">{{ $data->terbilang }}</td>
                        <td></td>
                        <td></td>
                        <td>K. Kredit :</td>
                        <td class="align-right">0,00</td>
                    </tr>
                    <tr>
                        <td>{{ $data->formatted_create_date }}</td>
                        <td></td>
                        <td colspan="2"></td>
                        <td></td>
                        <td>Kembali :</td>
                        <td class="align-right">{{ $data->formatted_return }}</td>
                    </tr>
                    <tr>
                        <td style="width: 25%"></td>
                        <td style="width: 38%"></td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td class="align-right">{{ $data->creator }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
