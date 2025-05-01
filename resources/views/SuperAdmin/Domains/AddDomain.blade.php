@extends('layouts.adminapp')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Domains</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title">Add Domain</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('AdminDomainStore') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Domain Name</label>
                                <input type="text" class="form-control" id="name" name="domain_name"  value="{{ old('domain_name') }}">
                                @error('domain_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="serviceType Type">Service Type<span class="requiredFields"></span></label>
                                <select class="form-control serviceType" name="service_type" id="serviceType">
                                    <option value="">Choose ...</option>
                                    <option value="1"
                                        @if( !empty(old('service_type')) )
                                            @if( old('service_type') == 1 )
                                                selected
                                            @endif
                                        @endif>Single Service</option>
                                    <option value="2"
                                        @if( !empty(old('service_type')) )
                                             @if( old('service_type') == 2 )
                                                selected
                                            @endif
                                        @endif>multiple Services</option>
                                    <option value="3"
                                        @if( !empty(old('service_type')) )
                                            @if( old('service_type') == 3 )
                                                selected
                                            @endif
                                        @endif>Ignostic Services</option>
                                </select>
                                @error('service_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @error('theme_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">

                                <label for="service_id Type">Service name<span class="requiredFields"></span></label>
                                <div class="service_idMultiple" style="display: none">
                                    <select id="service_id" class="select2 form-control" multiple name="service_id_multi[]" data-placeholder="Choose ...">
                                        <optgroup label="service_name">
                                            @foreach($service_campaigns as $Service)
                                                <option value="{{ $Service->service_campaign_id }}"
                                                    @if( !empty(old('service_id_multi')) )
                                                        @if( in_array($Service->service_campaign_id, old('service_id_multi')) )
                                                            selected
                                                        @endif
                                                    @endif>{{ $Service->service_campaign_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="service_idSingle">
                                    <select id="service_id" class="select form-control"name="service_id_single" data-placeholder="Choose ...">
                                        <optgroup label="service_name">
                                            <option value="" id="default">choose ...</option>
                                            @foreach($service_campaigns as $Service)
                                                <option value="{{ $Service->service_campaign_id }}"
                                                    @if( !empty(old('service_id_single')) )
                                                        @if( old('service_id_single') == $Service->service_campaign_id )
                                                            selected
                                                        @endif
                                                    @endif>{{ $Service->service_campaign_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--/// **This section will be fill up from jquery** ///--}}
                    <div class="form-group">
                        <label class="SelectThemeDomain" style="display: none" for="Select Theme">Select Theme<span class="SelectThemeDomain"></span></label>
                        <div class="col-sm-12 col-md-12">
                            <div class="themeImageSection">
                                <div class="row">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group card-box">
                        <h4 class="text-capitalize header-title">
                            options
                        </h4>
                        <div class="px-5 text-capitalize">
                            <input name="exit_popup" type="checkbox" class="form-check-input exit_pop" id="exit_pop" @if( !empty(old('exit_popup')) ) checked @endif>
                            <label for="exit_pop" class="exit_pop">exit popup</label><br>
                            <input name="lead_review" type="checkbox" class="form-check-input lead_review" id="lead_review" @if( !empty(old('lead_review')) ) checked @endif>
                            <label for="lead_review" class="lead_review">Lead Review</label><br>
                            <input name="join_network" type="checkbox" class="form-check-input join_network" id="join_network" @if( !empty(old('join_network')) ) checked @endif>
                            <label for="join_network" class="join_network">Join Our Network</label><br>
                            <input name="session_recording" type="checkbox" class="form-check-input session_recording" id="session_recording" @if( !empty(old('session_recording')) ) checked @endif>
                            <label for="session_recording" class="session_recording">session recording</label><br>
                            <div class="session_recoding_condition px-5 in-active">
                                <input type="radio" class="form-check-input session_recording_chech" id="all" name="all" value="all traffic source"
                                    @if( !empty(old('all')) )
                                        @if( old('all') == "all traffic source" )
                                            checked
                                        @endif
                                    @endif>
                                <label for="all" class="">For All Traffic Source</label><br>
                                <input type="radio" class="form-check-input session_recording_chech" id="ts1" name="all" value="according traffic source"
                                    @if( !empty(old('all')) )
                                       @if( old('all') == "according traffic source" )
                                           checked
                                        @endif
                                    @endif>
                                <label for="ts1" class="">Traffic Sourse</label><br>
                                <div class="session_recoding_TS in-active px-5 w-50">
                                    <select id="Select_TS" class="select2 form-control" multiple name="TS[]" data-placeholder="Choose ...">
                                        <optgroup label="Traffic Source">
                                            @foreach($traffic_sources as $item)
                                                <option value="{{ $item->id }}"
                                                    @if( !empty(old('TS')) )
                                                        @if( in_array($item->id, old('TS')) )
                                                            selected
                                                        @endif
                                                    @endif>{{ $item->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="w-50 px-5 text-capitalize second_service_section">
                            <input type="checkbox" class="form-check-input" id="second_service_checkbox" name="second_service_checkbox" @if( !empty(old('second_service_checkbox')) ) checked @endif>
                            <label for="second_service_checkbox" class="">Second Service</label><br>
                            <div class="second_service_select_1 in-active">
                                <select id="second_service_select" class="select2 form-control" multiple name="Second_service[]" data-placeholder="Choose ...">
                                    <optgroup label="Second Service">
                                        @foreach($service_campaigns as $item)
                                            <option value="{{ $item->service_campaign_id }}"
                                                @if( !empty(old('Second_service')) )
                                                    @if( in_array($item->service_campaign_id, old('Second_service')) )
                                                        selected
                                                    @endif
                                                @endif>{{ $item->service_campaign_name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group card-box">
                        <h4 class="text-capitalize header-title">
                            content
                        </h4>
                        <div class="row text-capitalize">
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12">
                                <p class="mt-3">main header:</p>
                                <input name="main_header" class="form-control w-100 rounded" value="{{ old('main_header') }}">
                                <p class="mt-3">main body:</p>
                                <textarea name="main_body" class="form-control w-100">{{ old('main_body') }}</textarea>
                            </div>
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12 text-capitalize">
                                <p class="mt-3">second header:</p>
                                <input name="second_header" class="form-control w-100" value="{{ old('second_header') }}">
                                <p class="mt-3">second body:</p>
                                <textarea name="second_body" class="form-control w-100">{{ old('second_body') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group card-box">
                        <h4 class="text-capitalize header-title">
                            SEO related
                        </h4>
                        <div class="row text-capitalize">
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12">
                                <p>Logo:</p>
                                <div class="input-group mb-2">
                                    <input type="file" name="logo" id="file-logo" onchange="previewFile('logo')">
                                    <img src="" class="in-active" width="100px" height="100px" id="img-logo">
                                </div>
                                <p>Icon:</p>
                                <div class="input-group mb-2">
                                    <input type="file" name="icon" onchange="previewFile('icon')" id="file-icon"><br>
                                    <img src="" class="in-active" width="100px" height="100px" id="img-icon">
                                </div>
                            </div>
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12 text-capitalize">
                                <div>
                                    <p>Background:</p>
                                    <input type="file" name="background" id="file-background" onchange="previewFile('background')">
                                    <img src="" class="in-active" width="100px" height="100px" id="img-background">
                                </div>
                                <div>
                                    <p class="mt-3">meta description:</p>
                                    <textarea name="meta" class="form-control w-100">{{ old('meta') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="addPixels" for="Select Theme">Add Pixels<span class=""></span></label>
                        <div class="col-sm-12 col-md-12">
                            <div class="PixelsSection">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 card-box">
                                        <h5 class="m-b-20">Facebook Pixel Setting</h5>
                                        <div class="form-group row">
                                            <label for="facebookpixels" class="col-sm-4 col-form-label">Facebook Pixels:</label>
                                            <div class="col-sm-6">
                                                <label class="facebookpixels switch">
                                                    <input type="checkbox" name="facebook_check" @if( !empty(old('facebook_check')) ) checked @endif>
                                                    <span class="slider round hide-off"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="facebook-pixels" class="col-sm-4 col-form-label">Facebook Pixels ID:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" @if( empty(old('facebook_check')) ) disabled="true" @endif id="FPixels" name="FPixels" value="{{ old('FPixels') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 card-box">
                                        <h5 class="m-b-20">Please be carefull when you are configuring Facebook pixel.</h5>
                                        <ul class="list-group mar-no">
                                            <li class="list-group-item text-dark">1. Log in to Facebook and go to your Ads Manager account.</li>
                                            <li class="list-group-item text-dark">2. Open the Navigation Bar and select Events Manager.</li>
                                            <li class="list-group-item text-dark">3. Copy your Pixel ID from underneath your Site Name and paste the number into Facebook Pixel ID field.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="PixelsSection">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 card-box">
                                        <div class="FirstSection">
                                            <h5 class="m-b-20">Google Tag Manager Setting</h5>
                                            <div class="form-group row">
                                                <label for="GoogleTagManager " class="col-sm-4 col-form-label">Google Tag Manager:</label>
                                                <div class="col-sm-6">
                                                    <label class="GoogleTagManager switch"><input type="checkbox" @if( !empty(old('GTM')) ) checked @endif><span class="slider round hide-off"></span></label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="GoogleTagManager" class="col-sm-4 col-form-label">Google Tag Manager ID:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" @if( empty(old('GTM')) ) disabled="true" @endif id="GPixelsId" name="GTM" value="{{ old('GTM') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <a id="addWithTs">Add With Ts</a>
                                    </div>
                                    <div class="col-sm-12 col-md-6 card-box">
                                        <h5 class="m-b-20">Please be carefull when you are configuring Google Tag Manager.</h5>
                                        <ul class="list-group mar-no">
                                            <li class="list-group-item text-dark">1. Log in to Google and go to your Google account.</li>
                                            <li class="list-group-item text-dark">2. find the GTM container ID located on the top right-hand side of the user interface.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success waves-effect waves-light pull-right">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            @foreach($errors->all() as $error)
                                <i>{{ $error }}</i>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
