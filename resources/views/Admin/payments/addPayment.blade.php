@extends('layouts.adminapp')

@section('content')
    <!-- Page 2-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Add Payment Value</h4>
            </div>
        </div>
    </div>
    <style type="text/css">
        .panel-title {
            display: inline;
            font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }
        .panel-heading {
            margin: 0 auto;
        }
        label.col-md-12.control-label {
            float: left;
        }
    </style>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .w3-display-middle {
            position: unset !important;
            transform: unset !important;
        }
        span.w3-button.w3-red.w3-large.w3-display-topright {
            font-size: unset !important;
        }
        .input-group-sm>.input-group-btn>select.btn:not([size]):not([multiple]), .input-group-sm>select.form-control:not([size]):not([multiple]), .input-group-sm>select.input-group-addon:not([size]):not([multiple]), select.form-control-sm:not([size]):not([multiple]) {
            height: unset;
        }
    </style>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <ul class="nav nav-tabs">
                    <li class="nav-item col-2 text-center @if( !(empty($permission_users) || in_array('5-13', $permission_users)) ) col-3 @endif">
                        <a href="#Card" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Credit/Debit Card
                        </a>
                    </li>
                    <li class="nav-item col-2 text-center">
                        <a href="#Paypal" data-toggle="tab" aria-expanded="false" class="nav-link">
                            PayPal
                        </a>
                    </li>
{{--                    <li class="nav-item col-2 text-center">--}}
{{--                    <a href="#echeck" data-toggle="tab" aria-expanded="false" class="nav-link">--}}
{{--                    eCheck--}}
{{--                    </a>--}}
{{--                    </li>--}}
                    <li class="nav-item col-2 text-center">
                        <a href="#ACHCredit" data-toggle="tab" aria-expanded="false" class="nav-link">
                            ACH Credit
                        </a>
                    </li>
                    <li class="nav-item col-2 text-center">
                        <a href="#AddCredit" data-toggle="tab" aria-expanded="false" class="nav-link ">
                            Add Credit
                        </a>
                    </li>
                    <li class="nav-item col-2 text-center @if( !(empty($permission_users) || in_array('5-13', $permission_users)) ) col-3 @endif">
                        <a href="#Promotional" data-toggle="tab" aria-expanded="false" class="nav-link ">
                            Add Promotional Credit
                        </a>
                    </li>
                    @if( empty($permission_users) || in_array('5-13', $permission_users) )
                        <li class="nav-item col-2 text-center">
                            <a href="#ReturnLead" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Return Lead
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="Card">
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
                                            @foreach( $errors->all() as $error )
                                                <div class="alert alert-danger fade in alert-dismissible show">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true" style="font-size:20px">×</span>
                                                    </button>
                                                    {{ $error }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="panel-heading display-table" >
                                        <div class="row display-tr" >
                                            <h3 class="panel-title display-td" >Payment Details</h3>
                                            <div class="display-td" >
                                                <img class="img-responsive pull-right" src="{{ URL::asset('images/accepted_c22e0.png') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <form class="form-horizontal row" role="form" method="post" action="{{ route('Admin.Payments.Add') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group inline-block col-12">
                                            <label class="col-md-12 control-label">Value</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="amount" required value="">
                                                <input type="hidden" class="form-control" name="merchant_account" required value="{{ Config::get('services.PAYMENT_METHODS') }}">
                                            </div>
                                        </div>
                                        <div class="form-group inline-block col-12">
                                            <label class="col-md-12 control-label">Payment Method</label>
                                            <div class="col-md-12">
                                                <input type="hidden" class="form-control" id="payment_user_id" name="user_id" required value="{{ $user_id }}">
                                                <select class="form-control" name="payment_id" required id="optionShowModel">
                                                    @foreach( $payments as $payment )
                                                        @if( old('paymentMethod') == $payment->payment_id)
                                                            <option value="{{ $payment->payment_id }}" selected>{{ $payment->payment_visa_type }}-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                        @else
                                                            <option value="{{ $payment->payment_id }}">{{ $payment->payment_visa_type }}-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                        @endif
                                                    @endforeach
                                                    <option value="other">Other Payment Method</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Confirm Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="Paypal">
                        <div class="w3-container">
                            <form class="w3-container w3-display-middle w3-card-4 w3-padding-16" method="POST" id="payment-form" action="{!! URL::to('paypal') !!}">
                                <div class="w3-container w3-teal w3-padding-16">Paywith Paypal</div>
                                {{ csrf_field() }}
                                <h2 class="w3-text-blue">Payment Form</h2>
                                <label class="w3-text-blue"><b>Enter Amount</b></label>
                                <input class="w3-input w3-border pay_pal_amount" id="amount" type="text" name="amount" value="">
                                <input class="w3-input w3-border" id="user_id" type="hidden" name="user_id" value="{{ $user_id }}">
                                <br/>
                                <button class="w3-btn w3-blue pay_pal_button">Pay with PayPal</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="echeck">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('echeckPaymentformAdmin', $user_id) }}">
                                                {{ csrf_field() }}
                                                <div class="row form-group">
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Value</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="$" id="amount" name="amount" required value="{{ old('amount') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Account Type</label>
                                                        <div class="col-md-12">
                                                            <select class="form-control" name="account_type" required id="account_type">
                                                                <option value="checking">Checking</option>
                                                                <option value="savings">Savings</option>
                                                                <option value="businessChecking">Business Checking</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Routing Number</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="Routing Number" id="routing_number" name="routing_number" required value="{{ old('routing_number') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Account Number</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="Account Number" id="account_number" name="account_number" required value="{{ old('account_number') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Name On Bank Account</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="Name On Bank Account" id="username" name="username" required value="{{ old('username') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Bank Name</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="Bank Name" id="bank_name" name="bank_name" required value="{{ old('bank_name') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Pay Now</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="ACHCredit">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('addACHCredit', $user_id) }}" name="addACHCredit">
                                                {{ csrf_field() }}
                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Value</label>
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="type" value="ACH Credit">
                                                        <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value" required value="{{ old('value') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="AddCredit">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('addACHCredit', $user_id) }}" name="addACHCredit">
                                                {{ csrf_field() }}
                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Value</label>
                                                    <div class="col-md-12">
                                                        <input type="hidden" name="type" value="Add Credit">
                                                        <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value" required value="{{ old('value') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="Promotional">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('addPromotionalCredit', $user_id) }}" name="addPromotionalCredit">
                                                {{ csrf_field() }}
                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Value</label>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value_promo" required value="{{ old('value_promo') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="ReturnLead">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form method="post" action="{{ route('addReturnLeadBidPayment', $user_id) }}" name="ReturnLead">
                                                {{ csrf_field() }}
                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Value</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value_return" required value="{{ old('value_return') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Return Date</label>
                                                    <div class="col-md-12">
                                                        <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="datepicker1" name="return_date" required value="{{ old('return_date') }}">
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <label class="col-md-12 control-label">Note</label>
                                                    <div class="col-md-12">
                                                        <textarea class="form-control" id="return_note" name="return_note">{{ old('return_note') }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group inline-block col-12">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 2-->
@endsection
