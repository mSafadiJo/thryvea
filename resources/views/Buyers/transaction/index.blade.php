@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="header-title transactions">Transactions</h4>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="header-title" style="float: right;">Total Amount: ${{ (!empty($totalAmmount['total_amounts_value']) ? $totalAmmount['total_amounts_value'] : 0 ) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://unpkg.com/balloon-css/balloon.min.css">
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
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('transaction.buyer.export') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="hidden" name="buyer_id" id="buyer_id" value="{{$user_id}}">
                                        <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination" required/>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination" required/>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <h6 class="text-w">List Of Transactions</h6>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action = "/Transaction/fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>Value</th>
                                        <th>Visa Number</th>
                                        <th>Status</th>
                                        <th>Is PayPal</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( !empty($transactions) )
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>${{ $transaction->transactions_value }}</td>
                                                <td>
                                                    @if( !empty( $transaction->payment_visa_number ) )
                                                        {{ $transaction->payment_visa_type }}-{{ $transaction->payment_visa_number }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if( $transaction->transaction_status == 1 )
                                                        @if( $transaction->accept != 2 )
                                                            Credit
                                                        @else
                                                            <span style="color: red">Failed</span>
                                                        @endif
                                                    @else
                                                        Paid
                                                    @endif
                                                </td>
                                                <td>
                                                    @if( $transaction->transaction_paypall == 1 )
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                                <td>{{ $transaction->transactions_comments }}</td>
                                                <td>{{ $transaction->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                Showing {{($transactions->currentPage()-1)* $transactions->perPage()+($transactions->total() ? 1:0)}} to {{($transactions->currentPage()-1)*$transactions->perPage()+count($transactions)}}  of  {{$transactions->total()}}  Results
                                {!! $transactions->links() !!}
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="row">--}}
                {{--<div class="col-lg-12">--}}
                {{--<div class="addedSectionLogo pull-right"--}}
                {{--onclick=''>--}}
                {{--<i class="mdi mdi-plus-circle-outline" data-toggle="modal" data-target="#con-close-modal"></i>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Payment Gateway</h4>
                </div>
                <div class="modal-body">
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
                                                    <select class="form-control select2" name="paymentMethod" required id="optionShowModel">
                                                        @foreach( $payments as $payment )
                                                            @if( old('paymentMethod') == $payment->payment_id)
                                                                <option value="{{ $payment->payment_id }}" selected>xxxx-xxxx-xxxx-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                            @else
                                                                <option value="{{ $payment->payment_id }}">xxxx-xxxx-xxxx-{{ substr($payment->payment_visa_number, -4) }}</option>
                                                            @endif
                                                        @endforeach
                                                        <option value="other">Other Payment Method</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-12">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light pull-right">Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
