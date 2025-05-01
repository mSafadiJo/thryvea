@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Users Payments Report</h4>
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
                <div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="form-horizontal" method="GET" action="{{ route('Reports.users_payments') }}">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-4">
                                        <input type="text" id="datepicker1" autocomplete="false" class="form-control" name="start_date" placeholder="Start Date" required
                                               value="@if(!empty($start_date)){{ date('Y-m-d', strtotime($start_date)) }}@endif"/>
                                    </div>
                                    <div class="col-lg-4">
                                        <input type="text" id="datepicker2" autocomplete="false" class="form-control" name="end_date" placeholder="End Date" required
                                               value="@if(!empty($end_date)){{ date('Y-m-d', strtotime($end_date)) }}@endif"/>
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary col-lg-12" id="">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div style="margin-bottom: 5%;"></div>
                    <h4 class="header-title">Performance Report</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="dataAjaxTableReport">
                                <table class="table table-striped table-bordered" cellspacing="0" width="100%"
                                       @if( empty($permission_users) || in_array('3-4', $permission_users) ) id="datatable-buttons" @else id="datatable" @endif>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Business Name</th>
                                            <th>Current Balance</th>
                                            <th>Total Payments</th>
                                            <th>Total Refunds</th>
                                            <th>First Payment</th>
                                            <th>First Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $val)
                                        <tr>
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->user_business_name }}</td>
                                            <td>{{ (!empty($val->total_amounts_value) ? $val->total_amounts_value : 0) }}</td>
                                            <td>{{ (!empty($total_payments[$val->id]) ? $total_payments[$val->id] : 0) }}</td>
                                            <td>{{ (!empty($total_refunds[$val->id]) ? $total_refunds[$val->id] : 0) }}</td>
                                            <td>{{ (!empty($first_payment[$val->id]) ? $first_payment[$val->id] : 0) }}</td>
                                            <td>{{ (!empty($first_date_payment[$val->id]) ? $first_date_payment[$val->id] : "") }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                        <td>Total</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ array_sum($total_payments) }}</td>
                                        <td>{{ array_sum($total_refunds) }}</td>
                                        <td></td>
                                        <td></td>
                                    </tfoot>
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
