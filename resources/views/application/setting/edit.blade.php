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
                <h2>Pengaturan</h2>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="card">
            <div class="card-body" style="padding: 5px 10px;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="margin-bottom: 0px !important;">
                    <li class="breadcrumb-item"><a href="{{ route('application.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
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
                                <h3 class="title">Pengaturan</h3>
                            </div>
                            <form action="{{ route('application.settings.update') }}" method="POST" style="margin-bottom: 0">
                                {{ csrf_field() }}
                                @method('PUT')
                                <section>
                                    <div class="row">
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Nama Toko <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control boxed" name="store_name" value="{{ old('store_name', $setting->store_name ?? '') }}" placeholder="Masukkan Nama Toko">
                                                @error('store_name')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Alamat Toko <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="store_address" value="{{ old('store_address', $setting->store_address ?? '') }}" placeholder="Masukkan Nomor Telefon Admin">
                                                @error('store_address')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Nomor Telefon 1 <sup style='color: red'>*</sup></label>
                                                <input type="text" class="form-control underlined" name="store_phone_number_one" value="{{ old('store_phone_number_one', $setting->store_phone_number_one ?? '') }}" placeholder="Masukkan nomor telefon 1" id="store_phone_number_one">
                                                @error('store_phone_number_one')
                                                    <span class="has-error">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12 mb-1">
                                            <div class="form-group">
                                                <label class="control-label">Nomor Telefon 2</label>
                                                <input type="text" class="form-control underlined" name="store_phone_number_two" value="{{ old('store_phone_number_two', $setting->store_phone_number_two ?? '') }}" placeholder="Masukkan nomor telefon 2" id="store_phone_number_two">
                                                @error('store_phone_number_two')
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
