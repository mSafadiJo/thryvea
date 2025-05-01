<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="{{ config('app.name', 'Laravel') }}" name="description" />
    <meta content="Farid-Naouri" name="author" />
    @include('include.include_css')
</head>
<body class="fontSFam">
<section class="newResetBG">
    <div class="container">
        <div class="logoContLog">
            <img src="{{asset('images/zone1logo.svg')}}" class="logoLogIn" alt="zone 1 Remodeling">
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="topTextLoginReset">
                    <h2>Don't Worry!</h2>
                    <p>We're Here To Help You Recover Your Password!</p>
                </div>

                <div class="ResetFieldsCont">
                    <div class="formCont">
                        <form id="register-form" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group row">
                                <div class="col-12">
                                    <input class="form-control inputContTTRJ" type="email" name="email" id="email" required="" placeholder="Enter your Email" value="{{ $email ?? old('email') }}">
                                </div>
                                <div class="col-12">
                                    <input class="form-control inputContTTRJ" type="password" name="password"
                                           id="password" required="" placeholder="Enter your new password"
                                           value="">
                                    <a class="linkShow">
                                        <i class="fa fa-eye fa-eyeRes fa-2x icon" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-12">
                                    <input class="form-control inputContTTRJ" type="password" name="password_confirmation"
                                           id="password1" required="" placeholder="Confirm your new password"
                                           value="">
                                    <a class="linkShowP2">
                                        <i class="fa fa-eye fa-eyeRes fa-2x icon" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-12">
                                    @foreach( $errors->all() as $error )
                                        <p class="errorText">
                                            {{ $error }}<br>
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-block ResetButton" type="submit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p class="noAccount"><a href="{{ route('login') }}" class="signUpRE"><b>Sign In</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('include.include_js')
<script src="{{ asset('js/AllJs.js') }}"></script>
</body>
</html>
