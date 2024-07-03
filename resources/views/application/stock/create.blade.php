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
                    <li class="breadcrumb-item"><a href="{{ route('application.stocks.index') }}">Daftar stok</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah stok</li>
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
                                <h3 class="title">Buat stok</h3>
                            </div>
                            <form action="{{ route('application.stocks.store') }}" method="POST" style="margin-bottom: 0">
                                {{ csrf_field() }}
                                <section>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Tipe Stok<sup style='color: red'>*</sup></label>
                                                <select class="form-control" name="type" id="type" required>
                                                    <option value="pemasukan">Pemasukan</option>
                                                    <option value="pengeluaran">Pengeluaran</option>
                                                </select>
                                                @error('type')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Produk<sup style='color: red'>*</sup></label>
                                                <select class="form-control" name="product_id" id="product_id" required>
                                                    <option value=""> Pilih Salah Satu </option>
                                                    @foreach ( $products as $product)
                                                        <option value="{{ $product->id }}" {{ (old('product_id') == $product->id) ? 'selected' : '' }}>{{ $product->code }} - {{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('product_id')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Jumlah</label>
                                                <input type="number" class="form-control underlined" name="quantity" value="{{ old('quantity') }}" placeholder="Masukkan jumlah stok produk">
                                                @error('quantity')
                                                    <span class="has-error">
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
    
  </script>

@endsection
