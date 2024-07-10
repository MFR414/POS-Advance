@extends('layouts.master')

@section('content')
<!-- Main content -->

<section class="content">
    <section>
        <div class="d-flex d-flex d-flex justify-content-between align-items-center p-2">
            <div class="title">
                <h2>Buat Transaksi</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="card">
            <div class="card-body" style="padding: 5px 10px;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 0px !important;">
                    <li class="breadcrumb-item"><a href="{{ route('application.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Buat Transaksi</li>
                </ol>
            </nav>
            </div>
        </div>
    </section>
    <br>
    <div class="main-container">
        <section class="section">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="col-6">
                        <form action="{{ route('application.transactions.store') }}" method="POST" style="margin-bottom: 0" id="transactionForm">
                            {{ csrf_field() }}
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title-body">
                                        <h3 class="title">Data Transaksi</h3>
                                    </div>
                                    <section>
                                        <input type="hidden" name="items" id="itemsArr">
                                        <div class="row">
                                            <div class="col-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Nomor Transaksi</label>
                                                    <input type="text" class="form-control boxed" name="transaction_number" value="{{ $transaction_number }}" placeholder="Masukkan Kode Sales Admin" readonly="readonly">
                                                    @error('transaction_number')
                                                        <span class="has-error">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Tanggal Transaksi</label>
                                                    <input type="date" class="form-control boxed" id='transaction_date' name="transaction_date" value="{{ $transaction_date }}" readonly="readonly">
                                                    @error('transaction_date')
                                                        <span class="has-error">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Kode Sales</label>
                                                    <input type="text" class="form-control boxed" name="sales_code" value="{{ old('sales_code') }}" placeholder="Masukkan Kode Sales Admin">
                                                    @error('sales_code')
                                                        <span class="has-error">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Nama Pelanggan <sup style='color: red'>*</sup></label>
                                                    <input type="text" class="form-control underlined" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" placeholder="Masukkan Nama Pelanggan" required>
                                                    @error('customer_name')
                                                        <span class="has-error">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Alamat Pelanggan </label>
                                                    <input type="text" class="form-control underlined" name="customer_address" value="{{ old('customer_address') }}" placeholder="Masukkan Alamat Pelanggan">
                                                    @error('customer_address')
                                                        <span class="has-error">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title-body">
                                    <h3 class="title">Input Data Item</h3>
                                </div>
                                <section>
                                    <div class="row">
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Produk <sup style='color: red'>*</sup></label>
                                                <select class="form-control" name="product-select" id="product-select" required>
                                                    <option value="" selected> Pilih Salah Satu </option>
                                                    @foreach ($products as $item)
                                                        <option  
                                                            value="{{ $item->id }}" 
                                                            data-code="{{ $item->code }}" 
                                                            data-name="{{ $item->name }}"
                                                            data-price="{{ $item->price }}"
                                                            data-uom="{{ $item->uom }}"
                                                        >{{ $item->code }} - {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- <input type="text" class="form-control underlined" name="item_quantity_unit" id="item_quantity_unit" placeholder="Masukkan Satuan item" required> --}}
                                            </div>
                                        </div>
                                        {{-- <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Kode Item</label>
                                                <input type="text" class="form-control boxed" name="item_code" id="item_code" placeholder="Masukkan Kode Item">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Nama Item <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="item_name" id="item_name" placeholder="Masukkan Nama item">
                                            </div>
                                        </div> --}}
                                        <div class="col-md-6 col-sm-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Jumlah Item <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="item_quantity" id="item_quantity" placeholder="Masukkan Jumlah item" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Satuan Item</label>
                                                <select class="form-control" name="item_quantity_unit" id="item_quantity_unit" disabled>
                                                    <option value="" selected> Pilih Salah Satu </option>
                                                    <option value="Meter">Meter</option>
                                                    <option value="Pcs">Pcs</option>
                                                </select>
                                                {{-- <input type="text" class="form-control underlined" name="item_quantity_unit" id="item_quantity_unit" placeholder="Masukkan Satuan item" required> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Harga Item Per Satuan</label>
                                                <input type="text" class="form-control underlined" name="item_price_formatted" id="item_price_formatted" placeholder="Masukkan Harga item per satuan" readonly>
                                                {{-- <input type="hidden" name="item_price" id="item_price"> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Diskon (%)</label>
                                                <input type="number" class="form-control underlined" name="disc_percent" id="disc_percent" value="0" placeholder="Masukkan Potongan Harga (%)">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mt-3">
                                            <div class="form-group">
                                                <a class="btn btn-primary" style="width: 100%;" onclick="addItem()">Simpan Item</a>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                            </div>
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
                                <table class="table table-striped table-responsive center" style="overflow-x: auto;" id="items_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%">
                                                No
                                            </th>
                                            <th>
                                                Kode Item
                                            </th>
                                            <th>
                                                Nama Item
                                            </th>
                                            <th>
                                                Jumlah Satuan Item
                                            </th>
                                            <th>
                                                Diskon (%)
                                            </th>
                                            <th>
                                                Harga Item
                                            </th>
                                            <th>
                                                Total
                                            </th>
                                            <th>
                                                Opsi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td colspan="2" style="text-align: right !important;">Jml Item</td>
                                            <td colspan="2" id="total_items"></td>
                                            <td style="text-align: right !important;">Sub Total</td>
                                            <td id="sub_total_price"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="2" style="text-align: right !important;">Diskon</td>
                                            <td id="total_disc_percent"></td>
                                            <td id="total_disc_amount"></td>
                                            <td style="text-align: right !important;">Total</td>
                                            <td id="total_price"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <br>
                            <div class="col-sm-12 col-md-12 mt-3">
                                <div class="form-group">
                                    <button class="btn btn-primary" style="width: 100%;" id="submitTransactionButton">Berikutnya</button>
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

  <script>
    var transaction_date = "<?php echo"$transaction_date"?>"; 
    var items = new Array();
    var total_disc_amount = 0;
    let selectedProduct = {};

    $('#submitTransactionButton').click(function(e){
        e.preventDefault(); // Prevent the default action of the button
        
        if(document.getElementById("customer_name").value == ""){
            alert("Nama Pelanggan Kosong! Silahkan masukkan nama pelanggan terlebih dahulu");
        } else if(items.length <= 0){
            alert("Item List Kosong! Silahkan tambahkan item terlebih dahulu");
        } else{
            if (confirm('Apakah anda yakin ingin menyimpan transaksi berikut ini?')) {
                // Submit the form using jQuery
                $('#transactionForm').submit();
            } else {
                // Do nothing!
                console.log('Transaksi tidak jadi disubmit.');
            }
        }
    });

    $('#product-select').change(function() {
        const selectedOption = $(this).find('option:selected');
        selectedProduct.id = $(this).val();
        selectedProduct.code = selectedOption.data('code');
        selectedProduct.name = selectedOption.data('name');
        selectedProduct.price = selectedOption.data('price');
        selectedProduct.uom = selectedOption.data('uom');
        $('#item_price_formatted').val(formatToCurrency(selectedProduct.price));
        $('#item_quantity_unit').val(selectedProduct.uom);
    });

    //set variable for tanggal transaksi
    document.getElementById("transaction_date").setAttribute('value', transaction_date);

    // $('#item_price_formatted').on('blur', function() {

    //     // check if value is valid numeric
    //     if($.isNumeric(this.value)){
    //         //pass original value for next calculation
    //         $('#item_price').val(this.value);

    //         this.value = formatToCurrency(this.value);
    //     } else {
    //         alert("Harga tidak valid, silahkan cek kembali!");
    //         $('#item_price_formatted').val("");
    //     }

    // });

    function formatToCurrency(val){        
        const value = val.toString().replace(/,/g, '');

        var final_value = parseFloat(value).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        });

        return final_value;
    }

    function calculateTotalPriceItem (total_price, disc_percent) {
        // var disc_amount = (total_price * disc_percent) / 100;
        var disc_amount = (disc_percent / 100) * total_price;
        console.log(disc_amount);
        return total_price - disc_amount;
    }

    function addItem(){
        const itemQuantity = document.getElementById("item_quantity").value;
        const discPercent = document.getElementById("disc_percent").value;

        if (selectedProduct.name && itemQuantity && selectedProduct.uom && selectedProduct.price) {
            const existingItemIndex = items.findIndex(item => item.product_id === selectedProduct.id);

            if (existingItemIndex !== -1) {
                // Jika item sudah ada, tambahkan quantity
                items[existingItemIndex].item_quantity = parseInt(items[existingItemIndex].item_quantity) + parseInt(itemQuantity);
                items[existingItemIndex].item_total_price = calculateTotalPriceItem(items[existingItemIndex].item_quantity * selectedProduct.price, discPercent);
            } else {
                // Jika item belum ada, tambahkan item baru ke array
                const item = {
                    product_id: selectedProduct.id,
                    item_code: selectedProduct.code,
                    item_name: selectedProduct.name,
                    item_quantity: itemQuantity,
                    item_quantity_unit: selectedProduct.uom,
                    disc_percent: discPercent,
                    item_price: selectedProduct.price,
                    item_total_price: calculateTotalPriceItem(itemQuantity * selectedProduct.price, discPercent)
                };
                items.push(item);
            }

            console.log(items);
            refreshTable();
            resetFormFields();
        } else {
            alert('Mohon isi semua kolom dengan tanda ( * ) terlebih dahulu');
        }
    }

    function refreshTable(type = null, index = null){
        //get table body of list item:
        var tableRef = document.getElementById('items_table').getElementsByTagName('tbody')[0];
        
        // Clear existing content of tbody
        tableRef.innerHTML = '';

        //unset value for items
        $('#itemsArr').val('');
        // Flatten the multidimensional array into a string representation
        var encripted_items = JSON.stringify(items);
        // console.log(items);
        // console.log(encripted_items);
        $('#itemsArr').val(encripted_items);
        console.log($('#itemsArr').val());

        var subtotal_price = 0;
        var total_disc_percent = 0;
        var final_total_price = 0;
        var final_item_qty = 0;
        
        for (let index = 0; index < items.length; index++){
           
            var formattedPrice = formatToCurrency(items[index]['item_price'].toString());
            var formattedTotalPrice = formatToCurrency(items[index]['item_total_price'].toString());

            // Define a unique ID for the th element
            const thId = 'th_' + (index + 1);

            //insert Row
            tableRef.insertRow().innerHTML = 
            "<th id='" + thId + "' scope='row'>" + (index + 1).toString()+ "</th>" + 
            "<td>" +items[index]['item_code']+ "</td>"+
            "<td>" +items[index]['item_name']+ "</td>"+
            "<td>" +items[index]['item_quantity']+" "+items[index]['item_quantity_unit']+ "</td>"+
            "<td>" +items[index]['disc_percent']+ " </td>"+
            "<td>" +formattedPrice+ "</td>"+
            "<td>" +formattedTotalPrice+ "</td>"+
            "<td><a class='btn btn-danger' data-index='"+index+"' onclick='deleteItem(this)'><i class='fa fa-trash'></i></a></td>";

            subtotal_price += (items[index]['item_price'] * items[index]['item_quantity']);
            total_disc_percent += parseInt(items[index]['disc_percent']);
            final_total_price += items[index]['item_total_price'];
            final_item_qty += parseInt(items[index]['item_quantity']);

            const discPercent = items[index]['disc_percent'];

            if (discPercent !== null && discPercent !== '0' && discPercent !== 0) {
                console.log(discPercent);
                total_disc_amount = ((items[index]['item_price'] * items[index]['item_quantity']) * total_disc_percent) / 100;
            }
        }

        $('#total_disc_percent').text(formatToCurrency(total_disc_percent.toString())+"%");
        $('#total_disc_amount').text(formatToCurrency(total_disc_amount.toString()));
        $('#sub_total_price').text(formatToCurrency(subtotal_price.toString()));
        $('#total_price').text(formatToCurrency(final_total_price.toString()));
        $('#total_items').text(formatToCurrency(final_item_qty.toString()));
    }

    function deleteItem(data){
        if (confirm('Apakah anda yakin ingin menghapus item ini?')) {
            // Delete it!
            var index = parseInt(data.getAttribute('data-index'));
            // Remove item at the specified index
            removed_item = items[index];
            items.splice(index, 1);
            refreshTable();
            alert("Item " + removed_item['item_name'] + " telah dihapus.");
        } else {
            // Do nothing!
            console.log('Item tidak jadi dihapus.');
        }
    }

  </script>

@endsection