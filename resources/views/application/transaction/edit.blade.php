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
                    <li class="breadcrumb-item active" aria-current="page">Edit Transaksi</li>
                </ol>
            </nav>
            </div>
        </div>
    </section>
    <br>
    <div class="main-container">
        <div class="modal-container">
            {{-- modal --}}
            <div class="modal fade" id="ItemFormsModal" tabindex="-1" role="dialog" aria-labelledby="ItemFormsModalTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ItemFormsModalTitle">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <section>
                                        <input type="hidden" id="input_type" name="input_type">
                                        <input type="hidden" id="array_index" name="array_index">
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
                                                    <label class="control-label">Satuan Item <sup style='color: red'>*</sup></label>
                                                    <select class="form-control" name="item_quantity_unit" id="item_quantity_unit" required>
                                                        <option value="" selected> Pilih Salah Satu </option>
                                                        <option value="Meter">Meter</option>
                                                        <option value="Pcs">Pcs</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Harga Item Per Satuan<sup style='color: red'>*</sup></label>
                                                    <input type="text" class="form-control underlined" name="item_price_formatted" id="item_price_formatted" placeholder="Masukkan Harga item per satuan" required>
                                                    <input type="hidden" name="item_price" id="item_price">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-1">
                                                <div class="form-group">
                                                    <label class="control-label">Diskon (%)</label>
                                                    <input type="number" class="form-control underlined" name="disc_percent" id="disc_percent" value="0" placeholder="Masukkan Potongan Harga (%)">
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="saveItemBtnModal" onclick="addItem()">Simpan Item</button>
                            <button type="button" class="btn btn-primary" id="updateItemBtnModal" onclick="updateItem()">Update Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-12 d-flex">
                    <div class="col-12">
                            <form action="{{ route('application.transactions.update',$transaction) }}" method="POST" style="margin-bottom: 0" id="transactionForm">
                                {{ csrf_field() }}
                                @method('PUT')
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title-body">
                                            <h3 class="title">Data Transaksi</h3>
                                        </div>
                                        <section>
                                            <input type="hidden" name="items" id="itemsArr">
                                            <input type="hidden" name="deleted_items" id="deletedItemsArr">
                                            <input type="hidden" name="dp_po">
                                            <div class="row">
                                                <div class="col-12 mb-1">
                                                    <div class="form-group">
                                                        <label class="control-label">Nomor Transaksi</label>
                                                        <input type="text" class="form-control boxed" name="transaction_number" id="transaction_number" value="{{  old('transaction_number',$transaction->transaction_number) }}" placeholder="Masukkan Kode Sales Admin" readonly="readonly">
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
                                                        <input type="date" class="form-control boxed" id='transaction_date' name="transaction_date" id="transaction_date" value="{{ old('transaction_date',$transaction->formatted_transaction_date_input) }}" readonly="readonly">
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
                                                        <input type="text" class="form-control boxed" name="sales_code" value="{{ old('sales_code', $transaction->sales_code) }}" placeholder="Masukkan Kode Sales Admin">
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
                                                        <input type="text" class="form-control underlined" id="customer_name" name="customer_name" value="{{ old('customer_name',$transaction->customer_name) }}" placeholder="Masukkan Nama Pelanggan" required>
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
                                                        <input type="text" class="form-control underlined" name="customer_address" value="{{ old('customer_address',$transaction->customer_address) }}" placeholder="Masukkan Alamat Pelanggan">
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
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title-body">
                                <div class="head-container d-flex justify-content-between mb-1">
                                    <h3 class="title">List Item</h3>
                                    <a class="btn btn-primary add-item-btn" data-toggle="modal" data-target="#ItemFormsModal" >Tambah Item</a>
                                </div>
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
                                                Potongan (%)
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
                                            <td colspan="2" style="text-align: right !important;">Potongan</td>
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
    var transaction_date = "<?php echo"$transaction->formatted_transaction_date_input"?>"; 
    var items = new Array();
    var deleted_items = new Array();
    var total_disc_amount = 0;
    let selectedProduct = {};

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
        return total_price - disc_amount;
    }

    function addItem(){
        item = createItemObject();

        if(item !== null){
            //push created item to items array
            items.push(item);
            console.log(items);

            //refresh item table list
            refreshTable();
    
            closeModal();
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

        $('#itemsArr').val(encripted_items);

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
            "<td><a class='btn btn-danger' data-index='"+index+"' onclick='deleteItem(this)'><i class='fa fa-trash'></i></a></td>"+
            "<td><a class='btn btn-warning edit-item-btn' data-index='"+index+"' data-toggle='modal' data-target='#ItemFormsModal'><i class='fas fa-edit'></i></a></td>";

            subtotal_price += (items[index]['item_price'] * items[index]['item_quantity']);
            total_disc_percent += parseInt(items[index]['disc_percent']);
            final_total_price += items[index]['item_total_price'];
            final_item_qty += parseInt(items[index]['item_quantity']);

            const discPercent = items[index]['disc_percent'];

            if (discPercent !== null && discPercent !== '0' && discPercent !== 0) {
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

            removed_item = items[index];
            if(removed_item['id'] !== null){
                deleted_items.push(removed_item['id']);
                $('#deletedItemsArr').val(JSON.stringify(deleted_items));
            }

            // Remove item at the specified index
            items.splice(index, 1);

            //refresh the table items list
            refreshTable();
            alert("Item " + removed_item['item_name'] + " telah dihapus.");
        } else {
            // Do nothing!
            console.log('Item tidak jadi dihapus.');
        }
    }

    function prepareModal(fromButton, params_index = null){
        if(fromButton == 'editItemBtn' && params_index !== null){
            $('#ItemFormsModalTitle').text("Edit Item");
            $("#saveItemBtnModal").hide();
            $("#updateItemBtnModal").show();
            $('#input_type').val("edit");
            $('#product-select').val(items[params_index]['product_id']);
            $('#product-select').trigger('change');
            // $('#item_code').val(items[params_index]['item_code']);
            // $('#item_name').val(items[params_index]['item_name']);
            $('#item_quantity').val(items[params_index]['item_quantity']);
            $('#item_quantity_unit').val(items[params_index]['item_quantity_unit']);
            $('#item_price_formatted').val(formatToCurrency(items[params_index]['item_price'].toString()));
            $('#item_price').val(items[params_index]['item_price']);
            $('#disc_percent').val(items[params_index]['disc_percent']);
        } else {
            $('#ItemFormsModalTitle').text("Tambah Item");
            $('#input_type').val("add");
            $("#updateItemBtnModal").hide();
            $("#saveItemBtnModal").show();
            $('#product-select').val("");
            // $('#item_code').val("");
            // $('#item_name').val("");
            $('#item_quantity').val("");
            $('#item_quantity_unit').val("");
            $('#item_price_formatted').val("");
            $('#item_price').val(0);
            $('#disc_percent').val(0);
        }
    }

    function closeModal(){
        //hide modal
        $('#ItemFormsModal').modal('hide');
    
        //reset input form fields
        $('#input_type').val("");
        $('#array_index').val("");
        $('#item_code').val("");
        $('#item_name').val("");
        $('#item_quantity').val("");
        $('#item_quantity_unit').val("");
        $('#item_price_formatted').val("");
        $('#item_price').val(0);
        $('#disc_percent').val(0);
    }


    function createItemObject(){
        var product_id = selectedProduct.id;
        var item_code = selectedProduct.code;
        var item_name = selectedProduct.name;
        var item_quantity = document.getElementById("item_quantity").value;
        var item_quantity_unit = selectedProduct.uom;
        var disc_percent = document.getElementById("disc_percent").value;
        var item_price = selectedProduct.price;
        var actual_price = item_price;

        if( item_name !== "" && item_quantity !== "" && item_quantity_unit !== "" && item_price !== ""){
            var existingItemIndex = items.findIndex(item => item.item_code === item_code);

            if(existingItemIndex !== -1){
                // Item already exists, update the quantity
                items[existingItemIndex].item_quantity = parseInt(items[existingItemIndex].item_quantity) + parseInt(item_quantity);
                items[existingItemIndex].item_total_price = calculateTotalPriceItem(items[existingItemIndex].item_quantity * items[existingItemIndex].item_price, items[existingItemIndex].disc_percent);
                console.log("Item quantity updated successfully");

                // Return the index of the existing item
                return items[existingItemIndex];
            } else {
                var item = new Object();
                item.product_id = product_id;
                item.item_code = item_code;
                item.item_name = item_name;
                item.item_quantity = item_quantity;
                item.item_quantity_unit = item_quantity_unit;
                item.item_price = item_price;
                item.disc_percent = disc_percent;
                item.item_total_price = calculateTotalPriceItem(item_quantity * item_price, disc_percent);
                console.log(item.product_id);
            }

            return item;
        } else {
            return null;
        }
    }

    function updateItem(){

        // get the data from input fields to array
        selected_index = $('#array_index').val();
        updated_data = createItemObject();

        if(updated_data !== null){
            if (selected_index >= 0 && selected_index < items.length) {
                let item = items[selected_index];
                Object.keys(item).forEach(key => {
                    if (updated_data.hasOwnProperty(key)) {
                        item[key] = updated_data[key];
                    }
                });
                console.log("Item updated successfully");
    
                // refresh the table
                refreshTable();
                
                closeModal();
            } else {
                console.log("Index out of bounds");
                alert("item gagal di update, mohon ulangi kembali dalam beberapa saat!");
            }
        } else {
            alert('Mohon isi semua kolom dengan tanda ( * ) terlebih dahulu');
        }
    }

    $(document).ready(function() {
        //set value for nomor transaksi
        $('#transation_number').val("{{$transaction->transaction_number}}");
         //set value for tanggal transaksi
        $('#transaction_date').val("{{$transaction->formatted_transaction_date_input}}");

        // retreive data from backend to javascript object
        const phpObj = '<?php echo $items; ?>';

        // Parse JSON string to JavaScript object
        itemsObj = JSON.parse(phpObj);

        // convert object to array
        itemsObj.forEach( item => {
            items.push(item);
        })

        //refresh table
        refreshTable();

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

        $('#item_price_formatted').on('blur', function() {

            // check if value is valid numeric
            if($.isNumeric(this.value)){
                //pass original value for next calculation
                $('#item_price').val(this.value);

                this.value = formatToCurrency(this.value);
            } else {
                alert("Harga tidak valid, silahkan cek kembali!");
                $('#item_price_formatted').val("");
            }

        });

        $('#product-select').change(function() {
            const selectedOption = $(this).find('option:selected');
            
            if (selectedOption.length > 0) {
                selectedProduct.id = selectedOption.val();
                selectedProduct.code = selectedOption.data('code');
                selectedProduct.name = selectedOption.data('name');
                selectedProduct.price = selectedOption.data('price');
                selectedProduct.uom = selectedOption.data('uom');

                $('#item_price_formatted').val(formatToCurrency(selectedProduct.price));
                $('#item_quantity_unit').val(selectedProduct.uom);
            } else {
                console.error("No selected option found");
            }
        });


        $(document).on('click', '.add-item-btn',function() {
            prepareModal('addItemBtn');
        });

        $(document).on('click', '.edit-item-btn', function() {

            // Get the value of the 'data-index' attribute of the clicked button
            var dataIndex = $(this).data('index');
            $('#array_index').val(dataIndex);

            // Pass both the button ID and the dataIndex to the prepareModal function
            prepareModal('editItemBtn', dataIndex);
        });
    });

  </script>

@endsection