@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">ListOfSeller</h4>
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

                <div class="panel">
                    <div class="panel-heading bord-btm clearfix pad-all h-100">
                        <h3 class="panel-title pull-left pad-no">List Of Seller</h3>
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
                                <th>Current Balance</th>
                                <th>Last Transaction</th>
                                @if( empty($permission_users) || in_array('23-5', $permission_users) || in_array('23-7', $permission_users)
                                  || in_array('23-8', $permission_users) || in_array('23-11', $permission_users) )
                                    <th>Details</th>
                                @endif
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if( !empty($ListOfLeadsSeller) )
                                @foreach($ListOfLeadsSeller as $key => $seller)
                                    <tr>
                                        <td>{{ $seller->id }}</td>
                                        <td>{{ $seller->username }}</td>
                                        <td>
                                            {{ $seller->email }}<br/>
                                            {{ $seller->user_phone_number }}<br/>
                                            {{ $seller->user_mobile_number }}
                                        </td>
                                        <td>{{ $seller->user_business_name }}</td>
                                        <td>{{ $seller->user_type_data }}</td>
                                        <td>${{ ( !empty($seller->total_amounts_value) ? $seller->total_amounts_value : 0 ) }}</td>
                                        <td>@if( !empty($last_trx_arr[$seller->id]) ){{ date('Y/m/d', strtotime($last_trx_arr[$seller->id])) }}@endif</td>
                                        @if( empty($permission_users) || in_array('23-5', $permission_users) || in_array('23-7', $permission_users)
                                            || in_array('23-8', $permission_users) || in_array('23-11', $permission_users) )
                                            <td>
                                                @if( $seller->user_visibility == 1 )
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Details
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu ListBuyersDrop">
                                                            @if( empty($permission_users) || in_array('23-5', $permission_users) )
                                                                <li><a href='{{ route("Admin.Seller.Campaigns.list", $seller->id) }}'>Campaigns</a></li>
                                                            @endif

                                                            @if( empty($permission_users) || in_array('23-8', $permission_users) )
                                                                <li><a href='{{ route("Admin.Sellers.Transactions", $seller->id) }}'>Transactions</a></li>
                                                            @endif

                                                            @if( empty($permission_users) || in_array('23-7', $permission_users) )
                                                                <li>
                                                                    <span class="suberadminBuyerspan" data-toggle="modal" data-target="#paymenttypemethod"
                                                                          onclick='document.getElementById("user_id_payment_method_model").value="{{ $seller->id }}"'>
                                                                        Payments
                                                                    </span>
                                                                </li>
                                                            @endif

                                                            @if( empty($permission_users) || in_array('23-12', $permission_users) )
                                                                <li>
                                                                    <span class="suberadminBuyerspan" data-toggle="modal" data-target="#returnSellerlead"
                                                                          onclick='document.getElementById("user_id_return_lead").value="{{ $seller->id }}"'>
                                                                        Return Leads
                                                                    </span>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        @endif
                                        <td>{{ $seller->users_status_visibility }}</td>
                                        <td>{{  date('Y/m/d', strtotime($seller->created_at)) }}</td>
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

    <!-- Modal -->
    <div class="modal" id="paymenttypemethod" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ACH Payments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form class="form-horizontal" action="{{ route('returnSeller')}}" method="POST" id="PaymentMethodModelSellerForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" id="user_id_payment_method_model">
                                <input type="hidden" name="type" id="" value="1">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="paymentValue">Value<span class="requiredFields">*</span></label>
                                            <input class="form-control" type="text" name="paymentValue" id="" value="">
                                            @foreach( $errors->all() as $error )
                                                <p class="errorText">
                                                    {{ $error }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"
                            onclick="document.getElementById('PaymentMethodModelSellerForm').submit();">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="returnSellerlead" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Return Leads
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="returnSellerleads" class="form-horizontal" action="{{ route('returnSeller') }}" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" id="user_id_return_lead">
                        <input type="hidden" name="type" value="2">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="paymentValue">Value<span class="requiredFields">*</span></label>
                                    <input class="form-control" type="text" name="paymentValue" value="">
                                    @foreach( $errors->all() as $error )
                                        <p class="errorText">
                                            {{ $error }}
                                        </p>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label for="return_note">Note<span class="requiredFields">*</span></label>
                                    <textarea class="form-control" type="text" name="return_note" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                                onclick="document.getElementById('returnSellerleads').submit();">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
