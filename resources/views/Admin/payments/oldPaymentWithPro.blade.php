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
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <ul class="nav nav-tabs">
                    <li class="nav-item col-4 text-center">
                        <a href="#Paypal" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Paypal
                        </a>
                    </li>
                    <li class="nav-item col-4 text-center">
                        <a href="#Card" data-toggle="tab" aria-expanded="false" class="nav-link ">
                            Credit/Debit Card
                        </a>
                    </li>
                    <li class="nav-item col-4 text-center">
                        <a href="#Promotional" data-toggle="tab" aria-expanded="false" class="nav-link ">
                            Add Promotional Credit
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="Paypal">
                        <div class="row">
                            <div class="col-lg-12">
                                @if ($message = Session::get('success'))
                                    <div class="w3-panel w3-green w3-display-container">
                                        <span onclick="this.parentElement.style.display='none'"
                                              class="w3-button w3-green w3-large w3-display-topright">&times;</span>
                                        <p>{!! $message !!}</p>
                                    </div>
                                    <?php Session::forget('success');?>
                                @endif

                                @if ($message = Session::get('error'))
                                    <div class="w3-panel w3-red w3-display-container">
                                        <span onclick="this.parentElement.style.display='none'"
                                              class="w3-button w3-red w3-large w3-display-topright">&times;</span>
                                        <p>{!! $message !!}</p>
                                    </div>
                                    <?php Session::forget('error');?>
                                @endif
                            </div>
                        </div>
                        <div class="w3-container">
                            <form class="w3-container w3-display-middle w3-card-4 w3-padding-16" method="POST" id="payment-form"
                                  action="{!! URL::to('paypal') !!}">
                                <div class="w3-container w3-teal w3-padding-16">Paywith Paypal</div>
                                {{ csrf_field() }}
                                <h2 class="w3-text-blue">Payment Form</h2>
                                <input type="hidden" class="form-control" name="user_id" required value="{{ $user_id }}">
                                <label class="w3-text-blue"><b>Enter Amount</b></label>
                                <input class="w3-input w3-border" id="amount" type="text" name="amount"></p>
                                <button class="w3-btn w3-blue">Pay with PayPal</button>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="Card">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @if ($message = Session::get('success'))
                                                <div class="w3-panel w3-green w3-display-container">
                                        <span onclick="this.parentElement.style.display='none'"
                                              class="w3-button w3-green w3-large w3-display-topright">&times;</span>
                                                    <p>{!! $message !!}</p>
                                                </div>
                                                <?php Session::forget('success');?>
                                            @endif

                                            @if ($message = Session::get('error'))
                                                <div class="w3-panel w3-red w3-display-container">
                                        <span onclick="this.parentElement.style.display='none'"
                                              class="w3-button w3-red w3-large w3-display-topright">&times;</span>
                                                    <p>{!! $message !!}</p>
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
                                    <form class="form-horizontal row" role="form" method="post" action="{{ route('PayPalProPaymentController') }}">
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
                                            <button type="submit" id="submitButtonsAddTransaction" class="btn btn-primary waves-effect waves-light pull-right">Submit
                                            </button>
                                        </div>
                                    </form>
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
                                                        <input type="text" class="form-control" placeholder="$" id="ValueInputPayment" name="value" required value="{{ old('value') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group col-12">
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light col-12">Submit
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