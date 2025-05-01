<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="{{ config('app.name', 'Laravel') }}" name="description"/>
    <meta content="Farid-Naouri" name="author"/>

    @include('include.include_css')

</head>
<body>
<section class="formContainer1">
    <div class="formContainer resetContainer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 px-0">
                    <div class=" marginCont heightauto resetCont">
                        <div class="formCont text-center formBigScreen ResetForm">
                            <img class="logo-img1" src="{{asset('images/NewLogin/Group265.svg')}}">
                            <h2 class="dntWorryH2">Don't Worry!</h2>
                            <p>Enter Your Email Below And We'll Send You A Reset Link</p>
                            <form id="register-form" action="{{ route('password.email') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control inputContTT resetEmail" type="email" name="email" id="emailaddress"
                                               required="" placeholder="E-mail">
                                    </div>
                                    <div class="col-12">
                                        @if (Session::has('errors'))
                                            <p class="FailedReset">
                                                {{$errors->first()}}
                                            </p>
                                        @else
                                            <p class="SuccessReset">
                                                {{Session::get('status')}}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-block g-recaptcha logInButton resetButtomn" type="submit">
                                            Reset Password
                                        </button>
                                    </div>

                                </div>
                            </form>
                            <div class="col-sm-12 text-center">
                                <p class="backto noAccount">Back to  <a href="{{ route('login') }}" class="signUpCo"><b> Sign In</b></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 px-0">
                    <div class="marginContRight heightauto">
                        <div class="formContRight">
                            <img class='logo-img' src="{{asset('images/NewLogin/Group265.svg')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('include.include_js')

</body>
</html>
