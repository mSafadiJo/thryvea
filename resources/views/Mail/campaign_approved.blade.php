Hi {{ $name }},

Please approve this new campaign: {{ $campaign }}.

https://{{ $_SERVER['SERVER_NAME'] }}/{{ $url }}

Thank you,

{{ Config::get('app.name') }}
