@extends('layouts.master')

@section('content')
<!-- Main content -->

<section class="content">
    @if(Session::has('success_message'))
        <div class="card card-success" style='margin-bottom: 20px'>
            <div class="card-header">
                <div class="header-body">
                    <p class="title" style='color: white; margin: 0;'>Success</p>
                </div>
            </div>
            <div class="card-body">
                {{ Session::get('success_message') }}
            </div>
        </div>
    @endif

    @if(Session::has('error_message'))
        <div class="card card-danger" style='margin-bottom: 20px'>
            <div class="card-header">
                <div class="header-body">
                    <p class="title" style='color: white; margin: 0;'>Error</p>
                </div>
            </div>
            <div class="card-body">
                {{ Session::get('error_message') }}
            </div>
        </div>
    @endif
    <br>
    <section>
        <div class="d-flex d-flex d-flex justify-content-between align-items-center p-2">
            <div class="title">
                <h2>Buat produk</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="card">
            <div class="card-body" style="padding: 5px 10px;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 0px !important;">
                    <li class="breadcrumb-item"><a href="{{ route('application.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('application.products.index') }}">Daftar produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah admin</li>
                </ol>
            </nav>
            </div>
        </div>
    </section>
    <br>
    <div class="main-container">
        <section class="section">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title-body">
                                <h3 class="title">Buat produk</h3>
                            </div>
                            <form action="{{ route('application.products.update',$product) }}" method="POST" style="margin-bottom: 0">
                                {{ csrf_field() }}
                                @method('PUT')
                                <section>
                                    <div class="row">
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Kode Produk <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control boxed" name="code" value="{{ old('code', $product->code) }}" placeholder="Masukkan Kode Produk">
                                                @error('code')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Nama Produk <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="name" value="{{ old('name', $product->name) }}" placeholder="Masukkan Nomor Telefon Admin">
                                                @error('name')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Harga <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="formatted_price" placeholder="Masukkan harga produk" id="formatted_price">
                                                <input type="hidden" class="form-control underlined" name="price" value="{{ old('price', $product->price) }}" id="price">
                                                @error('price')
                                                    <span class="has-error" id="price-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Satuan Item <sup style='color: red'>*</sup></label>
                                                <select class="form-control" name="uom" id="uom" required>
                                                    {{-- <option value=""> Pilih Salah Satu </option> --}}
                                                    {{-- <option value="Meter" {{ (old('uom') == "Meter") ? 'selected' : '' }}>Meter</option> --}}
                                                    <option value="Pcs" selected>Pcs</option>
                                                </select>
                                                @error('uom')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Deskripsi</label>
                                                <textarea type="text" class="form-control underlined" name="description" placeholder="Masukkan deskripsi produk">{{ old('description') }}</textarea>
                                                @error('description')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Status</label>
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="is_active" value="0">
                                                    <input class="form-check-input" name="is_active" type="checkbox" role="switch" id="flexSwitchCheckDefault" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="flexSwitchCheckDefault">Aktif</label>
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="col-sm-12 col-md-12 mt-3">
                                            <div class="form-group">
                                                <button class="btn btn-primary" style="width: 30%;">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </section>
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
    var temp_price = '{{ $product->price}}';
    $('#formatted_price').val(formatToCurrency(temp_price));
    

    $('#formatted_price').on('blur', function() {
        // check if value is valid numeric
        if($.isNumeric(this.value)){
            //pass original value for next calculation
            $('#price').val(this.value);

            this.value = formatToCurrency(this.value);
        } else {
            alert("Harga tidak valid, silahkan cek kembali!");
            $('#formatted_price').val("");
        }

    });

    function formatToCurrency(val){        
        const value = val.replace(/,/g, '');

        var final_value = parseFloat(value).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        });

        return final_value;
    }
  </script>

@endsection
