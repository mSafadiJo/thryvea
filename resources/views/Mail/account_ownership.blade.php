Hi {{ $name }},

@if( $is_claim == 1 )
Admin {{ $admin_name }} requested to claim {{ $buyername }} buyer.
@else
Admin {{ $admin_name }} requested to change the payment term for {{ $buyername }} buyer to {{ $typeofpayment }}.
@endif

Thank you,

{{ Config::get('app.name') }}
