@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="header-title">Transactions</h4>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="header-title" style="float: right;">Total Amount: ${{ (!empty($totalAmmount['total_amounts_value']) ? $totalAmmount['total_amounts_value'] : 0 ) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('listOfUserTransactionsExport') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-3">
                                    <input type="hidden" name="buyer_id" id="buyer_id" value="{{$user_id}}">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination"/>
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                </div>
                                @if( empty($permission_users) || in_array('5-4', $permission_users) )
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

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
                <h6>List Of Transactions</h6>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       @if($is_seller == 1) data-action = "/Admin/Transactions/fetch_data_seller?page=" @else data-action = "/Admin/Transactions/fetch_data?page=" @endif>
                                    <thead>
                                    <tr>
                                        <th>Value</th>
                                        @if( $is_seller == 0 )
                                            <th>Visa Number</th>
                                        @endif
                                        <th>Status</th>
                                        @if( $is_seller == 0 )
                                            <th>Is PayPal</th>
                                        @endif
                                        <th>Type</th>
                                        <th>Admin</th>
                                        <th>Created At</th>
                                        @if( $is_seller == 0 )
                                            @if( Auth::user()->role_id == 1 )
                                                <th>Action</th>
                                            @endif
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if( !empty($transactions) )
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>${{ $transaction->transactions_value }}</td>
                                                @if( $is_seller == 0 )
                                                    <td>
                                                        @if( !empty( $transaction->payment_visa_number ) )
                                                            {{ $transaction->payment_visa_type }}-{{ $transaction->payment_visa_number }}
                                                        @endif
                                                    </td>
                                                @endif

                                                <td>
                                                    @if( $transaction->transaction_status == 1 )
                                                        @if( $transaction->accept != 2 )
                                                            Credit
                                                        @else
                                                            <span style="color: red">Failed</span>
                                                        @endif
                                                    @elseif( $transaction->transaction_status == 2 )
                                                        Refund
                                                    @else
                                                        {{ ($is_seller == 0 ? "Sold" : "Bought") }}
                                                    @endif
                                                </td>
                                                @if( $is_seller == 0 )
                                                    <td>{{ ( $transaction->transaction_paypall === 1 ? "Yes" : "No") }}</td>
                                                @endif

                                                <td>{{ $transaction->transactions_comments }}</td>
                                                <td>{{ $transaction->username }}</td>
                                                <td>{{ $transaction->created_at }}</td>
                                                @if( $is_seller == 0 )
                                                    @if( Auth::user()->role_id == 1 )
                                                        <td>
                                                            @if( !empty($transaction->transactionauthid)
                                                              && ( in_array($transaction->transactions_comments, ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal']) )
                                                              && $transaction->transaction_status == 1
                                                              && $transaction->is_refund == 0
                                                              && $transaction->accept != 2 )
                                                                <button type="button" class="btn btn-danger"
                                                                        onclick="return refundTrxpayments('{{ $transaction->transaction_id }}', '{{ $transaction->transactions_value }}',  '{{ $transaction->payment_type }}')">Refund Trx</button>
                                                            @elseif($transaction->transactions_comments == "ACH Credit")
                                                                <button type="button" class="btn btn-danger"
                                                                        onclick="return DeletePayments('{{ $transaction->transaction_id }}', '{{ $transaction->transactions_value }}', '{{$user_id}}', this)">Delete ACH</button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                @endif
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
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="refundTrxPaymentsmodel" style="display: none;">
        Launch demo modal
    </button>

    <!-- Modal -->
    <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Refund Transactions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('Admin.Payments.Refund') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="refundTrxPaymentsFormamountStripe">Value $</label>
                                    <input type="hidden" class="form-control" value="" name="id" id="refundTrxPaymentsFormId">
                                    <input type="hidden" class="form-control" value="" name="merchant_account" id="refundTrxPaymentsFormType">
                                    <input type="text" class="form-control" value="" name="amount" id="refundTrxPaymentsFormAmount">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group pull-right">
                                    <input type="submit" class="btn btn-primary" value="submit"
                                           onclick="return confirm('are you sure you want to refund this?');">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="display: none">

    </div>
    <script>
        function refundTrxpayments(id, amount, type) {
            $("#refundTrxPaymentsFormId").val(id);
            $("#refundTrxPaymentsFormType").val(type);
            $("#refundTrxPaymentsFormAmount").val(amount);
            $("#refundTrxPaymentsmodel").click();
        }
    </script>
    <!-- End Of Page 1-->
@endsection
