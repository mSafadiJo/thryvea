@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">SDR Report</h4>
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
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" placeholder="From Date" autocomplete="false" class="form-control" value="{{ date('Y-m-d') }}"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" placeholder="To Date" autocomplete="false" class="form-control" value="{{ date('Y-m-d') }}"/>
                                </div>
                                <div class="col-lg-4">
                                    <button type="button" class="btn btn-primary col-lg-12" id="FilterSDRReport">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <h4 class="header-title">SDR Report</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableReport">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                       @if( empty($permission_users) || in_array('3-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Admin Name</th>
                                        <th>Admin Type</th>
                                        <th>Number Of Buyer/Enterprise</th>
                                        <th>Number Of Aggregator</th>
                                        <th>Number Of Sign up</th>
                                        <th>Created At</th>
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
@endsection
