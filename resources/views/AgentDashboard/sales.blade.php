<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @include('include.include_css')
    <link href="{{ asset('css/sales_transfers_dashboard.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>{{ config('app.name', 'Laravel') }}</h2>
                </div>
            </div>
        </div>
    </nav>
    @include('include.include_js')
    <main class="py-12">
        <div class="container-fluid">

            <h3>Sales</h3>
            <div class="row">
                <div class="col-sm-9">
                    <div id="sales_transfers_dashboard_div_1">
                        @foreach($sales as $val)
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="custom-name-progress">{{ $val->username }}</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="main-progress">
                                                @php
                                                    $data_perc = round(($val->transfer_number / $daly_sales_max_transfer) * 100);
                                                    if( $data_perc > 95 ){
                                                        $data_perc = 95;
                                                    }
                                                    if( $data_perc < 0 ){
                                                        $data_perc = 0;
                                                    }
                                                @endphp
                                                @if( $data_perc == 0 )
                                                    @if(empty($val->adminIcon1))
                                                        <img src="{{ asset('/images/salesDashboard/man-user.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon1) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @elseif( $data_perc == 95 )
                                                    @if(empty($val->adminIcon3))
                                                        <img src="{{ asset('/images/salesDashboard/break-dance.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon3) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @else
                                                    @if(empty($val->adminIcon2))
                                                        <img src="{{ asset('/images/salesDashboard/man-walking-directions-button.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon2) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="custom-table-progress">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col"># of sales</th>
                            </tr>
                            </thead>
                            <tbody id="sales_transfers_dashboard_body_1">
                                @foreach($sales as $val)
                                    <tr>
                                        <td scope="row">{{ $val->id }}</td>
                                        <td>{{ $val->username }}</td>
                                        @php
                                        $color = "Red";
                                        if( !empty($val->transfer_number) ){
                                            if( $val->transfer_number >= $sales_target ){
                                                $color = "Green";
                                            }
                                        }
                                        @endphp
                                        <td><span class="custom-sales-number" style="color: {{ $color }}">{{ ( !empty($val->transfer_number) ? $val->transfer_number : 0 ) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr>

            <h3>SDR's</h3>
            <div class="row">
                <div class="col-sm-9">
                    <div id="sales_transfers_dashboard_div_2">
                        @foreach($sdrs as $val)
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="custom-name-progress">{{ $val->username }}</div>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="main-progress">
                                                @php
                                                    $data_perc = round(($val->transfer_number / $daly_sdr_max_transfer) * 100);
                                                    if( $data_perc > 95 ){
                                                        $data_perc = 95;
                                                    }
                                                    if( $data_perc < 0 ){
                                                        $data_perc = 0;
                                                    }
                                                @endphp
                                                @if( $data_perc == 0 )
                                                    @if(empty($val->adminIcon1))
                                                        <img src="{{ asset('/images/salesDashboard/man-user.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon1) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @elseif( $data_perc == 95 )
                                                    @if(empty($val->adminIcon3))
                                                        <img src="{{ asset('/images/salesDashboard/break-dance.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon3) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @else
                                                    @if(empty($val->adminIcon2))
                                                        <img src="{{ asset('/images/salesDashboard/man-walking-directions-button.png') }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @else
                                                        <img src="{{ asset('/images/salesDashboard/'.$val->adminIcon2) }}" class="img-sales-custom" style="left: {{ $data_perc }}%;">
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="custom-table-progress">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col"># of Transfers</th>
                                </tr>
                            </thead>
                            <tbody id="sales_transfers_dashboard_body_2">
                                @foreach($sdrs as $val)
                                    <tr>
                                        <td scope="row">{{ $val->id }}</td>
                                        <td>{{ $val->username }}</td>
                                        @php
                                            $color = "Red";
                                            if( !empty($val->transfer_number) ){
                                                if( $val->transfer_number >= $sdr_target ){
                                                    $color = "Green";
                                                }
                                            }
                                        @endphp
                                        <td><span class="custom-sales-number" style="color: {{ $color }}">{{ ( !empty($val->transfer_number) ? $val->transfer_number : 0 ) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

</body>
</html>
