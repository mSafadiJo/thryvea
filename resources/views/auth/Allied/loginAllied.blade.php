<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="{{ config('app.name', 'Laravel') }}" name="description"/>

    @include('include.include_css')
    @if( config('app.env', 'local') != "local" )
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script>
            function onSubmit(token) {
                document.getElementById("register-form").submit();
            }
        </script>
    @endif
</head>
<body class="bodyLogINS fontSFam">
<section class="newLogInBG">
    <div class="row">
        <div class="col-md-7">
            <div class="rightSection">

                <div class="logoContLog">
                    <img src="{{asset('images/Allied/thryveaLogin.png')}}" class="logoLogIn" alt="thryvea">
                </div>

                <div class="textToRight">
                    <h2>Welcome Back!</h2>
                    <p>Hello, in order to view your account details, please login using your username and password. For
                        assistance, please <a href="mailto:info@zone1remodeling.com">Click Here!</a></p>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-5">
            <div class="loginFieldsCont">
                <div class="topTextLogin">
                    <h2>Sign In</h2>
                </div>
                <div class="formCont">
                    <form id="register-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-12">
                                <i class="fa fa-user fa-2x icon" aria-hidden="true"></i>
                                <input class="form-control inputContTT" type="email" name="email" id="emailaddress"
                                       required="" placeholder="E-mail"
                                       value="@if( old('email')){{ old('email') }}@else{{ Cookie::get('PetraEmail') }}@endif">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <i class="fa fa-lock fa-3x icon" aria-hidden="true"></i>
                                <input class="form-control inputContTT" type="password" name="password"
                                       id="password" required="" placeholder="Password"
                                       value="@if(old('password')){{old('password')}}@else{{Cookie::get('PetraPassword')}}@endif">
                                <a class="linkShow">
                                    <i class="fa fa-eye fa-2x icon" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-12">
                                @foreach( $errors->all() as $error )
                                    <p class="errorText">
                                        {{ $error }}
                                    </p>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-block g-recaptcha logInButton"
                                        data-sitekey="{{ config('services.RECAPTCHA.RECAPTCHA_SITE_KEY', '') }}"
                                        data-callback="onSubmit">Sign In
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="checkbox checkbox-success">
                        <input type="checkbox" type="checkbox" name="remember" id="remember" checked>
                        <label class="passLabel" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="pull-right forgotPLOg"><small>Forgot
                                Password?</small></a>
                    @endif
                    <p class="noAccount">
                        Don't have an account? <a href="{{ route('register') }}"
                                                  class="signUpCo"><b>Create One!</b></a>
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>
@include('include.include_js')
@if(isset($data['input']))
    <script>
        $(document).ready(function () {
            $('#password').css("border-color", "#ff00008c");
            $('#emailaddress').css("border-color", "#ff00008c");
        });
    </script>
@endif
</body>
</html>
