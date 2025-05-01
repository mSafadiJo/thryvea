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
                        <h4 class="header-title">Edit Domain</h4>
                    </div>
                </div>
                <form class="form-horizontal" action="{{ route('Edit_Domain_save') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><span class="requiredFields"></span>Domain Name</label>
                                <input type="text" class="form-control" id="name" name="name"  value="{{ $domain[0]->domain_name }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="Service Type">Service Type<span class="requiredFields"></span></label>
                                <select class="form-control serviceType" name="service_type" id="serviceType">
                                    <option value="">Choose ...</option>
                                    @if($domain[0]->Service_type == 1)
                                        <option value="1" selected>Single Service</option>
                                        <option value="2">multiple Services</option>
                                        <option value="3">Ignostic Services</option>
                                    @elseif($domain[0]->Service_type == 2)
                                        <option value="1">Single Service</option>
                                        <option value="2" selected>multiple Services</option>
                                        <option value="3">Ignostic Services</option>
                                    @elseif($domain[0]->Service_type == 3)
                                        <option value="1">Single Service</option>
                                        <option value="2">multiple Services</option>
                                        <option value="3" selected>Ignostic Services</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="Service Type">Service name<span class="requiredFields"></span></label>
                                <div class="service_idMultiple" style="display: none">
                                    <select id="service_id" class="select2 form-control" multiple name="service_id_multi[]" data-placeholder="Choose ...">
                                        <optgroup label="service_name">
                                            <option value="" id="default">choose ...</option>
                                            @foreach($services as $Service)
                                                @if(collect($choosed_services_before)->where('service_id', $Service->service_campaign_id)->count() == 1)
                                                    <option value="{{ $Service->service_campaign_id }}" selected>{{ $Service->service_campaign_name }}</option>
                                                @else
                                                    <option value="{{ $Service->service_campaign_id }}">{{ $Service->service_campaign_name }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="service_idSingle">
                                    <select id="service_id" class="select form-control"name="service_id_single" data-placeholder="Choose ...">
                                        <optgroup label="service_name">
                                            <option value="" id="default">choose ...</option>
                                            @foreach($services as $Service)
                                                @if(collect($choosed_services_before)->where('service_id', $Service->service_campaign_id)->count() == 1)
                                                    <option value="{{ $Service->service_campaign_id }}" selected>{{ $Service->service_campaign_name }}</option>
                                                @else
                                                    <option value="{{ $Service->service_campaign_id }}">{{ $Service->service_campaign_name }}</option>
                                                @endif
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
                                <input class="selected-theme" hidden value="{{$domain[0]->theme_id}}"/>
                                <input class="domain-id" hidden value="{{$domain[0]->id}}" name="domain_id"/>
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

                            @if($domain[0]->exit_popup == 1)
                                <input name="exit_popup" type="checkbox" class="form-check-input exit_pop" id="exit_pop" checked="true">
                                <label for="exit_pop" class="exit_pop">exit popup</label><br>
                            @else
                                @if($domain[0]->Service_type != 2)
                                    <input name="exit_popup" type="checkbox" class="form-check-input exit_pop" id="exit_pop">
                                    <label for="exit_pop" class="exit_pop">exit popup</label><br>
                                @endif
                            @endif
                            @if($domain[0]->lead_review == 1)
                                <input name="lead_review" type="checkbox" class="form-check-input lead_review" id="lead_review" checked="true">
                                <label for="lead_review" class="lead_review">Lead Review</label><br>
                            @else
                                <input name="laeder_view" type="checkbox" class="form-check-input lead_review" id="lead_review">
                                <label for="lead_review" class="lead_review">Lead Review</label><br>
                            @endif
                            @if($domain[0]->join_network == 1)
                                    <input name="join_network" type="checkbox" class="form-check-input lead_review" id="join_network" checked="true">
                                    <label for="join_network" class="join_network">Join Our Network</label><br>
                            @else
                                    <input name="join_network" type="checkbox" class="form-check-input lead_review" id="join_network">
                                    <label for="join_network" class="join_network">Join Our Network</label><br>
                            @endif
                            @if($domain[0]->session_recording == 1)
                                <input name="session_recording" type="checkbox" class="form-check-input session_recording" id="session_recording" checked="true">
                                <label for="session_recording" class="session_recording">session recording</label><br>
                            @else
                                <input name="session_recording" type="checkbox" class="form-check-input session_recording" id="session_recording">
                                <label for="session_recording" class="session_recording">session recording</label><br>
                            @endif
                            <div class="session_recoding_condition px-5 in-active">
                                @if($domain[0]->session_recoding_option == "all traffic source")
                                    <input type="radio" class="form-check-input session_recording_chech" id="all" name="all" value="all traffic source" checked>
                                    <label for="all" class="">for all</label><br>
                                @else
                                    <input type="radio" class="form-check-input session_recording_chech" id="all" name="all" value="all traffic source" >
                                    <label for="all" class="">for all</label><br>
                                @endif
                                @if($domain[0]->session_recoding_option == "according traffic source")
                                    <input type="radio" class="form-check-input session_recording_chech" id="ts1" name="all" value="according traffic source" checked>
                                    <label for="ts1" class="">Ts</label><br>
                                @else
                                    <input type="radio" class="form-check-input session_recording_chech" id="ts1" name="all" value="according traffic source">
                                    <label for="ts1" class="">Ts</label><br>
                                @endif
                                <div class="session_recoding_TS in-active px-5 w-50">
                                    <select id="Select_TS" class="select2 form-control" multiple name="TS[]" data-placeholder="Choose ...">
                                        <optgroup label="Traffic Source">
                                            @foreach($traffic as $item)
                                                @if(in_array($item->id, collect(json_decode($domain[0]->traffic_source_selected))->values()->toArray()))
                                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                @else
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="w-50 px-5 text-capitalize second_service_section">
                            @if($domain[0]->second_service)
                                <input type="checkbox" class="form-check-input" id="second_service_checkbox" name="second_service_checkbox" checked>
                                <label for="second_service_checkbox" class="">Second Service</label><br>
                            @else
                                <input type="checkbox" class="form-check-input" id="second_service_checkbox" name="second_service_checkbox">
                                <label for="second_service_checkbox" class="">Second Service</label><br>
                            @endif
                            <div class="second_service_select_1 in-active">
                                <select id="second_service_select" class="select2 form-control" multiple name="Second_service[]" data-placeholder="Choose ...">
                                    <optgroup label="Second Service">
                                        @foreach($services as $item)
                                            @if(!empty($domain[0]->second_service))
                                                @if(in_array($item->service_campaign_id, json_decode($domain[0]->second_service)))
                                                    <option value="{{ $item->service_campaign_id }}" selected>{{ $item->service_campaign_name }}</option>
                                                @else
                                                    <option value="{{ $item->service_campaign_id }}">{{ $item->service_campaign_name }}</option>
                                                @endif
                                            @else
                                                <option value="{{ $item->service_campaign_id }}">{{ $item->service_campaign_name }}</option>
                                            @endif
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
                                <p>main header:</p>
                                <input name="main_header" class="form-control w-100 rounded" value="{{ !empty($contents->main_header)?$contents->main_header: ''  }}">
                                <p class="mt-3">main body:</p>
                                <textarea name="main_body" class="form-control w-100">{{ !empty($contents->main_body)?$contents->main_body:'' }}</textarea>
                            </div>
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12 text-capitalize">
                                <p>second header:</p>
                                <input name="second_header" class="form-control w-100" value="{{ !empty($contents->second_header)?$contents->second_header: '' }}">
                                <p class="mt-3">second body:</p>
                                <textarea name="second_body" class="form-control w-100">{{ !empty($contents->second_body)?$contents->second_body:''  }}</textarea>
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
                                    @if(isset($domain[0]->logo))
                                        <img src="{{ $domain[0]->logo }}" width="100px" height="100px" id="img-logo">
                                    @else
                                        <img src="" class="in-active" width="100px" height="100px" id="img-logo">
                                    @endif
                                </div>
                                <p>Icon:</p>
                                <div class="input-group mb-2">
                                    <input type="file" name="icon" onchange="previewFile('icon')" id="file-icon"><br>
                                    @if(isset($domain[0]->icon))
                                        <img src="{{ $domain[0]->icon }}" width="100px" height="100px" id="img-icon">
                                    @else
                                        <img src="" class="in-active" width="100px" height="100px" id="img-icon">
                                    @endif
                                </div>
                            </div>
                            <div class="px-5 font-weight-light col-lg-6 col-sm-12 col-md-12 text-capitalize">
                                <div>
                                    <p>Background:</p>
                                    <input type="file" name="background" id="file-background" onchange="previewFile('background')">
                                    @if(isset($domain[0]->background))
                                        <img src="{{ $domain[0]->background }}" width="100px" height="100px" id="img-background">
                                    @else
                                        <img src="" class="in-active" width="100px" height="100px" id="img-background">
                                    @endif
                                </div>
                                <div>
                                    <p class="mt-5">meta description:</p>
                                    @if(isset($domain[0]->meta_description))
                                        <textarea name="meta" class="form-control w-100">{{ $domain[0]->meta_description }}</textarea>
                                    @else
                                        <textarea name="meta" class="form-control w-100"></textarea>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="addPixels" for="Select Theme">Edit Pixels<span class=""></span></label>
                        <div class="col-sm-12 col-md-12">
                            <div class="PixelsSection">
                                <div class="row">
                                    @if(isset($pixel_facebook))
                                        <div class="col-sm-12 col-md-6 card-box">
                                            <h5 class="m-b-20">Facebook Pixel Setting</h5>
                                            <div class="form-group row">
                                                <label for="facebookpixels" class="col-sm-4 col-form-label">Facebook Pixels:</label>
                                                <div class="col-sm-6">
                                                    <label class="facebookpixels switch">
                                                        <input name="facebook_check" type="checkbox" checked>
                                                        <span class="slider round hide-off"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="facebook-pixels" class="col-sm-4 col-form-label">Facebook Pixels ID:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" id="FPixels" name="FPixels" value="{{$pixel_facebook->pixels_name}}">
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-sm-12 col-md-6 card-box">
                                            <h5 class="m-b-20">Facebook Pixel Setting</h5>
                                            <div class="form-group row">
                                                <label for="facebookpixels" class="col-sm-4 col-form-label">Facebook Pixels:</label>
                                                <div class="col-sm-6">
                                                    <label class="facebookpixels switch">
                                                        <input name="facebook_check" type="checkbox">
                                                        <span class="slider round hide-off"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="facebook-pixels" class="col-sm-4 col-form-label">Facebook Pixels ID:</label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" disabled="true" id="FPixels" name="FPixels" value="">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                            @if(isset($pixel_google))
                                                <div class="form-group row">
                                                    <label for="GoogleTagManager " class="col-sm-4 col-form-label">Google Tag Manager:</label>
                                                    <div class="col-sm-6">
                                                        <label class="GoogleTagManager switch">
                                                            @if(count($pixel_google) > 0)
                                                                <input type="checkbox" checked name="google_check">
                                                                <span class="slider round hide-off"></span>
                                                            @else
                                                                <input type="checkbox" name="google_check">
                                                                <span class="slider round hide-off"></span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="GoogleTagManager" class="col-sm-4 col-form-label">Google Tag Manager ID:</label>
                                                    <div class="col-sm-6">
                                                        @if(isset($pixel_google[0]))
                                                            <input type="text" class="form-control" id="GPixelsId" name="GTM" value="{{ $pixel_google[0]->pixels_name }}">
                                                        @else
                                                            <input type="text" class="form-control" id="GPixelsId" name="GTM" value="" disabled="true">
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group row">
                                                    <label for="GoogleTagManager " class="col-sm-4 col-form-label">Google Tag Manager:</label>
                                                    <div class="col-sm-6">
                                                        <label class="GoogleTagManager switch">
                                                            <input type="checkbox" name="google_check">
                                                            <span class="slider round hide-off"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        <label for="GoogleTagManager" class="col-form-label">Google Tag Manager ID:</label>
                                                    </div>
                                                </div>
                                            @endif
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
                                <button type="submit" class="btn btn-success waves-effect waves-light pull-right">Edit</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- End row -->
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-muted">
                            @foreach( $errors->all() as $error )
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 5-->
@endsection
