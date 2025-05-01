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
                        <a href="#Paypal" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Paypal
                        </a>
                    </li>
                    <li class="nav-item col-6 text-center">
                        <a href="#Card" data-toggle="tab" aria-expanded="false" class="nav-link ">
                            Credit/Debit Card
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
                                                <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
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
                                                <select class="form-control" name="paymentMethod" required id="optionShowModel">
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
                                            <button type="button" id="submitButtonsAddTransaction" class="btn btn-primary waves-effect waves-light pull-right">Submit
                                            </button>
                                        </div>
                                    </form>

                                    <div style="display:none;">
                                        <form id="myCCForm" action="{{ route('pay2checkout') }}" method="post">
                                            {{ csrf_field() }}
                                            <input id="token" name="token" type="hidden" value="">
                                            <input class='form-control' id="full_name" name="full_name" type='text' value="">
                                            <input autocomplete='off' class='form-control' type='text' value="" name="value" id="value">
                                            <input autocomplete='off' class='form-control' type='text' value="" name="zipcode" id="zipcode">
                                            <input autocomplete='off' class='form-control' type='text' value="" name="addrLine1" id="addrLine1">

                                        <div>
                                            <label>
                                                <span>Card Number</span>
                                            </label>
                                            <input id="ccNo" type="text" size="20" value="" autocomplete="off" required />
                                        </div>
                                        <div>
                                            <label>
                                                <span>Expiration Date (MM/YYYY)</span>
                                            </label>
                                            <input type="text" size="2" id="expMonth" required />
                                            <span> / </span>
                                            <input type="text" size="2" id="expYear" required />
                                        </div>
                                        <div>
                                            <label>
                                                <span>CVC</span>
                                            </label>
                                            <input id="cvv" size="4" type="text" value="" autocomplete="off" required />
                                        </div>
                                        <input type="submit" id="pay2checkout" value="Submit Payment">
                                    </form>

                                </div>
                                    <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
                                    <script type="text/javascript">
                                        // Called when token created successfully.
                                        var successCallback = function(data) {
                                            var myForm = document.getElementById('myCCForm');

                                            // Set the token as the value for the token input
                                            myForm.token.value = data.response.token.token;

                                            // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
                                            myForm.submit();
                                        };

                                        // Called when token creation fails.
                                        var errorCallback = function(data) {
                                            if (data.errorCode === 200) {
                                                tokenRequest();
                                            } else {
                                                alert(data.errorMsg);
                                            }
                                        };

                                        var tokenRequest = function() {
                                            // Setup token request arguments
                                            var args = {
                                                sellerId: "{{ config('services.2checkout.2CHECKOUT_SELLER_ID', '') }}",
                                                publishableKey: "{{ config('services.2checkout.2CHECKOUT_PUPLIC_KEY', '') }}",
                                                ccNo: $("#ccNo").val(),
                                                cvv: $("#cvv").val(),
                                                expMonth: $("#expMonth").val(),
                                                expYear: $("#expYear").val()
                                            };

                                            // Make the token request
                                            TCO.requestToken(successCallback, errorCallback, args);
                                        };

                                        $(function() {
                                            // Pull in the public encryption key for our environment
                                            TCO.loadPubKey('sandbox');

                                            $("#myCCForm").submit(function(e) {
                                                // Call our token request function
                                                tokenRequest();

                                                // Prevent form from submitting
                                                return false;
                                            });
                                        });

                                        $(document).ready(function(){
                                            $('#submitButtonsAddTransaction').click(function(){
                                                var valueForm = $('#ValueInputPayment').val();
                                                var payment_id = $('#optionShowModel').val();
                                                if( payment_id == 'other' ){
                                                    window.location.href = '/Transaction/Value/Create/'+valueForm;
                                                } else {
                                                    $.ajax({
                                                        url: GetPaymentDetailsAjax,
                                                        method: "POST",
                                                        data: {
                                                            valueForm: valueForm,
                                                            payment_id: payment_id,
                                                            _token: token
                                                        },
                                                        success: function(the_result){
                                                            $('#full_name').val(the_result.payment_full_name);
                                                            $('#ccNo').val(the_result.payment_visa_number);
                                                            $('#value').val(the_result.valueForm);
                                                            $('#cvv').val(the_result.payment_cvv);
                                                            $('#expYear').val(the_result.payment_exp_year);
                                                            $('#expMonth').val(the_result.payment_exp_month);
                                                            $('#zipcode').val(the_result.payment_zip_code);
                                                            $('#addrLine1').val(the_result.payment_address);

                                                            $('#pay2checkout').click();
                                                            $.ajax({
                                                                url: TransactionValueStore,
                                                                method: "POST",
                                                                data: {
                                                                    value: valueForm,
                                                                    paymentMethod: payment_id,
                                                                    _token: token
                                                                },
                                                                success: function(the_result){  }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    </script>
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
