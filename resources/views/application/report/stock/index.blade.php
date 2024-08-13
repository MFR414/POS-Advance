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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('application.reports.stocks.index') }}">Stok</a></li>
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
                                <h3 class="title">Cari Stock</h3>
                            </div>
                            <section>
                                <form action="{{ route('application.reports.stocks.index') }}" method="GET" style="margin-bottom: 0">
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
                                            <button type="submit" class="btn btn-primary" style="width: 100%;margin-top: 24px; height: 38px">Cari Stock</button>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <!-- Export to PDF button with dynamic URL -->
                                            <button type="button" id="exportPdfBtn" class="btn btn-success" style="width: 100%;margin-top: 24px; height: 38px">
                                                Export to PDF
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </section>
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
                                Produk
                            </th>
                            <th>
                                Jumlah Stok
                            </th>
                            <th style="width: 20%">
                                Opsi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products) <= 0)
                            <tr>
                                <td colspan="6">produk tidak ditemukan</td>
                            </tr>
                        @else
                            @foreach( $products as $index => $product )
                            <tr>
                                <td>
                                    {{$products->firstItem() + $index}}
                                </td>
                                <td>
                                    {{$product->code}} - {{$product->name}} 
                                </td>
                                <td>
                                    @if(!empty($product->stock))
                                        {{$product->stock->quantity}} {{$product->stock->uom}}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="project-actions text-right" style="display: flex; gap:5px;">
                                    <a class="btn btn-primary btn-sm" style="padding-top: 8px;" href="{{ route('application.reports.stocks.show', $product->id)}}">
                                        <i class="fas fa-receipt"></i> Log Histori Stok
                                    </a>
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
                    @if ($products->hasPages())
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">«</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $products->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">»</a></li>
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
    $(document).ready(function() {
        $('#exportPdfBtn').on('click', function() {
            // Get the selected month and year values
            var code = $('#code').val();
            var name = $('#name').val();

            // Construct the export URL with the selected values
            var exportUrl = "{{ route('application.reports.stocks.export') }}?code=" + code + "&name=" + name;

            // Redirect to the export URL
            window.location.href = exportUrl;
        });
    });
  </script>

@endsection
