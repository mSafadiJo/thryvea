Dear {{ $name }},

You have a new lead

Client's FirstName: {{ $first_name }}

Client's LastName: {{ $last_name }}

Client's Email: {{ $LeadEmail }}

Client's Phone Number: {{ $LeadPhone }}

Client's Street Address: {{ $street }}

Client's ZipCode: {{ $Zipcode }}

Client's City: {{ $City }}

Client's State: {{ $State }}

Lead Service: {{ $LeadService }}

Lead ID: {{ $leadCustomer_id }}

@if(!empty($data['one']))
{{ $data['one'] }}

@endif
@if(!empty($data['two']))
{{ $data['two'] }}

@endif
@if(!empty($data['three']))
{{ $data['three'] }}

@endif
@if(!empty($data['four']))
{{ $data['four'] }}

@endif
@if(!empty($data['five']))
{{ $data['five'] }}

@endif
@if(!empty($data['six']))
{{ $data['six'] }}

@endif
@if(!empty($data['seven']))
    {{ $data['seven'] }}

@endif
@if(!empty($data['eight']))
    {{ $data['eight'] }}

@endif
@if(!empty($data['nine']))
    {{ $data['nine'] }}

@endif
@if(!empty($data['ten']))
    {{ $data['ten'] }}

@endif
@if(!empty($data['eleven']))
    {{ $data['eleven'] }}

@endif
@if(!empty($data['twelve']))
    {{ $data['twelve'] }}

@endif
@if(!empty($data['thirteen']))
    {{ $data['thirteen'] }}

@endif
@if(!empty($data['fourteen']))
    {{ $data['fourteen'] }}

@endif
@if(!empty($data['fifteen']))
    {{ $data['fifteen'] }}

@endif
@if( !empty($appointment_type ) )
Appointment Type: {{ $appointment_type }}

@endif
@if( !empty($appointment_date ) )
Appointment Date: {{ $appointment_date }}

@endif

For more information and to view your received leads please login using the link below:
https://{{ $_SERVER['SERVER_NAME'] }}

Thank you!

{{ Config::get('app.name') }}
