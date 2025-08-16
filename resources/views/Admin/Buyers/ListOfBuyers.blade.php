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
                <div class="row">
                    <div class="col-lg-12 pull-right">
                        @if( empty($permission_users) || in_array('5-4', $permission_users) )
                            <form action="{{ route('Admin.Buyers.Export') }}" method="post">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-rounded btn-info pull-right"><i class="fa fa-download"></i> Export Data</button>
                            </form>
                        @endif
                        @if( empty($permission_users) || in_array('5-1', $permission_users) )
                            <a href="{{ route('Admin_BuyersAdd') }}" class="btn btn-rounded btn-info pull-right mr-2"><i class="fa fa-plus"></i> {{__('Add New Buyer')}}</a>
                        @endif
                    </div>
                </div>
                <br>

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">{{ __('List OF Buyers') }}</h3>
                        {{--<div class="pull-right clearfix">--}}
                        {{--<form id="sort_table_form" action="" method="GET">--}}
                        {{--<div class="box-inline pad-rgt pull-left">--}}
                        {{--<div class="select" style="min-width: 200px;">--}}
                        {{--<select class="form-control select2" name="type" id="type" onchange="sort_list_table()">--}}
                        {{--<option value="">Sort by</option>--}}
                        {{--<option value="users.created_at,desc" @isset($col_name , $query) @if($col_name == 'users.created_at' && $query == 'desc') selected @endif @endisset>{{__('Create At (High > Low)')}}</option>--}}
                        {{--<option value="users.created_at,asc" @isset($col_name , $query) @if($col_name == 'users.created_at' && $query == 'asc') selected @endif @endisset>{{__('Create At (Low > High)')}}</option>--}}
                        {{--<option value="users.username,desc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'desc') selected @endif @endisset>{{__('Username (High > Low)')}}</option>--}}
                        {{--<option value="users.username,asc" @isset($col_name , $query) @if($col_name == 'users.username' && $query == 'asc') selected @endif @endisset>{{__('Username (Low > High)')}}</option>--}}
                        {{--<option value="total_amounts.total_amounts_value,desc" @isset($col_name , $query) @if($col_name == 'total_amounts.total_amounts_value' && $query == 'desc') selected @endif @endisset>{{__('Amount (High > Low)')}}</option>--}}
                        {{--<option value="total_amounts.total_amounts_value,asc" @isset($col_name , $query) @if($col_name == 'total_amounts.total_amounts_value' && $query == 'asc') selected @endif @endisset>{{__('Amount (Low > High)')}}</option>--}}
                        {{--</select>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="box-inline pad-rgt pull-left">--}}
                        {{--<div class="" style="min-width: 200px;">--}}
                        {{--<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Type & Enter">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</form>--}}
                        {{--</div>--}}
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
                                <th>Payment</th>
                                <th>City</th>
                                <th>Claimer</th>
                                <th>Current Balance</th>
                                <th>Transactions Details</th>
                                @if( empty($permission_users) || in_array('5-5', $permission_users) || in_array('5-6', $permission_users) || in_array('5-7', $permission_users)
                                  || in_array('5-8', $permission_users) || in_array('5-9', $permission_users) || in_array('5-10', $permission_users)
                                  || in_array('5-11', $permission_users) )
                                    <th>Details</th>
                                @endif
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
                                    @php
                                        $change_color = 0;
                                        if(!empty($last_trx_arr[$user->id])){
                                            $date1 = date_create(date('m/d/Y', strtotime($last_trx_arr[$user->id])));
                                            $date2 = date_create(date('Y-m-d'));
                                            $diff_date = date_diff($date1,$date2);
                                            $diff_date = $diff_date->format("%a");
                                            if( $diff_date >= 3 ){
                                                $change_color = 1;
                                            }
                                        } else {
                                            $change_color = 1;
                                        }
                                    @endphp
                                    <tr @if($change_color == 1) style="background-color: rgb(248 211 211);"@endif>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            {{ $user->email }}<br/>
                                            {{ $user->user_phone_number }}<br/>
                                            {{ $user->user_mobile_number }}
                                        </td>
                                        <td>{{ $user->user_business_name }}</td>
                                        <td>{{ $user->user_type }}</td>
                                        <td>{{ $user->payment_type_method_type }}</td>
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
                                        <td>@if( !empty($user->total_amounts_value) ) ${{ $user->total_amounts_value }} @else $0 @endif</td>
                                        <td>
                                            @php
                                            $total_spend_data = 0;
                                            if(!empty($total_spend_arr[$user->id])){
                                                $total_spend_data = $total_spend_arr[$user->id];
                                                if(!empty($list_of_return_amount[$user->id])){
                                                    $total_spend_data -= $list_of_return_amount[$user->id];
                                                }
                                            }
                                            @endphp
                                            <b>Current Balance:</b> @if( !empty($user->total_amounts_value) ) ${{ $user->total_amounts_value }} @else $0 @endif<br/>
                                            <b>Total Spent:</b> ${{ number_format($total_spend_data, 2, '.', ',') }} <br/>
                                            <b>Total Funded:</b>  @if( !empty($total_bid_arr[$user->id]) ) ${{ $total_bid_arr[$user->id] }} @else $0 @endif<br/>
                                            <b>Last Transaction:</b> @if( !empty($last_trx_arr[$user->id]) ){{ date('m/d/Y', strtotime($last_trx_arr[$user->id])) }}@endif<br/>
                                        </td>
                                        @if( empty($permission_users) || in_array('5-5', $permission_users) || in_array('5-6', $permission_users) || in_array('5-7', $permission_users)
                                            || in_array('5-8', $permission_users) || in_array('5-9', $permission_users) || in_array('5-10', $permission_users)
                                            || in_array('5-11', $permission_users) )
                                            <td>
                                                @if( $user->user_visibility == 1 )
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Details
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu ListBuyersDrop">
                                                            @if( empty($permission_users) || in_array('5-5', $permission_users) )
                                                                <li><a href='{{ route("Admin_Campaign_buyers", $user->id) }}'>Campaigns</a></li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-6', $permission_users) )
                                                                <li><a href='{{ route("Admin.Buyers.Wallet", $user->id) }}'>Wallet</a></li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-7', $permission_users) )
                                                                <li><a href='{{ route("Admin.Buyers.payments", $user->id) }}'>Payments</a></li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-8', $permission_users) )
                                                                <li><a href='{{ route("Admin.Buyers.Transactions", $user->id) }}'>Transactions</a></li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-12', $permission_users) )
                                                                <li>
                                                                    <span class="suberadminBuyerspan" data-toggle="modal" data-target="#returnlead"
                                                                          onclick='return return_lead_change_buyer_id("{{ $user->id }}")'>
                                                                        Return Lead
                                                                    </span>
                                                                </li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-9', $permission_users) )
                                                                <li><a href='{{ route("Admin.Buyers.Ticket", $user->id) }}'>Tickets</a></li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-10', $permission_users) )
                                                                {{--@if( empty($user->is_claim) )--}}
                                                                <li><span class="suberadminBuyerspan"
                                                                          onclick="return suberadminBuyerspan('{{ $user->id }}', '1', '{{ Auth::user()->account_type }}')">Claim</span></li>
                                                                <button type="button" style="display: none;" data-toggle="modal" data-target="#ClaimModel" id="ClaimModelbuttonOpen">t</button>
                                                                {{--@endif--}}
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-11', $permission_users) )
                                                                <li>
                                                                    <span class="suberadminBuyerspan" onclick="openModelpaymenttypemethod('{{ $user->id }}');">Payment Term</span>
                                                                    <button type="button" style="display: none;" data-toggle="modal" data-target="#paymenttypemethod" id="paymenttypemethodmodelbutton">t</button>
                                                                </li>
                                                            @endif
                                                            @if( empty($permission_users) || in_array('5-15', $permission_users) )
                                                                <li><a href='{{ route("Rev_Share", $user->id) }}'>Rev_Share</a></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
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
                        {{--<div class="clearfix">--}}
                        {{--<div class="pull-right">--}}
                        {{--{{ $users->appends(request()->input())->links() }}--}}
                        {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->

    <!-- Modal -->
    <div class="modal" id="paymenttypemethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Term</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="form-horizontal" action="{{ route('Admin.Buyers.AddPaymentMethod')}}" method="POST" id="AddPaymentMethodModelForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" id="user_id_payment_method_model">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="firstname">Payment Term<span class="requiredFields">*</span></label>
                                            <select class="form-control" id="payment_type" name="payment_type" required="">
                                                @if( !empty($payment_types) )
                                                    @foreach($payment_types as $item)
                                                        <option value="{{ $item->payment_type_method_id }}">{{ $item->payment_type_method_type }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('AddPaymentMethodModelForm').submit();">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="ClaimModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Claims</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="form-horizontal" action="{{ route('Admin.Claim.Add.Buyer') }}" method="POST" id="AddClaimModelForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" id="user_id_Claim_model">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="claim">claim<span class="requiredFields">*</span></label>
                                            <select class="form-control" id="claimselectModel" name="claim" required="">
                                                <option value="Sdr" selected>Sdr</option>
                                                <option value="Sales">Sales</option>
                                                <option value="Account Manager">Account Manager</option>
                                            </select>
                                            <input type="text" class="form-control" readonly value="" id="ClaimHiddenInput">
                                            <input type="hidden" class="form-control" value="1" name="type">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('AddClaimModelForm').submit();">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="returnlead" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Why do you want to return this lead?
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal" action="{{ route('ReturnLeadAdmin') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Not a working number
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="1" required="">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Didn’t request
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="2">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Bogus info
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="3">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Wrong number
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="4">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Doesn’t qualify
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="5">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Not interested
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="6">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">No response
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="7">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">False Advertisement
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="8">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">DNC
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="9">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="container-dayoff">Duplicate
                                    <input type="radio" class="user_privileges_service" name="reason_returned" value="10">
                                    <span class="checkmark-dayoff"></span>
                                </label>
                            </div>
                        </div>



{{--                    @if( !empty($reason_lead_returneds) )--}}
{{--                            @foreach( $reason_lead_returneds as $key => $item )--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-lg-12">--}}
{{--                                        <label class="container-dayoff">{{ $item->reason_lead_returned_name }}--}}
{{--                                            <input type="radio" class="user_privileges_service" name="reason_returned" value="{{ $item->reason_lead_returned_id }}" @if($key == 0) required @endif>--}}
{{--                                            <span class="checkmark-dayoff"></span>--}}
{{--                                        </label>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Unique id# (From Sold Lead)</label>
                                    <input type="text" class="form-control" id="lead_id" name="lead_id" placeholder=""
                                           required="" value="">
                                    <input type="hidden" class="form-control" id="return_lead_user_id" name="user_id" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="firstname">Ticket Message</label>
                                    <textarea class="form-control" name="ticket_message" id="ticket_message" required>

                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
