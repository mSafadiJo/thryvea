@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Buyer Payment Terms</h4>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('Buyer Payment Terms') }}</h3>
{{--                        <div class="pull-right clearfix">--}}
{{--                            <form id="sort_table_form" action="" method="GET">--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="select" style="min-width: 200px;">--}}
{{--                                        <select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
{{--                                            <option value="">Sort by</option>--}}
{{--                                            <option value="user_payment_type_admin.created_at,desc" @isset($col_name , $query) @if($col_name == 'user_payment_type_admin.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
{{--                                            <option value="user_payment_type_admin.created_at,asc" @isset($col_name , $query) @if($col_name == 'user_payment_type_admin.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
{{--                                            <option value="users.username,desc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'desc') selected @endif @endisset>{{__('Username (High > Low)')}}</option>--}}
{{--                                            <option value="users.username,asc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'asc') selected @endif @endisset>{{__('Username (Low > High)')}}</option>--}}
{{--                                            <option value="user_payment_type_admin.admin_name,desc" @isset($col_name , $query) @if($col_name == 'user_payment_type_admin.admin_name' && $query == 'desc') selected @endif @endisset>{{__('Admin Name (High > Low)')}}</option>--}}
{{--                                            <option value="user_payment_type_admin.admin_name,asc" @isset($col_name , $query) @if($col_name == 'user_payment_type_admin.admin_name' && $query == 'asc') selected @endif @endisset>{{__('Admin Name (Low > High)')}}</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="box-inline pad-rgt pull-left">--}}
{{--                                    <div class="" style="min-width: 200px;">--}}
{{--                                        <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
{{--                        <table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                        <table  class="table table-striped table-bordered" cellspacing="0" width="100%" id="datatable">
                            <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Payment Term</th>
                                <th>Admin Name</th>
                                <th>Created At</th>
                                <th>Confirmed by</th>
                                <th>Confirmed At</th>
                                <th>Threshold</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($payment_types) )
                                @foreach($payment_types as $key => $payment_type)
                                    <tr>
                                        <td>{{ $payment_type->username }} ({{ $payment_type->user_business_name }})</td>
                                        <td>{{ $payment_type->payment_type_method_type }}</td>
                                        <td>{{ $payment_type->admin_name }}</td>
                                        <td>{{ date('Y/m/d', strtotime($payment_type->created_at)) }}</td>
                                        <td>
                                            @if( $payment_type->payment_type_method_status == 1 )
                                                {{ $payment_type->confirmed_by_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if( $payment_type->payment_type_method_status == 1 )
                                                {{ date('Y/m/d', strtotime($payment_type->confirmed_at)) }}
                                            @endif
                                        </td>
                                        <td>
                                            <input type="text" class="form-control payment_limit" name="payment_limit" id="payment_limit_{{ $payment_type->user_payment_type_admin_id }}"
                                                value="{{ $payment_type->payment_type_method_limit }}">
                                        </td>
                                        <td>
                                            @if( $payment_type->payment_type_method_status == 0 )
                                                <button type="button" class="btn btn-success" id="agreeClaim"
                                                        onclick="return changePaymentMethodStatus('1', '{{ $payment_type->user_payment_type_admin_id }}');" style="margin: 2% 0;">Agree</button>
                                            @else
                                                <button type="button" class="btn btn-danger" id="ignoreClaim"
                                                        onclick="return changePaymentMethodStatus('0', '{{ $payment_type->user_payment_type_admin_id }}');" style="margin: 2% 0;">Ignore</button>
                                            @endif
                                            / <button type="button" class="btn btn-danger" id="deleteClaim"
                                                      onclick="return changePaymentMethodStatus('2', '{{ $payment_type->user_payment_type_admin_id }}');">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
{{--                        <div class="clearfix">--}}
{{--                            <div class="pull-right">--}}
{{--                                {{ $payment_types->appends(request()->input())->links() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
