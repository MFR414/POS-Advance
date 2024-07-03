@extends('layouts.master')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="main-container">
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
                    <h2>Daftar Stock</h2>
                </div>
                <div class="tambah-admin">
                    <a class="btn btn-primary btn-md" href="{{ route('application.stocks.create' )}}">
                        <i class="fa fa-plus">
                        </i>
                        Tambah stok
                    </a>
                </div>
            </div>
        </section>
        <br>
        <section class="section">
            <div class="card">
                <div class="card-body" style="padding: 5px 10px;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="margin-bottom: 0px !important;">
                        <li class="breadcrumb-item"><a href="{{ route('application.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar stok</li>
                    </ol>
                </nav>
                </div>
            </div>
        </section>
        <br>
        <section class="section">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title-body">
                                <h3 class="title">Cari stock</h3>
                            </div>
                            <form action="{{ route('application.stocks.index') }}" method="GET" style="margin-bottom: 0">
                                <section>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group" style='margin-bottom: 0'>
                                                <label class="control-label">Kode Produk</label>
                                                <input type="text" class="form-control boxed" name="code" value="{{ $search_terms['code'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group" style='margin-bottom: 0'>
                                                <label class="control-label">Nama Produk</label>
                                                <input type="text" class="form-control boxed" name="name" value="{{ $search_terms['name'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <button class="btn btn-primary" style="width: 100%;margin-top: 24px; height: 38px">Cari stok</button>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-striped table-responsive center" style="overflow-x: auto;">
                    <thead>
                        <tr>
                            <th style="width: 1%">
                                No
                            </th>
                            <th>
                                Tipe stok
                            </th>
                            <th>
                                Kode Produk
                            </th>
                            <th>
                                Nama Produk
                            </th>
                            <th>
                                Jumlah Stok Awal
                            </th>
                            <th>
                                Jumlah Stok Akhir
                            </th>
                            <th>
                                Jumlah Stok Masuk / Keluar
                            </th>
                            <th style="width: 20%">
                                Opsi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($stockCards) <= 0)
                            <tr>
                                <td colspan="6">Tidak ada data stock</td>
                            </tr>
                        @else
                            @foreach( $stockCards as $index => $stockCard )
                            <tr>
                                <td>
                                    {{$stockCards->firstItem() + $index}}
                                </td>
                                <td>
                                    @if($stockCard->type == 'pengeluaran')
                                        <span class="badge badge-danger">Pengeluaran</span>
                                    @else    
                                        <span class="badge badge-success">Pemasukan</span>
                                    @endif
                                </td>
                                <td>
                                    {{$stockCard->product->code ?? 'N/A'}}
                                </td>
                                <td>
                                    {{$stockCard->product->name ?? 'N/A'}}
                                </td>
                                <td>
                                    {{$stockCard->stock_before ?? 0}} {{$stockCard->uom}}
                                </td>
                                <td>
                                    {{$stockCard->stock->quantity ?? 0}} {{$stockCard->stock->uom}}
                                </td>
                                <td>
                                    {{$stockCard->quantity ?? 0}} {{$stockCard->uom}}
                                </td>
                                <td class="project-actions text-right" style="display: flex; gap:5px;">
                                    {{-- <a class="btn btn-primary btn-sm" style="padding-top: 8px;" href="{{ route('application.stocks.edit', $product)}}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('application.stocks.destroy', $product) }}">
                                        @csrf
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-danger show_confirm" title='Delete'> <i class="fas fa-trash"> </i> Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    {{-- Render Pagination Links --}}  
                    @if ($stockCards->hasPages())
                        {{-- Previous Page Link --}}
                        @if ($stockCards->onFirstPage())
                            <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $stockCards->previousPageUrl() }}">«</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($stockCards->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $stockCards->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($stockCards->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $stockCards->nextPageUrl() }}">»</a></li>
                        @else
                            <li class="page-item disabled"><a class="page-link" href="#">»</a></li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!-- /.card -->

  </section>

  <script>
    $('.show_confirm').click(function(e) {
        if(confirm("Apakah anda yakin ingin menghapus data admin ini?")) {
            document.getElementById('deleteForm').submit();
        }
    });
  </script>

@endsection
