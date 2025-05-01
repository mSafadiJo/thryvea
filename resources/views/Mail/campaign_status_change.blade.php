Hi {{ $name }},

{{ $user_type }} has changed {{ $campaign }} campaign status to {{ $action }}.

https://{{ request()->getHttpHost() }}/{{ $url }}

Thank you,

{{ Config::get('app.name') }}
