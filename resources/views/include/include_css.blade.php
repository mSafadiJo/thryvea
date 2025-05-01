<!-- App favicon -->
<meta content="{{ config('app.name', 'Laravel') }}" name="description" />


<!-- App css -->
<link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/metismenu.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/style.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('css/custom-style.css') }}" rel="stylesheet" type="text/css" />

@if(Config::get('app.name') == 'Zone1Remodeling')
    <link rel="shortcut icon" href="{{ asset('images/iconBlack.png') }}">
    <link href="{{ URL::asset('css/zoneLogin.css') }}" rel="stylesheet" type="text/css" />
@else
    <link rel="shortcut icon" href="{{ asset('images/Allied/iconBlack.png') }}">
    <link href="{{ URL::asset('css/zoneLogin.css') }}" rel="stylesheet" type="text/css" />
@endif
{{--<link rel="stylesheet" href="{{ asset('css/style.default.css')}}" id="theme-stylesheet">--}}


<link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ URL::asset('plugins/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('plugins/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

<link href="{{ URL::asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
<!-- tags input-->

<link href="{{ URL::asset('plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css">

<link href="{{ URL::asset('plugins/magnific-popup/css/magnific-popup.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />

@if(Config::get('app.name') == 'Zone1Remodeling')
    @if( !empty(Auth::user()->role_id) )
        @if( Auth::user()->role_id != 1 && Auth::user()->role_id != 2 )
            <link href="{{ asset('css/zoneBuyersDashbord.css') }}" rel="stylesheet" type="text/css" />
        @endif
    @endif
@else
    @if( !empty(Auth::user()->role_id) )
        @if( Auth::user()->role_id != 1 && Auth::user()->role_id != 2 )
            <link href="{{ asset('css/buyersDashbord.css') }}" rel="stylesheet" type="text/css" />
        @endif
    @endif
@endif

@if( !empty(Auth::user()->role_id) )
    @if( Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )
        <link href="{{ asset('css/admin-custom-style.css') }}" rel="stylesheet" type="text/css" />
    @endif
@endif

<!-- map files -->
<link href="{{ asset('css/hot-map.css') }}" rel="stylesheet" type="text/css" />
