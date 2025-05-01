@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Buyers</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>

                <!--Panel heading-->
{{--                <div class="row">--}}
{{--                    <div class="col-lg-12 pull-right">--}}
{{--                        @if( empty($permission_users) || in_array('5-4', $permission_users) )--}}
{{--                            <form action="{{ route('Admin.Buyers.Export') }}" method="post">--}}
{{--                                {{ csrf_field() }}--}}
{{--                                <button type="submit" class="btn btn-rounded btn-info pull-right"><i class="fa fa-download"></i> Export Data</button>--}}
{{--                            </form>--}}
{{--                        @endif--}}
{{--                        @if( empty($permission_users) || in_array('5-1', $permission_users) )--}}
{{--                            <a href="{{ route('Admin_BuyersAdd') }}" class="btn btn-rounded btn-info pull-right mr-2"><i class="fa fa-plus"></i> {{__('Add New Buyer')}}</a>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">List OF Old Buyers</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <table id="datatable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        {{--<table class="table table-striped table-bordered" cellspacing="0" width="100%">--}}
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Users Name</th>
                                <th>Contact</th>
                                <th>Business Name</th>
                                <th>Type</th>
                                <th>City</th>
                                <th>Claimer</th>
                                <th>Status</th>
                                <th>Created At</th>
                                @if( empty($permission_users) || in_array('5-2', $permission_users) || in_array('5-3', $permission_users) )
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($users) )
                                @foreach($users as $key => $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            {{ $user->email }}<br/>
                                            {{ $user->user_phone_number }}<br/>
                                            {{ $user->user_mobile_number }}
                                        </td>
                                        <td>{{ $user->user_business_name }}</td>
                                        <td>{{ $user->user_type }}</td>
                                        <td>{{ str_replace("=> ", "=>", $user->city_name) }}</td>
                                        <td>
                                            @if( !empty($user->acc_manager_username) )
                                                -{{ $user->acc_manager_username }} (Account Manager)<br>
                                            @endif
                                            @if( !empty($user->sales_username) )
                                                -{{ $user->sales_username }} (Sales)<br>
                                            @endif
                                            @if( !empty($user->sdr_username) )
                                                -{{ $user->sdr_username }} (Sdr)<br>
                                            @endif
                                        </td>
                                        <td>{{ $user->users_status_visibility }}</td>
                                        <td>{{  date('Y/m/d', strtotime($user->created_at)) }}</td>
                                        @if( empty($permission_users) || in_array('5-2', $permission_users) || in_array('5-3', $permission_users) )
                                            <td>
                                                @if( empty($permission_users) || in_array('5-2', $permission_users) )
                                                    <a href="{{ route('Admin_Buyers.edit', $user->id) }}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @endif
                                                @if( empty($permission_users) || in_array('5-3', $permission_users) )
                                                    @if( $user->user_visibility == 1 )
                                                        <form style="display: inline-block" action="{{ route('AdminsStoreDelete') }}" method="post" id="DeleteCampaignForm-{{ $user->id }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" class="form-control" value="{{ $user->id }}" name="user_id">
                                                        </form>
                                                        <span style="cursor: pointer;" class="on-default remove-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete" data-trigger="hover" data-animation="false"
                                                              onclick="return DeleteCampaignForm('{{ $user->id }}');">
                                                                <i class="fa fa-trash-o"></i>
                                                            </span>
                                                    @endif
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
