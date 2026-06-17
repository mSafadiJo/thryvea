@extends('layouts.adminapp')

@section('content')

{{--    <div class="row m-b-15">--}}
{{--        <div class="col-md-12 grid-margin">--}}
{{--            <div class="card" style="border-radius: 10px;">--}}
{{--                <div class="card-body report-daily">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="d-sm-flex align-items-baseline report-summary-header">--}}
{{--                                <h5 class="font-weight-semibold">Daily Report Summary</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row report-inner-cards-wrapper">--}}
{{--                        <div class=" col-md -6 col-xl report-inner-card">--}}
{{--                            <div class="inner-card-text">--}}
{{--                                <span class="report-title">Total Leads</span>--}}
{{--                                <h4>{{$totalLeads}}</h4>--}}
{{--                            </div>--}}
{{--                            <div class="inner-card-icon bg-warning ">--}}
{{--                                <i class="fa fa-users" aria-hidden="true"></i>--}}

{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6 col-xl report-inner-card">--}}
{{--                            <div class="inner-card-text">--}}
{{--                                <span class="report-title">Sold Lead</span>--}}
{{--                                <h4>{{$soldLeads}}</h4>--}}
{{--                            </div>--}}
{{--                            <div class="inner-card-icon bg-success">--}}
{{--                                <i class="fa fa-smile-o" aria-hidden="true"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6 col-xl report-inner-card">--}}
{{--                            <div class="inner-card-text">--}}
{{--                                <span class="report-title">UnSold Lead</span>--}}
{{--                                <h4>{{$unsoldLeads}}</h4>--}}
{{--                            </div>--}}
{{--                            <div class="inner-card-icon bg-danger">--}}
{{--                                <i class="fa fa-frown-o" aria-hidden="true"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-md-6 col-xl report-inner-card">--}}
{{--                            <div class="inner-card-text">--}}
{{--                                <span class="report-title">Profit</span>--}}
{{--                                <h4>{{$profitToday}}</h4>--}}
{{--                            </div>--}}
{{--                            <div class="inner-card-icon bg-primary">--}}
{{--                                <i class="fa fa-money" aria-hidden="true"></i>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



{{--    --}}{{--charts--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}

{{--    <div class="row m-b-15">--}}
{{--        <div class=" col-md-6 grid-margin stretch-card ">--}}
{{--            <div class="card charts-home">--}}
{{--                <div class="card-body">--}}
{{--                    <h6 class="card-title" style="text-align: center;">Profit this month</h6>--}}

{{--                    <!-- Circular Rings -->--}}
{{--                    <div class="rings-container">--}}

{{--                        <!-- Profit This Month Ring -->--}}
{{--                        <div class="ring-wrapper">--}}
{{--                            <svg class="ring-svg" viewBox="0 0 140 140">--}}
{{--                                <circle class="ring-bg" cx="70" cy="70" r="58"/>--}}
{{--                                <circle class="ring-progress" cx="70" cy="70" r="58"--}}
{{--                                        stroke="#a855f7"--}}
{{--                                        stroke-dasharray="364.4"--}}
{{--                                        stroke-dashoffset="{{ $profitOffsetMonth }}"/>--}}
{{--                            </svg>--}}
{{--                            <div class="ring-content">--}}
{{--                                <div class="ring-value">${{ number_format($profitMonth) }}</div>--}}
{{--                                <div class="ring-label">Profit This Month</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <!-- Margin Ring -->--}}
{{--                        <div class="ring-wrapper">--}}
{{--                            <svg class="ring-svg" viewBox="0 0 140 140">--}}
{{--                                <circle class="ring-bg" cx="70" cy="70" r="58"/>--}}
{{--                                <circle class="ring-progress" cx="70" cy="70" r="58"--}}
{{--                                        stroke="#1bdbe0"--}}
{{--                                        stroke-dasharray="364.4"--}}
{{--                                        stroke-dashoffset="{{ 364.4 - (364.4 * $marginPercentMonth / 100) }}"/>--}}
{{--                            </svg>--}}
{{--                            <div class="ring-content">--}}
{{--                                <div class="ring-value">{{ number_format($marginPercentMonth) }}%</div>--}}
{{--                                <div class="ring-label">Margin</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Stats List -->--}}
{{--                    <ul class="stats-list">--}}
{{--                        <li class="stats-item">--}}
{{--                            <div class="stats-icon gray">--}}
{{--                                <i class="fa fa-money" aria-hidden="true"></i>--}}
{{--                            </div>--}}
{{--                            <div class="stats-info">--}}
{{--                                <div class="stats-title">Month's Purchasing Price</div>--}}
{{--                                <div class="stats-value">${{ number_format($monthPurchasingPrice) }}</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="stats-item">--}}
{{--                            <div class="stats-icon gray">--}}
{{--                                <i class="fa fa-line-chart" aria-hidden="true"></i>--}}
{{--                            </div>--}}
{{--                            <div class="stats-info">--}}
{{--                                <div class="stats-title">Month's Selling Price</div>--}}
{{--                                <div class="stats-value">${{ number_format($monthSellingPrice) }}</div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Monthly Leads Chart (Line Chart) -->--}}
{{--    <div class="row mb-4">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card charts-line">--}}
{{--                <div class="card-header">Monthly Leads - Sold vs Unsold ({{ now()->year }})</div>--}}
{{--                <div class="card-body">--}}
{{--                    <canvas id="leadsChart" height="100"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Monthly Profit Chart (Point Styling) -->--}}
{{--    <div class="row mb-4">--}}
{{--        <div class="col-12">--}}
{{--            <div class="card charts-line">--}}
{{--                <div class="card-header">Monthly Profit ({{ now()->year }})</div>--}}
{{--                <div class="card-body">--}}
{{--                    <canvas id="profitChart" height="100"></canvas>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}



{{--    <script>--}}
{{--        // Profit Chart - Point Styling--}}
{{--        const profitCtx = document.getElementById('profitChart').getContext('2d');--}}
{{--        new Chart(profitCtx, {--}}
{{--            type: 'line',--}}
{{--            data: {--}}
{{--                labels: {!! json_encode($months) !!},--}}
{{--                datasets: [{--}}
{{--                    label: 'Monthly Profit ($)',--}}
{{--                    data: {!! json_encode(array_values($profitData)) !!},--}}
{{--                    borderColor: '#00d4d4',--}}
{{--                    backgroundColor: 'rgba(0, 212, 212, 0.1)',--}}
{{--                    borderWidth: 3,--}}
{{--                    fill: true,--}}
{{--                    tension: 0.4,--}}
{{--                    // Point Styling--}}
{{--                    pointRadius: 6,--}}
{{--                    pointHoverRadius: 10,--}}
{{--                    pointBackgroundColor: '#00d4d4',--}}
{{--                    pointBorderColor: '#fff',--}}
{{--                    pointBorderWidth: 2,--}}
{{--                    pointStyle: 'circle',--}}
{{--                    pointHoverBackgroundColor: '#fff',--}}
{{--                    pointHoverBorderColor: '#00d4d4'--}}
{{--                }]--}}
{{--            },--}}
{{--            options: {--}}
{{--                responsive: true,--}}
{{--                plugins: {--}}
{{--                    legend: { display: true },--}}
{{--                    tooltip: {--}}
{{--                        callbacks: {--}}
{{--                            label: function(context) {--}}
{{--                                return '$' + context.parsed.y.toLocaleString();--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                },--}}
{{--                scales: {--}}
{{--                    y: {--}}
{{--                        beginAtZero: true,--}}
{{--                        ticks: {--}}
{{--                            callback: function(value) {--}}
{{--                                return '$' + value.toLocaleString();--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}

{{--        // Leads Chart - Line Chart--}}
{{--        const leadsCtx = document.getElementById('leadsChart').getContext('2d');--}}
{{--        new Chart(leadsCtx, {--}}
{{--            type: 'line',--}}
{{--            data: {--}}
{{--                labels: {!! json_encode($months) !!},--}}
{{--                datasets: [--}}
{{--                    {--}}
{{--                        label: 'Sold Leads',--}}
{{--                        data: {!! json_encode(array_values($soldLeadsData)) !!},--}}
{{--                        borderColor: '#28a745',--}}
{{--                        backgroundColor: 'rgba(40, 167, 69, 0.1)',--}}
{{--                        borderWidth: 3,--}}
{{--                        fill: true,--}}
{{--                        tension: 0.4,--}}
{{--                        pointRadius: 5,--}}
{{--                        pointBackgroundColor: '#28a745',--}}
{{--                        pointBorderColor: '#fff',--}}
{{--                        pointBorderWidth: 2--}}
{{--                    },--}}
{{--                    {--}}
{{--                        label: 'Unsold Leads',--}}
{{--                        data: {!! json_encode(array_values($unsoldLeadsData)) !!},--}}
{{--                        borderColor: '#dc3545',--}}
{{--                        backgroundColor: 'rgba(220, 53, 69, 0.1)',--}}
{{--                        borderWidth: 3,--}}
{{--                        fill: true,--}}
{{--                        tension: 0.4,--}}
{{--                        pointRadius: 5,--}}
{{--                        pointBackgroundColor: '#dc3545',--}}
{{--                        pointBorderColor: '#fff',--}}
{{--                        pointBorderWidth: 2--}}
{{--                    }--}}
{{--                ]--}}
{{--            },--}}
{{--            options: {--}}
{{--                responsive: true,--}}
{{--                interaction: {--}}
{{--                    mode: 'index',--}}
{{--                    intersect: false--}}
{{--                },--}}
{{--                plugins: {--}}
{{--                    legend: { display: true },--}}
{{--                    tooltip: {--}}
{{--                        callbacks: {--}}
{{--                            label: function(context) {--}}
{{--                                return context.dataset.label + ': ' + context.parsed.y;--}}
{{--                            }--}}
{{--                        }--}}
{{--                    }--}}
{{--                },--}}
{{--                scales: {--}}
{{--                    y: {--}}
{{--                        beginAtZero: true,--}}
{{--                        ticks: { stepSize: 1 }--}}
{{--                    }--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--    </script>--}}

@endsection
