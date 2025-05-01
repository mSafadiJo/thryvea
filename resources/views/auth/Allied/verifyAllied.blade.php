<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="{{ config('app.name', 'Laravel') }}" name="description" />

    @include('include.include_css')

</head>
<body>
<section class="formContainer1 verifyBlur">
    <div class="newLogInBG verifySec formContainer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6 px-0">
                    <div class="leftContentVerify">
                       <div class="loginFieldsCont">
                            <div class="topTextLogin">
                                <img class='logo-img logo-img1 Verify-logo' src="{{asset('images/NewLogin/Group265.svg')}}">
                                <h2>Thank You For Your Registration</h2>
                            </div>
                            <div class="formContRE">
                                <svg class="d-block m-auto" version="1.1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 98 98"
                                     style="enable-background:new 0 0 98 98;height: 140px;" xml:space="preserve">
                                                        <style type="text/css">
                                                            .st0{fill:none;}
                                                            .st1{fill:#FFFFFF;}
                                                            .st2{fill:#2c73a0;stroke:#FFFFFF;stroke-width:2;stroke-miterlimit:10;}
                                                            .st3{fill:none;stroke:#2c73a0;stroke-width:2;stroke-linecap:round;stroke-miterlimit:10;}
                                                        </style>
                                    <g i:extraneous="self">
                                        <circle id="XMLID_50_" class="st0" cx="49" cy="49" r="49"/>
                                        <g id="XMLID_4_">
                                            <path id="XMLID_49_" class="st1" d="M77.3,42.7V77c0,0.6-0.4,1-1,1H21.7c-0.5,0-1-0.5-1-1V42.7c0-0.3,0.1-0.6,0.4-0.8l27.3-21.7
                                                                        c0.3-0.3,0.8-0.3,1.2,0l27.3,21.7C77.1,42.1,77.3,42.4,77.3,42.7z"/>
                                            <path id="XMLID_48_" class="st2" d="M66.5,69.5h-35c-1.1,0-2-0.9-2-2V26.8c0-1.1,0.9-2,2-2h35c1.1,0,2,0.9,2,2v40.7
                                                                        C68.5,68.6,67.6,69.5,66.5,69.5z"/>
                                            <path id="XMLID_47_" class="st1" d="M62.9,33.4H47.2c-0.5,0-0.9-0.4-0.9-0.9v-0.2c0-0.5,0.4-0.9,0.9-0.9h15.7
                                                                        c0.5,0,0.9,0.4,0.9,0.9v0.2C63.8,33,63.4,33.4,62.9,33.4z"/>
                                            <path id="XMLID_46_" class="st1" d="M62.9,40.3H47.2c-0.5,0-0.9-0.4-0.9-0.9v-0.2c0-0.5,0.4-0.9,0.9-0.9h15.7
                                                                        c0.5,0,0.9,0.4,0.9,0.9v0.2C63.8,39.9,63.4,40.3,62.9,40.3z"/>
                                            <path id="XMLID_45_" class="st1" d="M62.9,47.2H47.2c-0.5,0-0.9-0.4-0.9-0.9v-0.2c0-0.5,0.4-0.9,0.9-0.9h15.7
                                                                        c0.5,0,0.9,0.4,0.9,0.9v0.2C63.8,46.8,63.4,47.2,62.9,47.2z"/>
                                            <path id="XMLID_44_" class="st1" d="M62.9,54.1H47.2c-0.5,0-0.9-0.4-0.9-0.9v-0.2c0-0.5,0.4-0.9,0.9-0.9h15.7
                                                                        c0.5,0,0.9,0.4,0.9,0.9v0.2C63.8,53.7,63.4,54.1,62.9,54.1z"/>
                                            <path id="XMLID_43_" class="st2" d="M41.6,40.1h-5.8c-0.6,0-1-0.4-1-1v-6.7c0-0.6,0.4-1,1-1h5.8c0.6,0,1,0.4,1,1v6.7
                                                                        C42.6,39.7,42.2,40.1,41.6,40.1z"/>
                                            <path id="XMLID_42_" class="st2" d="M41.6,54.2h-5.8c-0.6,0-1-0.4-1-1v-6.7c0-0.6,0.4-1,1-1h5.8c0.6,0,1,0.4,1,1v6.7
                                                                        C42.6,53.8,42.2,54.2,41.6,54.2z"/>
                                            <path id="XMLID_41_" class="st1" d="M23.4,46.2l25,17.8c0.3,0.2,0.7,0.2,1.1,0l26.8-19.8l-3.3,30.9H27.7L23.4,46.2z"/>
                                            <path id="XMLID_40_" class="st3" d="M74.9,45.2L49.5,63.5c-0.3,0.2-0.7,0.2-1.1,0L23.2,45.2"/>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                           <div class="row">
                               <div class="col-sm-12 text-center">
                                   <p class="VerifyP"> <b>{{ __('Verify Your Email Address Before proceeding,') }}</b>
                                       {{ __(' please check your email for a verification link.') }}
                                       {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                                   </p>
                                   <p class="SuccessReset">
                                       @if (session('resent'))
                                           {{ __('A fresh verification link has been sent to your email address.') }}
                                       @endif
                                   </p>
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 px-0">
                    <div class="marginContRight heightauto rightContentVerify">
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
