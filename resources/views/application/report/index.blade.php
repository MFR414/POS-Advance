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
                        <li class="breadcrumb-item active" aria-current="page">Laporan</li>
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
                                <h3 class="title">Cari transaksi</h3>
                            </div>
                            <section>
                                <form action="{{ route('application.reports.index') }}" method="GET" style="margin-bottom: 0">
                                    <div class="row">
                                        {{-- <div class="col-sm-12 col-md-3">
                                            <div class="form-group" style='margin-bottom: 0'>
                                                <label class="control-label" for="transaction_date">Tanggal transaksi</label>
                                                <input type="date" class="form-control boxed" name="transaction_date" id="transaction_date" value="{{ $search_terms['transaction_date'] }}">
                                            </div>
                                        </div> --}}
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group" style='margin-bottom: 0'>
                                                <label class="control-label">Bulan</label>
                                                <select class="form-control boxed" name="month" id="month">
                                                    <option value="">Pilih Bulan</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}" {{ $search_terms['transaction_month'] == $i ? 'selected' : '' }}>
                                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group" style='margin-bottom: 0'>
                                                <label class="control-label">Tahun</label>
                                                <select class="form-control boxed" name="year" id="year">
                                                    <option value="">Pilih Tahun</option>
                                                    @for ($i = 2021; $i <= 2150; $i++)
                                                        <option value="{{ $i }}" {{ $search_terms['transaction_year'] == $i ? 'selected' : '' }}>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>                              
                                        <div class="col-sm-12 col-md-3">
                                            <button type="submit" class="btn btn-primary" style="width: 100%;margin-top: 24px; height: 38px">Cari Transaksi</button>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                        <!-- Export to PDF button with dynamic URL -->
                                        <button type="button" id="exportPdfBtn" class="btn btn-success" style="width: 100%;margin-top: 24px; height: 38px">
                                            Export to PDF
                                        </button>
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
                                Nomor Transaksi
                            </th>
                            <th>
                                Tanggal Transaksi
                            </th>
                            <th>
                                Nama Pelanggan
                            </th>
                            <th>
                                Status Pembayaran
                            </th>
                            <th>
                                Status Pembuatan Invoice
                            </th>
                            <th style="width: 20%">
                                Opsi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($transactions) <= 0)
                            <tr>
                                <td colspan="6">Tidak ada transaksi</td>
                            </tr>
                        @else
                            @foreach( $transactions as $index => $transaction )
                            <tr>
                                <td>
                                    {{$transactions->firstItem() + $index}}
                                </td>
                                <td>
                                    {{$transaction->transaction_number}}
                                </td>
                                <td>
                                    {{$transaction->formatted_transaction_date}}
                                </td>
                                <td>
                                    {{$transaction->customer_name}}
                                </td>
                                <td>
                                    @if($transaction->transaction_status == "Belum Dibayar")
                                        <span class="badge badge-danger">Belum Dibayar</span>
                                    @else    
                                        <span class="badge badge-success">Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if(empty($transaction->invoice_filename))
                                        <span class="badge badge-danger">Belum Dibuat</span>
                                    @else    
                                        <span class="badge badge-success">Sudah Dibuat</span>
                                    @endif
                                </td>
                                <td class="project-actions text-right" style="display: flex; gap:5px;">
                                    <a class="btn btn-primary btn-sm" style="padding-top: 8px;" href="{{ route('application.transactions.show', $transaction)}}">
                                        <i class="fas fa-receipt"></i> Detail Transaksi
                                    </a>
                                    @if(!empty($transaction->invoice_filename))
                                        <a class="btn btn-success btn-sm" style="padding-top: 8px;" href="{{ route('application.invoices.download', $transaction)}}">
                                            <i class="fas fa-print"></i> Download Invoice
                                        </a>
                                    @endif
                                    {{-- <form method="POST" action="{{ route('application.users.admins.destroy', $transaction) }}">
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
                    @if ($transactions->hasPages())
                        {{-- Previous Page Link --}}
                        @if ($transactions->onFirstPage())
                            <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $transactions->previousPageUrl() }}">«</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($transactions->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled"><a class="page-link" href="#">{{ $element }}</a></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $transactions->currentPage())
                                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($transactions->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $transactions->nextPageUrl() }}">»</a></li>
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
            var month = $('#month').val();
            var year = $('#year').val();

            // Construct the export URL with the selected values
            var exportUrl = "{{ route('application.reports.export') }}?month=" + month + "&year=" + year;

            // Redirect to the export URL
            window.location.href = exportUrl;
        });
    });
  </script>

@endsection
