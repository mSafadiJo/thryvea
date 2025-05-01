@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
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
