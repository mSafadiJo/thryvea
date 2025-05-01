@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered" data-action="/Transaction/fetch_data?page=">
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
