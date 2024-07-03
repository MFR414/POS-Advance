@extends('layouts.master')

@section('content')
<!-- Main content -->

<section class="content">
    <section>
        <div class="d-flex d-flex d-flex justify-content-between align-items-center p-2">
            <div class="title">
                <h2>Pembayaran Transaksi {{ $transaction->transaction_number }}</h2>
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
                    <li class="breadcrumb-item active" aria-current="page">Pembayaran Transaksi {{ $transaction->transaction_number }}</li>
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
                            <div class="col-lg-7 col-md-12">
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
                            <div class="col-lg-5 col-md-12">
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('application.transactions.payment.submit', $transaction) }}" method="POST" style="margin-bottom: 0" id="transactionForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="transaction_number" value="{{ $transaction->transaction_number }}">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title-body">
                                            <h3 class="title">Data Transaksi</h3>
                                        </div>
                                        <section>
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    <div class="form-group">
                                                        <label class="control-label" for="payment_type">Nomor Transaksi</label>
                                                        <select class="form-control" name="payment_type" id="payment_type" required>
                                                            {{-- <option value=""> Pilih Salah Satu </option> --}}
                                                            <option value="Cash" selected>Tunai</option>
                                                            {{-- <option value="Credit">Kredit</option> --}}
                                                        </select>
                                                        @error('payment_type')
                                                            <span class="has-error">
                                                                {{ $message }}
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-12 " name="payment_form_cash" id="payment_form_cash">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="tax_percentage">Jumlah Kena Pajak (%)</label>
                                                                <input type="number" class="form-control underlined" name="tax_percentage" id="tax_percentage" placeholder="Masukkan Jumlah Kena Pajak (%)">
                                                                @error('tax_percentage')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="tax_total_formatted">Jumlah Kena Pajak (RP)</label>
                                                                <input type="text" class="form-control underlined" name="tax_total_formatted" id="tax_total_formatted" placeholder="Masukkan Jumlah Kena Pajak (%) terlebih dahulu" readonly>
                                                                <input type="hidden" name="tax_total" id="tax_total">
                                                                @error('tax_total_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="other_fees_formatted">Biaya Lainnya</label>
                                                                <input type="text" class="form-control underlined" name="other_fees_formatted" id="other_fees_formatted" placeholder="Masukkan jumlah biaya lainnya">
                                                                <input type="hidden" name="other_fees" id="other_fees">
                                                                @error('other_fees_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="dp_po_formatted">Jumlah DP PO</label>
                                                                <input type="text" class="form-control underlined" name="dp_po_formatted" id="dp_po_formatted" placeholder="Masukkan Jumlah DP PO" value="{{ old('dp_po')}}">
                                                                <input type="hidden" name="dp_po" id="dp_po" value="{{old('dp_po')}}">
                                                                @error('dp_po_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="final_after_tax_total_formatted">Jumlah total setelah pajak dan biaya lainnya</label>
                                                                <input type="text" class="form-control underlined" name="final_after_tax_total_formatted" id="final_after_tax_total_formatted" placeholder="Masukkan jumlah kena pajak (%) dan biaya lainnya terlebih dahulu" readonly>
                                                                <input type="hidden" name="final_after_tax_total" id="final_after_tax_total" value="{{old('final_after_tax_total')}}">
                                                                @error('final_after_tax_total_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="cash_formatted">Jumlah Pembayaran</label>
                                                                <input type="text" class="form-control underlined" name="cash_formatted" id="cash_formatted" placeholder="Masukkan Jumlah Pembayaran" value="{{ old('cash')}}">
                                                                <input type="hidden" name="cash" id="cash" value="{{old('cash')}}">
                                                                @error('cash_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-1">
                                                            <div class="form-group">
                                                                <label class="control-label" for="change_formatted">Total Kembalian</label>
                                                                <input type="text" class="form-control underlined" name="change_formatted" id="change_formatted" readonly>
                                                                <input type="hidden" name="change" id="change" value="{{old('change')}}">
                                                                @error('change_formatted')
                                                                    <span class="has-error">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div name="payment_form_credit" id="payment_form_credit">
                                                    {{-- <div class="col-12 mb-1">
                                                        <div class="form-group">
                                                            <label class="control-label">Jumlah Pembayaran</label>
                                                            <input type="date" class="form-control boxed" id='transaction_date' name="transaction_date">
                                                            @error('transaction_date')
                                                                <span class="has-error">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div> --}}
                                                </div>
                                                <div class="col-sm-12 col-md-12 mt-3">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary" style="width: 100%;" type="submit">Berikutnya</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.card -->

  </section>

  <script>
    var payment_type = 'Cash';
    var tax_total_temp = 0;
    var other_fees_temp = 0;
    var total_amount = parseInt("{{$transaction->final_total}}");
    var final_after_additional_cost_temp = 0;
    var dp_po_temp = 0;
    var paid_amount_temp = 0;
    var change_temp = 0;

    function checkPaymentType (payment_type_params) {
        payment_type_select = payment_type_params;
        if(payment_type_select == 'Cash') {
            $('#payment_form_cash').show();
            $('#payment_form_credit').hide();
        } else {
            $('#payment_form_cash').hide();
            $('#payment_form_credit').show();
        }
        payment_type = payment_type_select;
    }
    //calculate tax amount
    function calculateTax(tax_percentage = null) {
        //use value from tax_percentage variable or tax_percentage input
        var tax_percent = tax_percentage == null || tax_percentage == '' ? 0 : parseInt(tax_percentage);
        tax_total_temp = (tax_percent/100) * total_amount;

        $('#tax_total').val(roundUpToNearestHundred(tax_total_temp));
        $('#tax_total_formatted').val(formatToCurrency(tax_total_temp.toString()));
        calculateFinalTotalAmount()
    }

    function calculateFinalTotalAmount() {
        //use value from other_fees variable or other_fees input
        other_fees_temp = parseInt(other_fees_temp);
        dp_po_temp = parseInt(dp_po_temp);
        final_after_additional_cost_temp = roundUpToNearestHundred((total_amount + tax_total_temp + other_fees_temp) - dp_po_temp);

        //save to other input (hidden)
        $('#other_fees').val(other_fees_temp);
        $('#dp_po').val(dp_po_temp);
        $('#final_after_tax_total').val(final_after_additional_cost_temp);

        //set formatted other fees
        $('#other_fees_formatted').val(formatToCurrency(other_fees_temp.toString()));
        $('#final_after_tax_total_formatted').val(formatToCurrency(final_after_additional_cost_temp.toString()));
        $('#dp_po_formatted').val(formatToCurrency(dp_po_temp.toString()));
    }

    function calculateChange() {
        if(paid_amount_temp >= final_after_additional_cost_temp){
            change_temp = paid_amount_temp - final_after_additional_cost_temp;
            $('#change').val(roundUpToNearestHundred(change_temp));
            $('#cash').val(roundUpToNearestHundred(paid_amount_temp));
            $('#change_formatted').val(formatToCurrency(change_temp.toString()));
            $('#cash_formatted').val(formatToCurrency(paid_amount_temp.toString()));
        } else {
            alert('Jumlah Pembayaran kurang, silahkan cek kembali!');
        }
    }

    function formatToCurrency(val){        
        const value = val.replace(/,/g, '');

        init_value = parseFloat(value);
        value_after_roundup = roundUpToNearestHundred(init_value);

        var final_value = parseFloat(value_after_roundup).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        });

        return final_value;
    }

    function roundUpToNearestHundred(number) {
        var remainder = number % 100;
        if (remainder >= 50) {
            return number + (100 - remainder);
        } else {
            return number - remainder;
        }
    }
    
    $('#payment_type').on('change', function() {
        checkPaymentType(this.value); //
    });

    $('#tax_percentage').blur( function() {
        calculateTax(this.value);
    });

    $('#other_fees_formatted').blur( function() {
        other_fees_temp = this.value;
        calculateFinalTotalAmount();
    });

    $('#dp_po_formatted').blur( function() {
        dp_po_temp = this.value;
        calculateFinalTotalAmount();
    });

    $('#cash_formatted').blur( function() {
        paid_amount_temp = this.value;
        calculateChange();
    });

    $(document).ready(function() {

        //hide all form first
        $('#payment_form_cash').hide();
        $('#payment_form_credit').hide();

        //set default form
        $('#payment_type').val(payment_type);
        checkPaymentType(payment_type);
        calculateFinalTotalAmount();
    });


  </script>

@endsection