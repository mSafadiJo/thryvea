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
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="tab-content">
                    <style>
                        input#amountvalue {
                            width: 70%;
                            margin: 1% auto;
                            padding: 22px 10px;
                        }
                    </style>
                    <!-- End row -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="text-muted" id="pErrorsShown">

                            </p>
                        </div>
                    </div>
                    <script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.PAYPAL_CLIENT_ID', '') }}&currency=USD"></script>

                    <div class="row">
                        <div class="col-12 text-center">
                            <input type="text" name="value" class="form-control" id="amountvalue" placeholder="Value $">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>

                    <script>
                        var valueammpunt = '';
                        $('#amountvalue').change(function () {
                            valueammpunt = $('#amountvalue').val();
                        });

                        paypal.Buttons({
                            createOrder: function(data, actions) {
                                if( valueammpunt == '' ){
                                    $('#pErrorsShown').html('please Add Value');
                                } else {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: valueammpunt
                                            }
                                        }],
                                        application_context: {
                                            shipping_preference: 'NO_SHIPPING'
                                        }
                                    });
                                }
                            },
                            style: {
                                size: 'responsive',
                                color: 'blue'
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    $('#pErrorsShown').html('Transaction completed by ' + details.payer.name.given_name);

                                    $.ajax({
                                        url: TransactionvalueinPayPalButtons,
                                        method: "POST",
                                        data: {
                                            value: valueammpunt,
                                            _token: token
                                        },
                                        success: function(the_result){ }
                                    });
                                    // Call your server to save the transaction
                                    return fetch('/paypal-transaction-complete', {
                                        method: 'post',
                                        headers: {
                                            'content-type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            orderID: data.orderID
                                        })
                                    });
                                });
                            }
                        }).render('#paypal-button-container');
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 2-->
@endsection
