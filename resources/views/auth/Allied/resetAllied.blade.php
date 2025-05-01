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
<body>
<section class="formContainer1">
    <div class="formContainer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 px-0">
                    <div class=" marginCont heightauto resetCont">
                        <div class="formCont text-center formBigScreen ResetForm ResetForm2">
                            <img class='logo-img1' src="{{asset('images/NewLogin/Group265.svg')}}">
                            <h2 class="dontwryReset">Don't Worry!</h2>
                            <p class="resetP">We're here to help you recover your password.</p>
                            <form id="register-form" action="{{ route('password.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control inputContTT resetEm" type="email" name="email" id="email" required="" placeholder="Enter your Email" value="{{ $email ?? old('email') }}">
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control inputContTT resetPass" type="password" name="password"
                                               id="password" required="" placeholder="Enter your new password"
                                               value="">
                                    </div>
                                    <div class="col-12">
                                        <input class="form-control inputContTT resetPassConfirm" type="password" name="password_confirmation"
                                               id="password1" required="" placeholder="Confirm your new password"
                                               value="">
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
                                        <button type="submit" class="btn btn-block logInButton saveReset" type="submit">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <p class="noAccount backto"><a href="{{ route('login') }}" class="signUpCo"><b>Sign In</b></a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 px-0">
                    <div class="marginContRight heightauto">
                        <div class="formContRight">
                            <img class='logo-img logo-reset' src="{{asset('images/NewLogin/Group265.svg')}}">
                        </div>
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
