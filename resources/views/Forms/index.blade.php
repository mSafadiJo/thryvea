@extends('layouts.formsApp')

@section('content')
    <div class="row">
        <div class="col-12">
        <div class="card-box page-title-box">
            <div class="row">
                <div class="col-6">
                    <img src="{{ URL::asset('images/Allied/thryveaLogin.png') }}" width="200px"/>
                </div>
                <div class="col-lg-6">
                    <h4 class="header-title pull-right">Form System</h4>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('success');?>
                        @endif

                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger fade in alert-dismissible show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size:20px">×</span>
                                </button>
                                {{ $message }}
                            </div>
                            <?php Session::forget('error');?>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form class="form-horizontal" action="{{ route('Forms.Submit') }}" method="POST" id="submit_lead_virified_forms">
                            {{ csrf_field() }}

                            <h4>Contact Information</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="FirstName">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="{{ old('fname') }}" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="LastName">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="{{ old('lname') }}" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="Address">Address</label>
                                        <input type="text" class="form-control" id="street_name" name="street_name" value="{{ old('street_name') }}" placeholder="Address" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="City">City</label>
                                        <input id="cities-reports" style="width:100%;" placeholder="type a cities, scroll for more results" name="city_id" required/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="State">State</label>
                                        <select class="select2 form-control" name="state_id" id="statenamelead" data-placeholder="Choose ...">
                                            <optgroup label="States">
                                                @if( !empty($states) )
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="ZIPCode">ZIPCode</label>
                                        <input id="zipcodes-reports" style="width:100%;" placeholder="type a zipcodes, scroll for more results" name="zipcode_id" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="EmailAddress">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="PhoneNumber">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number" required>
                                    </div>
                                </div>
                            </div>
                            <h4>Home Information</h4>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="RoofShade">Type Of Service</label>
                                        <select class="select2 form-control" name="service_id" id="service_id_forms" data-placeholder="Choose ..." required>
                                            <optgroup label="Type Of Service">
                                                @if( !empty($services) )
                                                    @foreach($services as $item)
                                                        @if( empty(old('service_id')) )
                                                            <option value="{{ $item->service_campaign_id }}">{{ $item->service_campaign_name }}</option>
                                                        @else
                                                            @if( $item->service_campaign_id == old('service_id') )
                                                                <option value="{{ $item->service_campaign_id }}" selected>{{ $item->service_campaign_name }}</option>
                                                            @else
                                                                <option value="{{ $item->service_campaign_id }}">{{ $item->service_campaign_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 {{--Start Windows Service--}}
                                <div class="col-sm-6 c-windows-service">
                                    <div class="form-group">
                                        <label for="numberofwindows">Number of windows</label>
                                        <select class="select2 form-control" name="numberofwindows" id="numberofwindows" data-placeholder="Choose ...">
                                            <optgroup label="Number of windows">
                                                <option value=""></option>
                                                @if( !empty($numberOfWindows) )
                                                    @foreach($numberOfWindows as $item)
                                                        @if( empty(old('solor_solution')) )
                                                            <option value="{{ $item->number_of_windows_c_id }}">{{ $item->number_of_windows_c_type }}</option>
                                                        @else
                                                            @if( $item->number_of_windows_c_id == old('numberofwindows') )
                                                                <option value="{{ $item->number_of_windows_c_id }}" selected>{{ $item->number_of_windows_c_type }}</option>
                                                            @else
                                                                <option value="{{ $item->number_of_windows_c_id }}">{{ $item->number_of_windows_c_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Windows Service--}}

                                {{--=================================================================================================--}}

                                {{--Start Solar Service--}}
                                <div class="col-sm-6 c-solar-service">
                                    <div class="form-group">
                                        <label for="solor_solution">What kind of solar power solution are you looking for?</label>
                                        <select class="select2 form-control" name="solor_solution" id="solor_solution" data-placeholder="Choose ...">
                                            <optgroup label="What kind of solar power solution are you looking for?">
                                                @if( !empty($listOfsolor_solution) )
                                                    @foreach($listOfsolor_solution as $item)
                                                        @if( empty(old('solor_solution')) )
                                                            <option value="{{ $item->lead_solor_solution_list_id }}">{{ $item->lead_solor_solution_list_name }}</option>
                                                        @else
                                                            @if( $item->lead_solor_solution_list_id == old('solor_solution') )
                                                                <option value="{{ $item->lead_solor_solution_list_id }}" selected>{{ $item->lead_solor_solution_list_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_solor_solution_list_id }}">{{ $item->lead_solor_solution_list_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-solar-service">
                                    <div class="form-group">
                                        <label for="solor_sun">How much sun exposure does your property get?</label>
                                        <select class="select2 form-control" name="solor_sun" id="solor_sun" data-placeholder="Choose ...">
                                            <optgroup label="How much sun exposure does your property get?">
                                                @if( !empty($listOfsun_expouser) )
                                                    @foreach($listOfsun_expouser as $item)
                                                        @if( empty(old('solor_sun')) )
                                                            <option value="{{ $item->lead_solor_sun_expouser_list_id }}">{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                        @else
                                                            @if( $item->lead_solor_sun_expouser_list_id == old('solor_sun') )
                                                                <option value="{{ $item->lead_solor_sun_expouser_list_id }}" selected>{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_solor_sun_expouser_list_id }}">{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-solar-service">
                                    <div class="form-group">
                                        <label for="utility_provider">What is your current utility provider?</label>
                                        <select class="form-control" name="utility_provider" id="utility_provider" data-placeholder="Choose ...">
                                            <optgroup label="What is your current utility provider?">
                                                @if( !empty($listOfutility_provider) )
                                                    @foreach($listOfutility_provider as $item)
                                                        @if( empty(old('utility_provider')) )
                                                            <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_id }}">{{ $item->lead_current_utility_provider_name }}</option>
                                                        @else
                                                            @if( $item->lead_current_utility_provider_id == old('utility_provider') )
                                                                <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_id }}" selected>{{ $item->lead_current_utility_provider_name }}</option>
                                                            @else
                                                                <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_id }}">{{ $item->lead_current_utility_provider_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-solar-service">
                                    <div class="form-group">
                                        <label for="avg_money">What is your average monthly electricity bill?</label>
                                        <select class="select2 form-control" style="padding: 0.375rem 0.75rem !important;" name="avg_money" id="avg_money" data-placeholder="Choose ...">
                                            <optgroup label="What is your average monthly electricity bill?">
                                                @if( !empty($listOfAVGMoney) )
                                                    @foreach($listOfAVGMoney as $item)
                                                        @if( empty(old('avg_money')) )
                                                            <option value="{{ $item->lead_avg_money_electicity_list_id }}">{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                        @else
                                                            @if( $item->lead_avg_money_electicity_list_id == old('avg_money') )
                                                                <option value="{{ $item->lead_avg_money_electicity_list_id }}" selected>{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_avg_money_electicity_list_id }}">{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Solar Service--}}

                                {{--=================================================================================================--}}

                                {{--Start Home Security Service--}}
                                <div class="col-sm-6 c-homesecurity-service">
                                    <div class="form-group">
                                        <label for="installation_preferences">Installation Preferences</label>
                                        <select class="select2 form-control" name="installation_preferences" id="installation_preferences" data-placeholder="Choose ...">
                                            <optgroup label="Installation Preferences">
                                                @if( !empty($listOfinstallation_preferences) )
                                                    @foreach($listOfinstallation_preferences as $item)
                                                        @if( empty(old('installation_preferences')) )
                                                            <option value="{{ $item->lead_installation_preferences_id }}">{{ $item->lead_installation_preferences_name }}</option>
                                                        @else
                                                            @if( $item->lead_installation_preferences_id == old('installation_preferences') )
                                                                <option value="{{ $item->lead_installation_preferences_id }}" selected>{{ $item->lead_installation_preferences_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_installation_preferences_id }}">{{ $item->lead_installation_preferences_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-homesecurity-service">
                                    <div class="form-group">
                                        <label for="lead_have_item_before_it">Do You Have An Existing Alarm And/ Or Monitoring System?</label>
                                        <select class="select2 form-control" name="lead_have_item_before_it" id="lead_have_item_before_it" data-placeholder="Choose ...">
                                            <optgroup label="Do You Have An Existing Alarm And/ Or Monitoring System?">
                                                <option value="1" @if(old('lead_have_item_before_it') == 1) selected @endif>Yes</option>
                                                <option value="0" @if(old('lead_have_item_before_it') == 0) selected @endif>No</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Home Security Service--}}
                                {{--=================================================================================================--}}

                                {{--Start Flooring Service--}}
                                <div class="col-sm-6 c-flooring-service">
                                    <div class="form-group">
                                        <label for="type_of_flooring">What type of flooring are you interested in?</label>
                                        <select class="select2 form-control" name="type_of_flooring" id="type_of_flooring" data-placeholder="Choose ...">
                                            <optgroup label="What type of flooring are you interested in?">
                                                @if( !empty($listOflead_type_of_flooring) )
                                                    @foreach($listOflead_type_of_flooring as $item)
                                                        @if( empty(old('type_of_flooring')) )
                                                            <option value="{{ $item->lead_type_of_flooring_id }}">{{ $item->lead_type_of_flooring_name }}</option>
                                                        @else
                                                            @if( $item->lead_type_of_flooring_id == old('type_of_flooring') )
                                                                <option value="{{ $item->lead_type_of_flooring_id }}" selected>{{ $item->lead_type_of_flooring_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_type_of_flooring_id }}">{{ $item->lead_type_of_flooring_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-flooring-service">
                                    <div class="form-group">
                                        <label for="nature_flooring_project">What is the nature of your flooring project?</label>
                                        <select class="select2 form-control" name="nature_flooring_project" id="nature_flooring_project" data-placeholder="Choose ...">
                                            <optgroup label="What is the nature of your flooring project?">
                                                @if( !empty($listOflead_nature_flooring_project) )
                                                    @foreach($listOflead_nature_flooring_project as $item)
                                                        @if( empty(old('nature_flooring_project')) )
                                                            <option value="{{ $item->lead_nature_flooring_project_id }}">{{ $item->lead_nature_flooring_project_name }}</option>
                                                        @else
                                                            @if( $item->lead_nature_flooring_project_id == old('nature_flooring_project') )
                                                                <option value="{{ $item->lead_nature_flooring_project_id }}" selected>{{ $item->lead_nature_flooring_project_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_nature_flooring_project_id }}">{{ $item->lead_nature_flooring_project_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Flooring Service--}}
                                {{--=================================================================================================--}}

                                {{--Start Walk In Tubs Service--}}
                                <div class="col-sm-6 c-walkintubs-service">
                                    <div class="form-group">
                                        <label for="walk_in_tub">Why Do You Want A Walk-In Tub?</label>
                                        <select class="select2 form-control" name="walk_in_tub" id="walk_in_tub" data-placeholder="Choose ...">
                                            <optgroup label="Why Do You Want A Walk-In Tub?">
                                                @if( !empty($listOflead_walk_in_tub) )
                                                    @foreach($listOflead_walk_in_tub as $item)
                                                        @if( empty(old('walk_in_tub')) )
                                                            <option value="{{ $item->lead_walk_in_tub_id }}">{{ $item->lead_walk_in_tub_name }}</option>
                                                        @else
                                                            @if( $item->lead_walk_in_tub_id == old('walk_in_tub') )
                                                                <option value="{{ $item->lead_walk_in_tub_id }}" selected>{{ $item->lead_walk_in_tub_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_walk_in_tub_id }}">{{ $item->lead_walk_in_tub_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-walkintubs-service">
                                    <div class="form-group">
                                        <label for="desired_featuers">What Are The Desired Features?</label>
                                        <select class="select2 form-control select2-multiple" multiple name="desired_featuers[]" id="desired_featuers" data-placeholder="Choose ...">
                                            <optgroup label="What Are The Desired Features?">
                                                @if( !empty($listOflead_desired_featuers) )
                                                    @foreach($listOflead_desired_featuers as $item)
                                                        <option value="{{ $item->lead_desired_featuers_id }}">{{ $item->lead_desired_featuers_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Walk In Tubs Service--}}
                                {{--=================================================================================================--}}

                                {{--Start Roofing Service--}}
                                <div class="col-sm-6 c-roofing-service">
                                    <div class="form-group">
                                        <label for="type_of_roofing">What type of roofing are you interested in?</label>
                                        <select class="select2 form-control" name="type_of_roofing" id="type_of_roofing" data-placeholder="Choose ...">
                                            <optgroup label="What type of roofing are you interested in?">
                                                @if( !empty($listOflead_type_of_roofings) )
                                                    @foreach($listOflead_type_of_roofings as $item)
                                                        @if( empty(old('type_of_roofing')) )
                                                            <option value="{{ $item->lead_type_of_roofing_id }}">{{ $item->lead_type_of_roofing_name }}</option>
                                                        @else
                                                            @if( $item->lead_type_of_roofing_id == old('type_of_roofing') )
                                                                <option value="{{ $item->lead_type_of_roofing_id }}" selected>{{ $item->lead_type_of_roofing_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_type_of_roofing_id }}">{{ $item->lead_type_of_roofing_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 c-roofing-service">
                                    <div class="form-group">
                                        <label for="nature_of_roofing">What is the nature of your roofing project?</label>
                                        <select class="select2 form-control" name="nature_of_roofing" id="nature_of_roofing" data-placeholder="Choose ...">
                                            <optgroup label="What is the nature of your roofing project?">
                                                @if( !empty($listOflead_nature_of_roofings) )
                                                    @foreach($listOflead_nature_of_roofings as $item)
                                                        @if( empty(old('nature_of_roofing')) )
                                                            <option value="{{ $item->lead_nature_of_roofing_id }}">{{ $item->lead_nature_of_roofing_name }}</option>
                                                        @else
                                                            @if( $item->lead_nature_of_roofing_id == old('nature_of_roofing') )
                                                                <option value="{{ $item->lead_nature_of_roofing_id }}" selected>{{ $item->lead_nature_of_roofing_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_nature_of_roofing_id }}">{{ $item->lead_nature_of_roofing_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 c-roofing-service c-Sunrooms-service">
                                    <div class="form-group">
                                        <label for="property_type_roofing">What is the property type?</label>
                                        <select class="select2 form-control" name="property_type_roofing" id="property_type_roofing" data-placeholder="Choose ...">
                                            <optgroup label="What is the property type?">
                                                @if( !empty($listOflead_property_type_roofings) )
                                                    @foreach($listOflead_property_type_roofings as $item)
                                                        @if( empty(old('property_type_roofing')) )
                                                            <option value="{{ $item->lead_property_type_roofing_id }}">{{ $item->lead_property_type_roofing_name }}</option>
                                                        @else
                                                            @if( $item->lead_property_type_roofing_id == old('property_type_roofing') )
                                                                <option value="{{ $item->lead_property_type_roofing_id }}" selected>{{ $item->lead_property_type_roofing_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_property_type_roofing_id }}">{{ $item->lead_property_type_roofing_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Roofing Service--}}
                                {{--=================================================================================================--}}

                                {{--Start Home Siding Service--}}
                                <div class="col-sm-6 c-homeSiding-service">
                                    <div class="form-group">
                                        <label for="type_of_siding">What type of siding are you interested in?</label>
                                        <select class="select2 form-control" name="type_of_siding" id="type_of_siding" data-placeholder="Choose ...">
                                            <optgroup label="What type of siding are you interested in?">
                                                @if( !empty($type_of_siding_leads) )
                                                    @foreach($type_of_siding_leads as $item)
                                                        @if( empty(old('type_of_siding')) )
                                                            <option value="{{ $item->type_of_siding_lead_id }}">{{ $item->type_of_siding_lead_type }}</option>
                                                        @else
                                                            @if( $item->type_of_siding_lead_id == old('type_of_siding') )
                                                                <option value="{{ $item->type_of_siding_lead_id }}" selected>{{ $item->type_of_siding_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->type_of_siding_lead_id }}">{{ $item->type_of_siding_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-homeSiding-service">
                                    <div class="form-group">
                                        <label for="nature_of_siding">What is the nature of your siding project?</label>
                                        <select class="select2 form-control" name="nature_of_siding" id="nature_of_siding" data-placeholder="Choose ...">
                                            <optgroup label="What is the nature of your siding project?">
                                                @if( !empty($nature_of_siding_leads) )
                                                    @foreach($nature_of_siding_leads as $item)
                                                        @if( empty(old('nature_of_siding')) )
                                                            <option value="{{ $item->nature_of_siding_lead_id }}">{{ $item->nature_of_siding_lead_type }}</option>
                                                        @else
                                                            @if( $item->nature_of_siding_lead_id == old('nature_of_siding') )
                                                                <option value="{{ $item->nature_of_siding_lead_id }}" selected>{{ $item->nature_of_siding_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->nature_of_siding_lead_id }}">{{ $item->nature_of_siding_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Home Siding Service--}}

                                {{--Start kitchen Service--}}
                                <div class="col-sm-6 c-kitchen-service">
                                    <div class="form-group">
                                        <label for="service_kitchen">What service are you interested in?</label>
                                        <select class="select2 form-control" name="service_kitchen" id="service_kitchen" data-placeholder="Choose ...">
                                            <optgroup label="What service are you interested in?">
                                                @if( !empty($service_kitchen_leads) )
                                                    @foreach($service_kitchen_leads as $item)
                                                        @if( empty(old('service_kitchen')) )
                                                            <option value="{{ $item->service_kitchen_lead_id }}">{{ $item->service_kitchen_lead_type }}</option>
                                                        @else
                                                            @if( $item->service_kitchen_lead_id == old('service_kitchen') )
                                                                <option value="{{ $item->service_kitchen_lead_id }}" selected>{{ $item->service_kitchen_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->service_kitchen_lead_id }}">{{ $item->service_kitchen_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 c-kitchen-service">
                                    <div class="form-group">
                                        <label for="removing_adding_walls">Does your kitchen remodel require removing or adding any walls?</label>
                                        <select class="select2 form-control" name="removing_adding_walls" id="removing_adding_walls" data-placeholder="Choose ...">
                                            <optgroup label="Does your kitchen remodel require removing or adding any walls?">
                                                <option value="1" @if(old('removing_adding_walls') == 1) selected @endif>Yes</option>
                                                <option value="0" @if(old('removing_adding_walls') == 0) selected @endif>No</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End kitchen Service--}}

                                {{--Start Bathroom Service--}}
                                <div class="col-sm-6 c-Bathroom-service">
                                    <div class="form-group">
                                        <label for="bathroom_type">What type of Bathroom project are you interested in?</label>
                                        <select class="select2 form-control" name="bathroom_type" id="bathroom_type" data-placeholder="Choose ...">
                                            <optgroup label="What type of Bathroom project are you interested in?">
                                                @if( !empty($campaign_bathroomtypes) )
                                                    @foreach($campaign_bathroomtypes as $item)
                                                        @if( empty(old('bathroom_type')) )
                                                            <option value="{{ $item->campaign_bathroomtype_id }}">{{ $item->campaign_bathroomtype_type }}</option>
                                                        @else
                                                            @if( $item->campaign_bathroomtype_id == old('bathroom_type') )
                                                                <option value="{{ $item->campaign_bathroomtype_id }}" selected>{{ $item->campaign_bathroomtype_type }}</option>
                                                            @else
                                                                <option value="{{ $item->campaign_bathroomtype_id }}">{{ $item->campaign_bathroomtype_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Bathroom Service--}}

                                {{--Start Stairlifts Service--}}
                                <div class="col-sm-6 c-Stairlifts-service">
                                    <div class="form-group">
                                        <label for="stairs_type">What type of stairs?</label>
                                        <select class="select2 form-control" name="stairs_type" id="stairs_type" data-placeholder="Choose ...">
                                            <optgroup label="What type of stairs?">
                                                @if( !empty($stairs_type_leads) )
                                                    @foreach($stairs_type_leads as $item)
                                                        @if( empty(old('stairs_type')) )
                                                            <option value="{{ $item->stairs_type_lead_id }}">{{ $item->stairs_type_lead_type }}</option>
                                                        @else
                                                            @if( $item->stairs_type_lead_id == old('stairs_type') )
                                                                <option value="{{ $item->stairs_type_lead_id }}" selected>{{ $item->stairs_type_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->stairs_type_lead_id }}">{{ $item->stairs_type_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Stairlifts-service">
                                    <div class="form-group">
                                        <label for="stairs_reason">What is the reason that you are looking for stair lift?</label>
                                        <select class="select2 form-control" name="stairs_reason" id="stairs_reason" data-placeholder="Choose ...">
                                            <optgroup label="What is the reason that you are looking for stair lift?">
                                                @if( !empty($stairs_reason_leads) )
                                                    @foreach($stairs_reason_leads as $item)
                                                        @if( empty(old('stairs_reason')) )
                                                            <option value="{{ $item->stairs_reason_lead_id }}">{{ $item->stairs_reason_lead_type }}</option>
                                                        @else
                                                            @if( $item->stairs_reason_lead_id == old('stairs_reason') )
                                                                <option value="{{ $item->stairs_reason_lead_id }}" selected>{{ $item->stairs_reason_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->stairs_reason_lead_id }}">{{ $item->stairs_reason_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Stairlifts Service--}}

                                {{--Start Furnace/Boiler Service--}}
                                <div class="col-sm-6 c-Furnace-service">
                                    <div class="form-group">
                                        <label for="stairs_reason">What type of central heating system do you want?</label>
                                        <select class="select2 form-control" name="furnance_type" id="furnance_type" data-placeholder="Choose ...">
                                            <optgroup label="What type of central heating system do you want?">
                                                @if( !empty($furnance_type_leads) )
                                                    @foreach($furnance_type_leads as $item)
                                                        @if( empty(old('furnance_type')) )
                                                            <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                        @else
                                                            @if( $item->furnance_type_lead_id == old('furnance_type') )
                                                                <option value="{{ $item->furnance_type_lead_id }}" selected>{{ $item->furnance_type_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Furnace/Boiler Service--}}

                                {{--Start Plumbing Service--}}
                                <div class="col-sm-6 c-Plumbing-service">
                                    <div class="form-group">
                                        <label for="plumbing_service">What kind of service do you need?</label>
                                        <select class="select2 form-control" name="plumbing_service" id="plumbing_service" data-placeholder="Choose ...">
                                            <optgroup label="What kind of service do you need?">
                                                @if( !empty($plumbing_service_lists) )
                                                    @foreach($plumbing_service_lists as $item)
                                                        @if( empty(old('plumbing_service')) )
                                                            <option value="{{ $item->plumbing_service_list_id }}">{{ $item->plumbing_service_list_type }}</option>
                                                        @else
                                                            @if( $item->plumbing_service_list_id == old('plumbing_service') )
                                                                <option value="{{ $item->plumbing_service_list_id }}" selected>{{ $item->plumbing_service_list_type }}</option>
                                                            @else
                                                                <option value="{{ $item->plumbing_service_list_id }}">{{ $item->plumbing_service_list_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Plumbing Service--}}

                                {{--Start Sunrooms Service--}}
                                <div class="col-sm-6 c-Sunrooms-service">
                                    <div class="form-group">
                                        <label for="sunroom_service">What best describes the project?</label>
                                        <select class="select2 form-control" name="sunroom_service" id="sunroom_service" data-placeholder="Choose ...">
                                            <optgroup label="What best describes the project?">
                                                @if( !empty($sunroom_service_leads) )
                                                    @foreach($sunroom_service_leads as $item)
                                                        @if( empty(old('sunroom_service')) )
                                                            <option value="{{ $item->sunroom_service_lead_id }}">{{ $item->sunroom_service_lead_type }}</option>
                                                        @else
                                                            @if( $item->sunroom_service_lead_id == old('sunroom_service') )
                                                                <option value="{{ $item->sunroom_service_lead_id }}" selected>{{ $item->sunroom_service_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->sunroom_service_lead_id }}">{{ $item->sunroom_service_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Sunrooms Service--}}

                                {{--Start Handyman Service--}}
                                <div class="col-sm-6 c-Handyman-service">
                                    <div class="form-group">
                                        <label for="handyman_ammount">Describe the amount of work you need to have done?</label>
                                        <select class="select2 form-control" name="handyman_ammount" id="handyman_ammount" data-placeholder="Choose ...">
                                            <optgroup label="Describe the amount of work you need to have done?">
                                                @if( !empty($handyman_ammount_works) )
                                                    @foreach($handyman_ammount_works as $item)
                                                        @if( empty(old('handyman_ammount')) )
                                                            <option value="{{ $item->handyman_ammount_work_id }}">{{ $item->handyman_ammount_work_type }}</option>
                                                        @else
                                                            @if( $item->handyman_ammount_work_id == old('handyman_ammount') )
                                                                <option value="{{ $item->handyman_ammount_work_id }}" selected>{{ $item->handyman_ammount_work_type }}</option>
                                                            @else
                                                                <option value="{{ $item->handyman_ammount_work_id }}">{{ $item->handyman_ammount_work_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Handyman Service--}}

                                {{--Start Countertops Service--}}
                                <div class="col-sm-6 c-Countertops-service">
                                    <div class="form-group">
                                        <label for="countertops_service">What type of countertop material do you want?</label>
                                        <select class="select2 form-control" name="countertops_service" id="countertops_service" data-placeholder="Choose ...">
                                            <optgroup label="What type of countertop material do you want?">
                                                @if( !empty($countertops_service_leads) )
                                                    @foreach($countertops_service_leads as $item)
                                                        @if( empty(old('countertops_service')) )
                                                            <option value="{{ $item->countertops_service_lead_id }}">{{ $item->countertops_service_lead_type }}</option>
                                                        @else
                                                            @if( $item->countertops_service_lead_id == old('countertops_service') )
                                                                <option value="{{ $item->countertops_service_lead_id }}" selected>{{ $item->countertops_service_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->countertops_service_lead_id }}">{{ $item->countertops_service_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Countertops Service--}}

                                {{--Start Doors Service--}}
                                <div class="col-sm-6 c-Doors-service">
                                    <div class="form-group">
                                        <label for="door_typeproject">What type of door project is this?</label>
                                        <select class="select2 form-control" name="door_typeproject" id="door_typeproject" data-placeholder="Choose ...">
                                            <optgroup label="What type of door project is this?">
                                                @if( !empty($door_typeproject_leads) )
                                                    @foreach($door_typeproject_leads as $item)
                                                        @if( empty(old('door_typeproject')) )
                                                            <option value="{{ $item->door_typeproject_lead_id }}">{{ $item->door_typeproject_lead_type }}</option>
                                                        @else
                                                            @if( $item->door_typeproject_lead_id == old('door_typeproject') )
                                                                <option value="{{ $item->door_typeproject_lead_id }}" selected>{{ $item->door_typeproject_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->door_typeproject_lead_id }}">{{ $item->door_typeproject_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Doors-service">
                                    <div class="form-group">
                                        <label for="number_of_door">How many doors are included in this project?</label>
                                        <select class="select2 form-control" name="number_of_door" id="number_of_door" data-placeholder="Choose ...">
                                            <optgroup label="How many doors are included in this project?">
                                                @if( !empty($number_of_door_leads) )
                                                    @foreach($number_of_door_leads as $item)
                                                        @if( empty(old('number_of_door')) )
                                                            <option value="{{ $item->number_of_door_lead_id }}">{{ $item->number_of_door_lead_type }}</option>
                                                        @else
                                                            @if( $item->number_of_door_lead_id == old('number_of_door') )
                                                                <option value="{{ $item->number_of_door_lead_id }}" selected>{{ $item->number_of_door_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->number_of_door_lead_id }}">{{ $item->number_of_door_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Doors Service--}}

                                {{--Start Gutter Service--}}
                                <div class="col-sm-6 c-Gutter-service">
                                    <div class="form-group">
                                        <label for="gutters_meterial">Select the type of Gutter material you want:</label>
                                        <select class="select2 form-control" name="gutters_meterial" id="gutters_meterial" data-placeholder="Choose ...">
                                            <optgroup label="Select the type of Gutter material you want:">
                                                @if( !empty($gutters_meterial_leads) )
                                                    @foreach($gutters_meterial_leads as $item)
                                                        @if( empty(old('gutters_meterial')) )
                                                            <option value="{{ $item->gutters_meterial_lead_id }}">{{ $item->gutters_meterial_lead_type }}</option>
                                                        @else
                                                            @if( $item->gutters_meterial_lead_id == old('gutters_meterial') )
                                                                <option value="{{ $item->gutters_meterial_lead_id }}" selected>{{ $item->gutters_meterial_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->gutters_meterial_lead_id }}">{{ $item->gutters_meterial_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Gutter Service--}}

                                {{--Start Paving Service--}}
                                <div class="col-sm-6 c-Paving-service">
                                    <div class="form-group">
                                        <label for="paving_service">What service are you interested in?</label>
                                        <select class="select2 form-control" name="paving_service" id="paving_service" data-placeholder="Choose ...">
                                            <optgroup label="What service are you interested in?">
                                                <option value=""></option>
                                                @if( !empty($paving_service_lead) )
                                                    @foreach($paving_service_lead as $item)
                                                        @if( empty(old('paving_service')) )
                                                            <option value="{{ $item->paving_service_lead_id }}">{{ $item->paving_service_lead_type }}</option>
                                                        @else
                                                            @if( $item->paving_service_lead_id == old('paving_service') )
                                                                <option value="{{ $item->paving_service_lead_id }}" selected>{{ $item->paving_service_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->paving_service_lead_id }}">{{ $item->paving_service_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                 {{--service 1 --}}
                                <div class="col-sm-6 c-Paving-service c-Paving1-service">
                                    <div class="form-group">
                                        <label for="paving_asphalt_type">What best describes the area needing asphalt?</label>
                                        <select class="select2 form-control" name="paving_asphalt_type" id="paving_asphalt_type" data-placeholder="Choose ...">
                                            <optgroup label="What best describes the area needing asphalt?">
                                                @if( !empty($paving_asphalt_type) )
                                                    @foreach($paving_asphalt_type as $item)
                                                        @if( empty(old('paving_asphalt_type')) )
                                                            <option value="{{ $item->paving_asphalt_type_id }}">{{ $item->paving_asphalt_type }}</option>
                                                        @else
                                                            @if( $item->paving_asphalt_type_id == old('paving_asphalt_type') )
                                                                <option value="{{ $item->paving_asphalt_type_id }}" selected>{{ $item->paving_asphalt_type }}</option>
                                                            @else
                                                                <option value="{{ $item->paving_asphalt_type_id }}">{{ $item->paving_asphalt_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                 {{--service 3 --}}
                                <div class="col-sm-6 c-Paving-service c-Paving3-service">
                                    <div class="form-group">
                                        <label for="paving_loose_fill_type">What type of loose fill do you want?</label>
                                        <select class="select2 form-control" name="paving_loose_fill_type" id="paving_loose_fill_type" data-placeholder="Choose ...">
                                            <optgroup label="What type of loose fill do you want?">
                                                @if( !empty($paving_loose_fill_type) )
                                                    @foreach($paving_loose_fill_type as $item)
                                                        @if( empty(old('paving_loose_fill_type')) )
                                                            <option value="{{ $item->paving_loose_fill_type_id }}">{{ $item->paving_loose_fill_type }}</option>
                                                        @else
                                                            @if( $item->paving_loose_fill_type_id == old('paving_loose_fill_type') )
                                                                <option value="{{ $item->paving_loose_fill_type_id }}" selected>{{ $item->paving_loose_fill_type }}</option>
                                                            @else
                                                                <option value="{{ $item->paving_loose_fill_type_id }}">{{ $item->paving_loose_fill_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 c-Paving-service">
                                    <div class="form-group">
                                        <label for="paving_best_describes_priject">What best describes this project?</label>
                                        <select class="select2 form-control" name="paving_best_describes_priject" id="paving_best_describes_priject" data-placeholder="Choose ...">
                                            <optgroup label="What best describes this project?">
                                                @if( !empty($paving_best_describes_priject) )
                                                    @foreach($paving_best_describes_priject as $item)
                                                        @if( empty(old('paving_best_describes_priject')) )
                                                            <option value="{{ $item->paving_best_describes_priject_id }}">{{ $item->paving_best_describes_priject_type }}</option>
                                                        @else
                                                            @if( $item->paving_best_describes_priject_id == old('paving_best_describes_priject') )
                                                                <option value="{{ $item->paving_best_describes_priject_id }}" selected>{{ $item->paving_best_describes_priject_type }}</option>
                                                            @else
                                                                <option value="{{ $item->paving_best_describes_priject_id }}">{{ $item->paving_best_describes_priject_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Paving Service--}}

                                {{--Start Painting Service--}}
                                <div class="col-sm-6 c-Painting-service">
                                    <div class="form-group">
                                        <label for="painting_service">What service are you interested in?</label>
                                        <select class="select2 form-control" name="painting_service" id="painting_service" data-placeholder="Choose ...">
                                            <optgroup label="What service are you interested in?">
                                                @if( !empty($painting_service_lead) )
                                                    @foreach($painting_service_lead as $item)
                                                        @if( empty(old('painting_service')) )
                                                            <option value="{{ $item->painting_service_lead_id }}">{{ $item->painting_service_lead_type }}</option>
                                                        @else
                                                            @if( $item->painting_service_lead_id == old('painting_service') )
                                                                <option value="{{ $item->painting_service_lead_id }}" selected>{{ $item->painting_service_lead_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting_service_lead_id }}">{{ $item->painting_service_lead_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                {{--Painting 1 --}}
                                <div class="col-sm-6 c-Painting-service c-Painting1-service">
                                    <div class="form-group">
                                        <label for="painting1_typeof_project">What type of project is this?</label>
                                        <select class="select2 form-control" name="painting1_typeof_project" id="painting1_typeof_project" data-placeholder="Choose ...">
                                            <optgroup label="What type of project is this?">
                                                @if( !empty($painting1_typeof_project) )
                                                    @foreach($painting1_typeof_project as $item)
                                                        @if( empty(old('painting1_typeof_project')) )
                                                            <option value="{{ $item->painting1_typeof_project_id }}">{{ $item->painting1_typeof_project_type }}</option>
                                                        @else
                                                            @if( $item->painting1_typeof_project_id == old('painting1_typeof_project') )
                                                                <option value="{{ $item->painting1_typeof_project_id }}" selected>{{ $item->painting1_typeof_project_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting1_typeof_project_id }}">{{ $item->painting1_typeof_project_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Painting-service c-Painting1-service c-Painting4-service">
                                    <div class="form-group">
                                        <label for="painting1_stories">How many stories is your home?</label>
                                        <select class="select2 form-control" name="painting1_stories" id="painting1_stories" data-placeholder="Choose ...">
                                            <optgroup label="How many stories is your home?">
                                                @if( !empty($painting1_stories_number) )
                                                    @foreach($painting1_stories_number as $item)
                                                        @if( empty(old('painting1_typeof_project')) )
                                                            <option value="{{ $item->painting1_stories_number_id }}">{{ $item->painting1_stories_number_type }}</option>
                                                        @else
                                                            @if( $item->painting1_stories_number_id == old('painting1_typeof_project') )
                                                                <option value="{{ $item->painting1_stories_number_id }}" selected>{{ $item->painting1_stories_number_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting1_stories_number_id }}">{{ $item->painting1_stories_number_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Painting-service c-Painting1-service">
                                    <div class="form-group">
                                        <label for="painting1_kindsof_surfaces">What kinds of surfaces need to be painted and/or stained?</label>
                                        <select class="select2 form-control" name="painting1_kindsof_surfaces" id="painting1_kindsof_surfaces" data-placeholder="Choose ...">
                                            <optgroup label="What kinds of surfaces need to be painted and/or stained?">
                                                @if( !empty($painting1_kindsof_surfaces) )
                                                    @foreach($painting1_kindsof_surfaces as $item)
                                                        @if( empty(old('painting1_kindsof_surfaces')) )
                                                            <option value="{{ $item->painting1_kindsof_surfaces_id }}">{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                        @else
                                                            @if( $item->painting1_kindsof_surfaces_id == old('painting1_kindsof_surfaces') )
                                                                <option value="{{ $item->painting1_kindsof_surfaces_id }}" selected>{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting1_kindsof_surfaces_id }}">{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                {{--Painting 2 --}}
                                <div class="col-sm-6 c-Painting-service c-Painting1-service c-Painting2-service c-Painting3-service c-Painting4-service">
                                    <div class="form-group">
                                        <label for="interior_historical2">Is this location a historical structure?</label>
                                        <select class="select2 form-control" name="interior_historical2" id="interior_historical2" data-placeholder="Choose ...">
                                            <optgroup label="Is this location a historical structure?">
                                                <option value="1" @if(old('interior_historical2') == 1) selected @endif>Yes</option>
                                                <option value="2" @if(old('interior_historical2') == 2) selected @endif>No</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-6 c-Painting-service c-Painting2-service">
                                    <div class="form-group">
                                        <label for="painting2_rooms_number">How many rooms or areas need to be painted?</label>
                                        <select class="select2 form-control" name="painting2_rooms_number" id="painting2_rooms_number" data-placeholder="Choose ...">
                                            <optgroup label="How many rooms or areas need to be painted?">
                                                @if( !empty($painting2_rooms_number) )
                                                    @foreach($painting2_rooms_number as $item)
                                                        @if( empty(old('painting2_rooms_number')) )
                                                            <option value="{{ $item->painting2_rooms_number_id }}">{{ $item->painting2_rooms_number_type }}</option>
                                                        @else
                                                            @if( $item->painting2_rooms_number_id == old('painting2_rooms_number') )
                                                                <option value="{{ $item->painting2_rooms_number_id }}" selected>{{ $item->painting2_rooms_number_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting2_rooms_number_id }}">{{ $item->painting2_rooms_number_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Painting-service c-Painting2-service">
                                    <div class="form-group">
                                        <label for="painting2_typeof_paint">What needs to be painted?</label>
                                        <select class="select2 form-control" name="painting2_typeof_paint" id="painting2_typeof_paint" data-placeholder="Choose ...">
                                            <optgroup label="What needs to be painted?">
                                                @if( !empty($painting2_typeof_paint) )
                                                    @foreach($painting2_typeof_paint as $item)
                                                        @if( empty(old('painting2_typeof_paint')) )
                                                            <option value="{{ $item->painting2_typeof_paint_id }}">{{ $item->painting2_typeof_paint_type }}</option>
                                                        @else
                                                            @if( $item->painting2_typeof_paint_id == old('painting2_typeof_paint') )
                                                                <option value="{{ $item->painting2_typeof_paint_id }}" selected>{{ $item->painting2_typeof_paint_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting2_typeof_paint_id }}">{{ $item->painting2_typeof_paint_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                {{--Painting 3 --}}
                                <div class="col-sm-6 c-Painting-service c-Painting3-service">
                                    <div class="form-group">
                                        <label for="painting3_each_feature">Check off each feature that will need to be painted/stained. (Check all that apply)</label>
                                        <select class="select2 form-control select2-multiple" multiple name="painting3_each_feature[]" id="painting3_each_feature" data-placeholder="Choose ...">
                                            <optgroup label="Check off each feature that will need to be painted/stained. (Check all that apply)">
                                                @if( !empty($painting3_each_feature) )
                                                    @foreach($painting3_each_feature as $item)
                                                        <option value="{{ $item->painting3_each_feature_id }}">{{ $item->painting3_each_feature_type }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                {{--Painting 4 --}}
                                <div class="col-sm-6 c-Painting-service c-Painting4-service">
                                    <div class="form-group">
                                        <label for="painting4_existing_roof">What best describes the condition of the existing roof?</label>
                                        <select class="select2 form-control select-multiple" multiple name="painting4_existing_roof[]" id="painting4_existing_roof" data-placeholder="Choose ...">
                                            <optgroup label="What best describes the condition of the existing roof?">
                                                @if( !empty($painting4_existing_roof) )
                                                    @foreach($painting4_existing_roof as $item)
                                                        <option value="{{ $item->painting4_existing_roof_id }}">{{ $item->painting4_existing_roof_type }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                {{--Painting 5 --}}
                                <div class="col-sm-6 c-Painting-service c-Painting5-service">
                                    <div class="form-group">
                                        <label for="painting5_kindof_texturing">What kind of texturing do you need?</label>
                                        <select class="select2 form-control select2-multiple" multiple name="painting5_kindof_texturing[]" id="painting5_kindof_texturing" data-placeholder="Choose ...">
                                            <optgroup label="What kind of texturing do you need?">
                                                @if( !empty($painting5_kindof_texturing) )
                                                    @foreach($painting5_kindof_texturing as $item)
                                                        <option value="{{ $item->painting5_kindof_texturing_id }}">{{ $item->painting5_kindof_texturing_type }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-Painting-service c-Painting5-service">
                                    <div class="form-group">
                                        <label for="painting5_surfaces_textured">What surfaces need to be textured?</label>
                                        <select class="select2 form-control" name="painting5_surfaces_textured" id="painting5_surfaces_textured" data-placeholder="Choose ...">
                                            <optgroup label="What surfaces need to be textured?">
                                                @if( !empty($painting5_surfaces_textured) )
                                                    @foreach($painting5_surfaces_textured as $item)
                                                        @if( empty(old('painting5_surfaces_textured')) )
                                                            <option value="{{ $item->painting5_surfaces_textured_id }}">{{ $item->painting5_surfaces_textured_type }}</option>
                                                        @else
                                                            @if( $item->painting5_surfaces_textured_id == old('painting5_surfaces_textured') )
                                                                <option value="{{ $item->painting5_surfaces_textured_id }}" selected>{{ $item->painting5_surfaces_textured_type }}</option>
                                                            @else
                                                                <option value="{{ $item->painting5_surfaces_textured_id }}">{{ $item->painting5_surfaces_textured_type }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--=================================================================================================--}}
                                {{--End Painting Service--}}


                                {{--Start Main Service--}}
                                <div class="col-sm-6 c-main-property_type_c-service">
                                    <div class="form-group">
                                        <label for="property_type_c">Property Type</label>
                                        <select class="select2 form-control" name="property_type_c" id="property_type_c" data-placeholder="Choose ...">
                                            <optgroup label="Property Type">
                                                @if( !empty($listOfproperty) )
                                                    @foreach($listOfproperty as $item)
                                                        @if( empty(old('property_type_c')) )
                                                            <option value="{{ $item->property_type_campaign_id }}">{{ $item->property_type_campaign }}</option>
                                                        @else
                                                            @if( $item->property_type_campaign_id == old('property_type_c') )
                                                                <option value="{{ $item->property_type_campaign_id }}" selected>{{ $item->property_type_campaign }}</option>
                                                            @else
                                                                <option value="{{ $item->property_type_campaign_id }}">{{ $item->property_type_campaign }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-main-priority-service">
                                    <div class="form-group">
                                        <label for="priority">Time to start project?</label>
                                        <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                            <optgroup label="Time to start project?">
                                                @if( !empty($lead_prioritys) )
                                                    @foreach($lead_prioritys as $item)
                                                        @if( empty(old('priority')) )
                                                            <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                        @else
                                                            @if( $item->lead_priority_id == old('priority') )
                                                                <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                            @else
                                                                <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-main-projectnature-service">
                                    <div class="form-group">
                                        <label for="projectnature">What is the nature of your project?</label>
                                        <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                            <optgroup label="What is the nature of your project?">
                                                @if( !empty($campain_inistallings) )
                                                    @foreach($campain_inistallings as $item)
                                                        @if( empty(old('projectnature')) )
                                                            <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                        @else
                                                            @if( $item->installing_type_campaign_id == old('projectnature') )
                                                                <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                            @else
                                                                <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 c-main-OwnRented-service">
                                    <div class="form-group">
                                        <label for="ownership">Do you own your home?</label>
                                        <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                            <optgroup label="Do you own your home?">
                                                <option value="1" @if(old('ownership') == 1) selected @endif>Yes</option>
                                                <option value="2" @if(old('ownership') == 2) selected @endif>No</option>
                                                <option value="3" @if(old('ownership') == 3) selected @endif>No, But Authorized to Make Changes</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                {{--End Main Service--}}
                            </div>
                            <!--Jornaya Tolken Input-->
                            <input id="leadid_token" name="universal_leadid" type="hidden" value=""/>
                            <!--End Jornaya Tolken Input-->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary form-control waves-effect waves-light pull-right" style="width: 25%;" id="submit_lead_virified_forms_buttons">submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted" id="pErrorsShown">
                                    @foreach( $errors->all() as $error )
                                        {{ $error }}<br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
