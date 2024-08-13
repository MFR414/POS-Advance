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
                    <h2>Laporan</h2>
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
                        <li class="breadcrumb-item" aria-current="page">Laporan</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('application.reports.stocks.index') }}">Stock</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('application.reports.stocks.index') }}">Histori Stock</a></li>
                    </ol>
                </nav>
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
                                Tanggal Pembuatan
                            </th>
                            <th>
                                Tipe Stock
                            </th>
                            <th>
                                Jumlah Stok
                            </th>
                            {{-- <th style="width: 20%">
                                Opsi
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($stockCards) <= 0)
                            <tr>
                                <td colspan="6">history tidak ditemukan</td>
                            </tr>
                        @else
                            @foreach( $stockCards as $index => $stockCard )
                            {{-- @php
                                dd($stockCard);
                            @endphp --}}
                            <tr>
                                <td>
                                    {{$stockCards->firstItem() + $index}}
                                </td>
                                <td>
                                    {{$stockCard->created_at}} 
                                </td>
                                <td>
                                    @if($stockCard->type == 'pengeluaran')
                                        <span class="badge badge-danger">Pengeluaran</span>
                                    @else
                                        <span class="badge badge-success">Pemasukan</span>
                                    @endif
                                </td>
                                <td>
                                    {{$stockCard->quantity}} {{$stockCard->uom}}
                                </td>
                                {{-- <td class="project-actions text-right" style="display: flex; gap:5px;">
                                    <a class="btn btn-primary btn-sm" style="padding-top: 8px;" href="{{ route('application.reports.stocks.show', $transaction)}}">
                                        <i class="fas fa-receipt"></i> Detail Transaksi
                                    </a>
                                </td> --}}
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

@endsection
