@extends('layouts.adminapp')

@section('content')
    <link href="{{ asset('css/sales_transfers_dashboard.css') }}" rel="stylesheet" type="text/css" />

    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">CallCenter Target Dashboard</h4>
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
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sales_target">CallCenter Target</label>
                                    <input type="text" class="form-control" id="callCenter_target" value="{{ $callCenter_target }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="daly_sales_max_transfer">Daly CallCenter Max Transfer</label>
                                    <input type="text" class="form-control" id="daly_callCenter_max_transfer" value="{{ $daly_callCenter_max_transfer }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <input type="hidden" id="saveDashboardType" value="2" class="form-control"/>
                                <label for="saveDashboardSalesSettings">.</label>
                                <button type="button" class="btn btn-primary col-lg-12" id="saveDashboardSalesSettings">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="header-title"># Of CallCenter</h4>
                <div class="row" style="margin-bottom: 20px;">
                    @foreach($callCenters as $val)
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="sales_username">{{ $val->user_business_name }}</label>
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
