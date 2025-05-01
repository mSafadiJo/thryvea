<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="{{ config('app.name', 'Laravel') }}" name="description" />

    @include('include.include_css')
    <style>
        #submit_hidden_form {
            background-color: white;
            color: white;
            border: none;
        }

        .account-box {
            max-width: unset;
        }
        .account-box .account-content {
            padding: 0 30px;
        }
        .account-logo-box {
            padding: 10px 30px 0;
        }
    </style>
    <!-- Plugins css -->
    @if( config('app.env', 'local') != "local" )
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script>
            function onSubmit(token) {
                document.getElementById("default-wizard").submit();
            }
        </script>
    @endif
</head>

<body class="fontSFam">
<section class="newRegisBG">
    <div class="container">
        <div class="logoContReg">
            <img src="{{asset('images/Allied/thryveaLogin.png')}}" class="logoLogREG" alt="zone1">
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="loginFieldsContRegister">
                    {{--                    <div class="topTextLogin">--}}
                    {{--                        <h2 class="dntWor">Don't Worry!</h2>--}}
                    {{--                        <p class="dntWorP">Enter Your Email Below And We'll Send You A Reset Link</p>--}}
                    {{--                    </div>--}}
                    <div class="formContRE">
                        <form id="default-wizard" action="{{ route('register') }}" method="POST">
                            @csrf
                            <fieldset title="1">
                                <legend>User <br/> Information</legend>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input type="text" class="form-control inputContTTRJ paddingInReg" id="firstname" name="firstname" placeholder="First Name" required=""
                                               value="{{ old('firstname') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control inputContTTRJ paddingInReg" id="lastname" name="lastname" placeholder="Last Name" required=""
                                               value="{{ old('lastname') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="email" class="form-control inputContTTRJ paddingInReg" id="email" name="email" placeholder="Email" required=""
                                               value="{{ old('email') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="password" class="form-control inputContTTRJ paddingInReg" id="password" name="password" placeholder="Password" required=""
                                               value="">
                                    </div>
                                    <div class="col-12">
                                        <input type="password" class="form-control inputContTTRJ paddingInReg" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required=""
                                               value="">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset title="2">
                                <legend>Business <br/>  Information</legend>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input type="text" class="form-control inputContTTRJ paddingInReg" id="owner" name="owner" placeholder="Business Owner" required=""
                                               value="{{ old('owner') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control inputContTTRJ paddingInReg" id="businessname" name="businessname" placeholder="Business Name" required=""
                                               value="{{ old('businessname') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="number" class="form-control inputContTTRJ paddingInReg" id="phonenumber" name="phonenumber" placeholder="Phone Number" required=""
                                               value="{{ old('phonenumber') }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="number" class="form-control inputContTTRJ paddingInReg" id="mobilenumber" name="mobilenumber" placeholder="Mobile Phone" required=""
                                               value="{{ old('mobilenumber') }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset title="3">
                                <legend>Address <br/>  Information</legend>
                                <div class="form-group row">
                                    <input type="hidden" value="{{ old('state') }}" id="State_id_aj"/>
                                    <input type="hidden" value="{{ old('city') }}" id="City_id_aj"/>
                                    <input type="hidden" value="{{ old('zipcode') }}" id="zipcode_aj"/>
                                    <div class="col-12">
                                        <select id="stateList" class="form-control inputContTTRJ" name="state" required data-placeholder="Choose State">
                                            <optgroup label="States">
                                                <option value="" disabled selected>Choose state</option>
                                                @foreach( \App\State::All() as $state )
                                                    @if( $state->state_id == old('state') )
                                                        <option value="{{ $state->state_id }}" selected>{{ $state->state_code }}</option>
                                                    @else
                                                        <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select class="form-control inputContTTRJ" id="cityList" name="city" required data-placeholder="Choose City">
                                            <option value="" disabled selected>Choose City</option>
                                            <optgroup label="Cities">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select class="form-control inputContTTRJ" id="zipcodeList" name="zipcode" required data-placeholder="Choose Zip Code">
                                            <option value="" disabled selected>Choose Zipcode</option>
                                            <optgroup label="ZipCodes">

                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control inputContTTRJ paddingInReg" id="streetname" name="streetname" required placeholder="Street Name"
                                               value="{{ old('streetname') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-12">
                                        {{--                                        <button type="button" class="btn btn-block REInButton stepy-finish" onclick="document.getElementById('submit_hidden_form').click();">--}}
                                        {{--                                            Create Account--}}
                                        {{--                                        </button>--}}

                                        <button type="button" class="btn btn-block REInButton stepy-finish">
                                            Create Account
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            {{--                            <fieldset title="4">--}}
                            {{--                                <legend>Promotional <br/>  Codes</legend>--}}
                            {{--                                <div class="form-group row">--}}
                            {{--                                    <div class="col-12">--}}
                            <input type="hidden" class="form-control inputContTTRJ paddingInReg" id="promocode" name="promocode" placeholder="Promo Code"
                                   value="{{ old('promocode') }}">
                            {{--                                    </div>--}}
                            {{--                                </div>--}}

                            {{--                            </fieldset>--}}
                            <div class="form-group row">
                                <div class="col-12">
                                    @foreach( $errors->all() as $error )
                                        <p class="errorText">
                                            {{ $error }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="g-recaptcha" id="submit_hidden_form"
                                    data-sitekey="{{ config('services.RECAPTCHA.RECAPTCHA_SITE_KEY', '') }}" data-callback="onSubmit"></button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <p class="noAccountReg">Already Have An Account? <a href="{{ route('login') }}" class="signUpCo"><b>Sign In</b></a>
                </p>
            </div>
        </div>
    </div>
</section>


@include('include.include_js')

</body>

</html>
