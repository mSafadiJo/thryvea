@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Reports</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                {{--<div class="loader"></div>--}}
                {{--<div class="unloader" style="display: none;">--}}
                <div>
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <a href="#" class="reports_search_show_hide" data-content="toggle-text">Read More <i class="fa fa-chevron-circle-down"></i></a>
                            <div class="reports_search_sections text-left">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="service_id">Service Name</label>
                                            <input id="services-reports" style="width:100%;" placeholder="type a services, scroll for more results" name="service_id[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="traffic_source">TS</label>
                                            <input id="trafficSource-reports" style="width:100%;" placeholder="type a traffic sources, scroll for more results" name="traffic_source[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="state">States Code Filter</label>
                                            <input id="states-reports" style="width:100%;" placeholder="type a states, scroll for more results" name="state_id[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="service_id">Counties Filter</label>
                                            <input id="counties-reports" style="width:100%;" placeholder="type a counties, scroll for more results" name="county_id[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="service_id">Cities Filter</label>
                                            <input id="cities-reports" style="width:100%;" placeholder="type a cities, scroll for more results" name="city_id[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="service_id">ZipCodes Filter</label>
                                            <input id="zipcodes-reports" style="width:100%;" placeholder="type a zipcodes, scroll for more results" name="zipcode_id[]" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="distance_area">Distance Area</label>
                                            <input type="number" class="form-control" id="distance_area" name="distance_area" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-t-10">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" class="form-control" value="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" class="form-control" value="{{ date('Y-m-d') }}"/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary col-lg-12" id="FilterLeadVolume">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <h4 class="header-title">Lead Volume</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableReport">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                       @if( empty($permission_users) || in_array('3-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                    <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Lead#</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
    @include('include.include_reports')
@endsection
