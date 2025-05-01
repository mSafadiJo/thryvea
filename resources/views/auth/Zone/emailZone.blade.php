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


<body class="fontSFam">
<section class="newResetBG">
    <div class="container">
        <div class="logoContLog">
            <img src="{{asset('images/zone1logo.svg')}}" class="logoRest" alt="Zone1Remodeling">
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="ResetFieldsCont">
                    <div class="topTextLoginReset">
                        <h2>Don't Worry!</h2>
                        <p>Enter Your Email Below And We'll Send You A Reset Link</p>
                    </div>
                    <div class="formCont">
                        <form id="register-form" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
{{--                                    <i class="fa fa-envelope-open fa-2x icon" aria-hidden="true"></i>--}}
                                    <input class="form-control inputContTTRJ" type="email" name="email" id="emailaddress"
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
                                    <button type="submit" class="btn btn-block g-recaptcha ResetButton wdthSiM" type="submit">
                                        Reset Password
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p class="noAccountReset">Back to <a href="{{ route('login') }}" class="ResetCo"><b>Sign In</b></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

@include('include.include_css')

</body>
</html>
