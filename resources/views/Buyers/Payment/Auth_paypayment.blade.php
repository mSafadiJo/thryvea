@extends('layouts.NavBuyerHome')

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
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <ul class="nav nav-tabs">
                    <li class="nav-item col-6 text-center">
                        <a href="#Card" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Credit/Debit Card
                        </a>
                    </li>
                    <li class="nav-item col-6 text-center">
                        <a href="#Paypal" data-toggle="tab" aria-expanded="false" class="nav-link">
                            PayPal
                        </a>
                    </li>
                    {{--<li class="nav-item col-4 text-center">--}}
                    {{--<a href="#echeck" data-toggle="tab" aria-expanded="false" class="nav-link">--}}
                    {{--eCheck--}}
                    {{--</a>--}}
                    {{--</li>--}}
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
                                                    {!! $message !!}
                                                </div>
                                                <?php Session::forget('success');?>
                                            @endif

                                            @if ($message = Session::get('error'))
                                                <div class="alert alert-danger fade in alert-dismissible show">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true" style="font-size:20px">×</span>
                                                    </button>
                                                    {!! $message !!}
                                                </div>
                                                <?php Session::forget('error');?>
                                            @endif
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

                                    <form class="form-horizontal row" role="form" method="post" action="{{ route('Transaction.Value.Store') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group inline-block col-12">
                                            <label class="col-md-12 control-label">Value</label>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value" required value="{{ old('value') }}">
                                            </div>
                                        </div>
                                        <div class="form-group inline-block col-12">
                                            <label class="col-md-12 control-label">Payment Method</label>
                                            <div class="col-md-12">
                                                <input type="hidden" class="form-control" id="payment_user_id" name="payment_user_id" required value="{{ Auth::user()->id }}">
                                                <select class="form-control" name="paymentMethod" required id="optionShowModel">
                                                    @foreach( $payments as $payment )
                                                        @if( old('paymentMethod') == $payment->payment_id)
                                                            <option value="{{ $payment->payment_id }}" selected>{{ $payment->payment_visa_type }}-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                        @else
                                                            <option value="{{ $payment->payment_id }}">{{ $payment->payment_visa_type }}-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <button type="button" id="submitButtonsAddTransaction_Auth" class="btn btn-primary waves-effect waves-light pull-right">Confirm Payment
                                            </button>
                                        </div>
                                    </form>

                                    <form id="payment-card-info" method="post" action="{{ route('dopay.online') }}" style="display:none;">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group owner col-md-8">
                                                <label for="owner">Owner</label>
                                                <input type="text" class="form-control" id="owner" name="owner" value="{{ old('owner') }}" required>
                                                <span id="owner-error" class="error text-red">Please enter owner name</span>
                                            </div>
                                            <div class="form-group CVV col-md-4">
                                                <label for="cvv">CVV</label>
                                                <input type="number" class="form-control" id="cvv" name="cvv" value="{{ old('cvv') }}" required>
                                                <span id="cvv-error" class="error text-red">Please enter cvv</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-8" id="card-number-field">
                                                <label for="cardNumber">Card Number</label>
                                                <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="{{ old('cardNumber') }}" required>
                                                <span id="card-error" class="error text-red">Please enter valid card number</span>
                                            </div>
                                            <div class="form-group col-md-4" >
                                                <label for="amount">Amount</label>
                                                <input type="number" class="form-control" id="amount" name="amount" min="1" value="{{ old('amount') }}" required>
                                                <span id="amount-error" class="error text-red">Please enter amount</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6" id="expiration-date">
                                                <label>Expiration Date</label><br/>
                                                <input type="text" class="form-control" id="expiration-month" name="expiration-month" value="{{ old('expiration-month') }}" required>
                                                <input type="text" class="form-control" id="expiration-year" name="expiration-year" value="{{ old('expiration-year') }}" required>

                                                <input type="text" class="form-control" id="payment_id" name="payment_id" value="{{ old('payment_id') }}" required>
                                                <input type="text" class="form-control" id="user_id" name="user_id" value="{{ old('user_id') }}" required>
                                            </div>
                                        </div>

                                        <br/>
                                        <div class="form-group" id="pay-now">
                                            <button type="submit" class="btn btn-success themeButton" id="confirm-purchase">Confirm Payment</button>
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
                                <input class="w3-input w3-border pay_pal_amount" id="amount" type="text" name="amount">
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
                                            <form method="post" action="{{ route('echeckPaymentformAdmin', Auth::user()->id) }}">
                                                {{ csrf_field() }}
                                                <div class="row form-group">
                                                    <div class="col-6">
                                                        <label class="col-md-12 control-label">Value</label>
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="$" id="value" name="value" required value="{{ old('value') }}">
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
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Pay Now
                                                    </button>
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
