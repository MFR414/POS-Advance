@extends('layouts.master')

@section('content')
<!-- Main content -->

<section class="content">
    <section>
        <div class="d-flex d-flex d-flex justify-content-between align-items-center p-2">
            <div class="title">
                <h2>Detail Transaksi {{ $transaction->transaction_number }}</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="card">
            <div class="card-body" style="padding: 5px 10px;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 0px !important;">
                    <li class="breadcrumb-item"><a href="{{ route('application.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('application.transactions.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $transaction->transaction_number }}</li>
                </ol>
            </nav>
            </div>
        </div>
    </section>
    <br>
    <div class="main-container">
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="container" id="container-info">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title-body">
                                            <h3 class="title">Informasi Transaksi</h3>
                                        </div>
                                        <section>  
                                            <table class="table table-responsive-md table-sm table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>No. Transaksi</td>
                                                        <td>:</td>
                                                        <td id="transaction_number"> {{$transaction->transaction_number}}</td>
                                                        <td>Tgl Transaksi</td>
                                                        <td>:</td>
                                                        <td id="customer_name"> {{$transaction->formatted_transaction_date}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Kode Sales</td>
                                                        <td>:</td>
                                                        <td id="transaction_number"> {{$transaction->sales_code}}</td>
                                                        <td>Nama Pelanggan</td>
                                                        <td>:</td>
                                                        <td id="customer_name"> {{$transaction->customer_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jml Item</td>
                                                        <td>:</td>
                                                        <td id="total_items">{{$transaction->item_total}}</td>
                                                        <td>Sub Total</td>
                                                        <td>:</td>
                                                        <td id="sub_total_price">Rp {{$transaction->formatted_subtotal}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Potongan</td>
                                                        <td>:</td>
                                                        <td id="total_disc_amount">Rp {{$transaction->formatted_discount_total}}</td>
                                                        <td>Total</td>
                                                        <td>:</td>
                                                        <td id="total_price">Rp {{$transaction->formatted_final_total}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title-body">
                                            <h3 class="title">List Item</h3>
                                        </div>
                                        <div>
                                            <table class="table table-sm table-striped table-responsive-md center" style="overflow-x: auto;" id="items_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 1%">
                                                            No
                                                        </th>
                                                        <th>
                                                            Nama Item
                                                        </th>
                                                        <th>
                                                            Qty Item
                                                        </th>
                                                        <th>
                                                            Total
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($transaction->details))
                                                        @foreach ($transaction->details as $item )
                                                            <tr>
                                                                <td>
                                                                    {{ $loop->iteration }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->item_code }} - {{ $item->item_name }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->item_quantity }}
                                                                </td>
                                                                <td id="item_price">
                                                                    {{ $item->formatted_item_price }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.card -->

</section>
@endsection