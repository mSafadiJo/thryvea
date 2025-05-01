@extends('layouts.adminapp')

@section('content')
    <link href="{{ asset('css/sales_transfers_dashboard.css') }}" rel="stylesheet" type="text/css" />

    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Sales/Transfers Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <h4 class="header-title">Dashboard Settings</h4>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="sales_target">Sales Target</label>
                                    <input type="text" class="form-control" id="sales_target" value="{{ $sales_target }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="daly_sales_max_transfer">Daly Sales Max Transfer</label>
                                    <input type="text" class="form-control" id="daly_sales_max_transfer" value="{{ $daly_sales_max_transfer }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="sdr_target">SDR's Target</label>
                                    <input type="text" class="form-control" id="sdr_target" value="{{ $sdr_target }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="daly_sdr_max_transfer">Daly SDR's Max Transfer</label>
                                    <input type="text" class="form-control" id="daly_sdr_max_transfer" value="{{ $daly_sdr_max_transfer }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-9"></div>
                            <div class="col-lg-3">
                                <input type="hidden" id="saveDashboardType" value="1" class="form-control"/>
                                <button type="button" class="btn btn-primary col-lg-12" id="saveDashboardSalesSettings">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <h4 class="header-title"># Of Sales</h4>
                <div class="row" style="margin-bottom: 20px;">
                    @foreach($sales as $val)
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="sales_username">{{ $val->username }}</label>
                                <div class="inputs-number-div-{{ $val->id }}">
                                    <span class="input-number-decrement">–</span>
                                    <input class="input-number" data-id="{{ $val->id }}" type="text" value="{{ ( !empty($val->transfer_number) ? $val->transfer_number : 0 ) }}" min="0" max="100">
                                    <span class="input-number-increment">+</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h4 class="header-title"># Of Transfers</h4>
                <div class="row" style="margin-bottom: 20px;">
                    @foreach($sdrs as $val)
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="sales_username">{{ $val->username }}</label>
                                <div class="inputs-number-div-{{ $val->id }}">
                                    <span class="input-number-decrement">–</span>
                                    <input class="input-number" data-id="{{ $val->id }}" type="text" value="{{ ( !empty($val->transfer_number) ? $val->transfer_number : 0 ) }}" min="0" max="100">
                                    <span class="input-number-increment">+</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
