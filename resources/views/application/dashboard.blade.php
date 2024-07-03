@extends('layouts.master')

@section('content')
   <!-- Main content -->
    <section class="content">
        <div class="main-container">
            <section>
                <div class="d-flex justify-content-start">
                    <div class="title">
                        <br>
                        <h3>Selamat Datang {{ auth()->user()->name}}</h3>
                    </div>
                </div>
            </section>
            <br>
            <section>   
                <div class="container-fluid">
                    @if( auth()->user()->hasAnyRole(['Admin','Super Admin','Owner User']))
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Jumlah Transaksi</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex" id="amount">
                                            <p class="d-flex flex-column">
                                                <span class="text-bold text-lg" id="total_transaction_alltime">0</span>
                                                <span>Jumlah Transaksi Keseluruhan</span>
                                            </p>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <canvas id="totalTransactionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Total Pemasukan</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex" id="amount">
                                            <p class="d-flex flex-column">
                                                <span class="text-bold text-lg" id="income_alltime"> Rp. 0</span>
                                                <span>Total Pemasukan Keseluruhan</span>
                                            </p>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <canvas id="incomeChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Barang Terlaris</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="mostSellingItemChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Jumlah Barang Terjual Keseluruhan</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="totalSelledItemChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Jumlah Transaksi Bulan Ini</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex" id="amount">
                                            <p class="d-flex flex-column">
                                                <span class="text-bold text-lg" id="total_transaction_monthly">0</span>
                                                <span>Jumlah Transaksi Keseluruhan Bulan Ini</span>
                                            </p>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <canvas id="totalTransactionMonthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="card">
                                    <div class="card-title p-3">
                                        <h4>Total Pemasukan Bulan Ini</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex" id="amount">
                                            <p class="d-flex flex-column">
                                                <span class="text-bold text-lg" id="income_monthly"> Rp. 0</span>
                                                <span>Total Pemasukan Keseluruhan Bulan Ini</span>
                                            </p>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <canvas id="incomeMonthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </section>
    <script>

        $(document).ready(function() {
            // get data from controller
            var data = {!! $data !!};
            var transaction = data.transaction;
            var income = data.income;

            // create options for transaction chart
            var line_chart_options = {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                        display: true,
                        text: 'Month'
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            };

             // create options for transaction chart
             var income_line_chart_options = {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                        display: true,
                        text: 'Date'
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                // Use Intl.NumberFormat for custom formatting
                                const formatter = new Intl.NumberFormat('id-ID', { style: 'decimal' });
                                return formatter.format(value);
                            }
                        },
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                // No further formatting needed as handled by callback in y-axis ticks
                                return value;
                            }
                        }
                    }
                }
            };

            function lineChartSet(data,options,chart_name){
                
                //-------------
                //- LINE CHART -
                //--------------
                var lineChartCanvas = $(chart_name).get(0).getContext('2d');
                var lineChart = new Chart(lineChartCanvas, {
                    type: 'line',
                    data: data,
                    options: options
                });
            };

            function pieChartSet(labels,data,chart_name){
                var pieChartCanvas = $(chart_name).get(0).getContext('2d');
                var pieChart = new Chart(pieChartCanvas, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                '#FF6384', // Solid red
                                '#36A2EB', // Solid blue
                                '#FFCE56', // Solid yellow
                                '#4BC0C0', // Solid teal
                                '#9966FF', // Solid purple
                                '#FF9F40', // Solid orange
                                '#FF6384', // Solid red
                                '#36A2EB', // Solid blue
                                '#FFCE56', // Solid yellow
                                '#4BC0C0', // Solid teal
                                // Add more colors if needed
                            ],
                            borderColor: '#FFFFFF', // White border for better visibility
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            };

            if(data.type == 'yearly') {

                var most_selling_items = data.most_selling_items;
                var total_selled_items = data.total_selled_items;

                // set some value to text field
                $('#total_transaction_alltime').text(transaction.count_all_time);
                $('#income_alltime').text('Rp. '+income.sum_all_time);

                // create dataset for transaction chart
                var total_transaction_chart_data = {
                    labels: transaction.labels,
                    datasets: [
                    {
                        label: 'Jumlah Transaksi Tahun '+transaction.year,
                        data: transaction.data,
                        borderColor: 'rgba(75, 192, 255, 1)',
                        tension: 0.1,
                        fill: false
                    },
                    // {
                    //     label: 'Dataset 2',
                    //     data: [28, 48, 40, 19, 86, 27, 90],
                    //     borderColor: 'rgba(153, 102, 255, 1)',
                    //     tension: 0.1,
                    //     fill: false
                    // }
                    ]
                };
                
                // create dataset for income chart
                var income_chart_data = {
                    labels: income.labels,
                    datasets: [
                    {
                        label: 'Jumlah Transaksi Tahun '+income.year,
                        data: income.data,
                        borderColor: 'rgba(75, 192, 255, 1)',
                        tension: 0.1,
                        fill: false
                    },
                    // {
                    //     label: 'Dataset 2',
                    //     data: [28, 48, 40, 19, 86, 27, 90],
                    //     borderColor: 'rgba(153, 102, 255, 1)',
                    //     tension: 0.1,
                    //     fill: false
                    // }
                    ]
                };

                lineChartSet(total_transaction_chart_data, line_chart_options,'#totalTransactionChart');
                lineChartSet(income_chart_data, income_line_chart_options,'#incomeChart');
                pieChartSet(most_selling_items.items_name,most_selling_items.items_count,'#mostSellingItemChart');
                pieChartSet(total_selled_items.items_name,total_selled_items.items_count,'#totalSelledItemChart');

            } else {

                // create dataset for monthly transaction chart
                var total_transaction_monthly_chart_data = {
                    labels: transaction.labels,
                    datasets: [
                    {
                        label: 'Jumlah Transaksi Bulan Ini',
                        data: transaction.data,
                        borderColor: 'rgba(75, 192, 255, 1)',
                        tension: 0.1,
                        fill: false
                    },
                    ]
                };
                
                // create dataset for income chart
                var income_monthly_chart_data = {
                    labels: income.labels,
                    datasets: [
                    {
                        label: 'Jumlah Transaksi Bulan Ini',
                        data: income.data,
                        borderColor: 'rgba(75, 192, 255, 1)',
                        tension: 0.1,
                        fill: false
                    },
                    ]
                };

                // set some value to text field
                $('#total_transaction_monthly').text(transaction.count_monthly);
                $('#income_monthly').text('Rp. '+income.sum_monthly);

                lineChartSet(total_transaction_monthly_chart_data, line_chart_options,'#totalTransactionMonthlyChart');
                lineChartSet(income_monthly_chart_data, income_line_chart_options,'#incomeMonthlyChart');
            }

        });
    </script>
@endsection