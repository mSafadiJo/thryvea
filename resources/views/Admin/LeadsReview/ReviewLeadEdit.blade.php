@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Review Lead Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <form id="" action="{{ route('UpdateLeadReview') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Edit Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="lead_id" name="lead_id" value="{{ $leadReviews->lead_id }}" >
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="{{ $leadReviews->lead_fname }}" >
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="{{ $leadReviews->lead_lname }}" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ $leadReviews->lead_email }}" >
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ $leadReviews->lead_phone_number }}" >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Address</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="state">State<span class=""></span></label>
                            <input type="hidden" value="{{ old('state') }}" id="State_id_aj"/>
                            <input type="hidden" value="{{ old('city') }}" id="City_id_aj"/>
                            <input type="hidden" value="{{ old('zipcode') }}" id="zipcode_aj"/>
                            <select class="select2 form-control select2-hidden-accessibled" id="stateListLeadReview" name="state" required="" data-placeholder="Choose ..." tabindex="-1" aria-hidden="true">
                                <optgroup label="States">
                                    <option>--Choose State--</option>
                                    <?php
                                    if ( !empty($states) ){
                                        foreach( $states as $state ){
                                            ?>
                                        <option value="<?php echo $state->state_id; ?>"
                                        <?php
                                            if( $state->state_id === $leadReviews->state_id )   {
                                                echo "selected";
                                            }
                                            ?>
                                        >{{ $state->state_code }}</option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </optgroup>
                            </select>


                        </div>
                    </div>
                    <?php
                    $city_arr = explode('=>', $leadReviews->city_name);
                    $county_arr = explode('=>', $leadReviews->county_name);
                    ?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="countyID" value="{{ $leadReviews->county_id }}">
                            <label for="County">County<span class=""></span></label>
                            <select class="select2 form-control" id="countyListLeadReview" name="County" required data-placeholder="Choose ...">
                                <optgroup label="Cities">

                                </optgroup>
                            </select>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="CityID" value="{{ $leadReviews->city_id }}">
                            <label for="city">City<span class=""></span></label>
                            <select class="select2 form-control" id="cityListLeadReview" name="city" required data-placeholder="Choose ...">
                                <optgroup label="Cities">

                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="Street">Street Address</label>
                            <input type="text" class="form-control" name="Street" id="Street" value="{{ $leadReviews->lead_address }}" >
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="zipcodeID" value="{{$leadReviews->zip_code_list }}">
                            <label for="zipcode">Zip-Code<span class=""></span></label>
                            <select class="select2 form-control" id="zipcodeListLeadReview" name="zipcode" required data-placeholder="Choose ...">
                                <optgroup label="ZipCodes">

                                </optgroup>
                            </select>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label for="ZipCode">ZipCode</label>--}}
                            {{--<input type="text" class="form-control" id="ZipCode" value="{{ $leadReviews->zip_code_list }}" readonly>--}}
                        {{--</div>--}}
                    </div>
                </div>

                @if($leadReviews->is_multi_service == 0)
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Lead Details</h6>
                        </div>
                    </div>

                    @if( $leadReviews->lead_type_service_id == 1 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of the project?</label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Priority">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="Priority" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Ownership">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="Ownership" value="@if( $leadReviews->lead_ownership == 1 ) Yes @else No @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberofwindows">Number of windows</label>
                                    <select class="select2 form-control" name="numberofwindows" id="numberofwindows" data-placeholder="Choose ...">
                                        <optgroup label="Number of windows">
                                            <option value=""></option>
                                            @if( !empty($numberOfWindows) )
                                                @foreach($numberOfWindows as $item)
                                                    @if( $item->number_of_windows_c_type == $leadReviews->number_of_windows_c_type )
                                                        <option value="{{ $item->number_of_windows_c_id }}" selected>{{ $item->number_of_windows_c_type }}</option>
                                                    @else
                                                        <option value="{{ $item->number_of_windows_c_id }}">{{ $item->number_of_windows_c_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 2 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of the project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_solor_solution_list_name }}" readonly>--}}
                                    <select class="select2 form-control" name="solor_solution" id="solor_solution" data-placeholder="Choose ...">
                                        <optgroup label="What kind of solar power solution are you looking for?">
                                            @if( !empty($listOfsolor_solution) )
                                                @foreach($listOfsolor_solution as $item)
                                                    @if( $item->lead_solor_solution_list_name == $leadReviews->lead_solor_solution_list_name )
                                                        <option value="{{ $item->lead_solor_solution_list_id }}" selected>{{ $item->lead_solor_solution_list_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_solor_solution_list_id }}">{{ $item->lead_solor_solution_list_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Property's sun exposure</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_solor_sun_expouser_list_name }}" readonly>--}}
                                    <select class="select2 form-control" name="solor_sun" id="solor_sun" data-placeholder="Choose ...">
                                        <optgroup label="How much sun exposure does your property get?">
                                            @if( !empty($listOfsun_expouser) )
                                                @foreach($listOfsun_expouser as $item)
                                                    @if( $item->lead_solor_sun_expouser_list_name == $leadReviews->lead_solor_sun_expouser_list_name )
                                                        <option value="{{ $item->lead_solor_sun_expouser_list_id }}" selected>{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_solor_sun_expouser_list_id }}">{{ $item->lead_solor_sun_expouser_list_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">What is the current utility provider for the customer?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_current_utility_provider_id }}" readonly>--}}
                                    <select class="form-control" name="utility_provider" id="utility_provider" data-placeholder="Choose ...">
                                        <optgroup label="What is your current utility provider?">
                                            @if(!empty($listOfutility_provider))
                                                @foreach($listOfutility_provider as $item)
                                                    <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_name }}"
                                                            @if( $item->lead_current_utility_provider_name == $leadReviews->lead_current_utility_provider_id ) selected @endif
                                                    >{{ $item->lead_current_utility_provider_name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What is the average monthly electricity bill for the customer?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_avg_money_electicity_list_name }}" readonly>--}}
                                    <select class="select2 form-control" style="padding: 0.375rem 0.75rem !important;" name="avg_money" id="avg_money" data-placeholder="Choose ...">
                                        <optgroup label="What is your average monthly electricity bill?">
                                            @if( !empty($listOfAVGMoney) )
                                                @foreach($listOfAVGMoney as $item)
                                                    @if( $item->lead_avg_money_electicity_list_name == $leadReviews->lead_avg_money_electicity_list_name )
                                                        <option value="{{ $item->lead_avg_money_electicity_list_id }}" selected>{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_avg_money_electicity_list_id }}">{{ $item->lead_avg_money_electicity_list_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Property Type</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->property_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="property_type_c" id="property_type_c" data-placeholder="Choose ...">
                                        <optgroup label="Property Type">
                                            @if( !empty($listOfproperty) )
                                                @foreach($listOfproperty as $item)
                                                    @if( $item->property_type_campaign == $leadReviews->property_type_campaign )
                                                        <option value="{{ $item->property_type_campaign_id }}" selected>{{ $item->property_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->property_type_campaign_id }}">{{ $item->property_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 3 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Installation Preferences:</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_installation_preferences_name }}" readonly>--}}
                                    <select class="select2 form-control" name="installation_preferences" id="installation_preferences" data-placeholder="Choose ...">
                                        <optgroup label="Installation Preferences">
                                            @if( !empty($listOfinstallation_preferences) )
                                                @foreach($listOfinstallation_preferences as $item)
                                                    @if( $item->lead_installation_preferences_name == $leadReviews->lead_installation_preferences_name )
                                                        <option value="{{ $item->lead_installation_preferences_id }}" selected>{{ $item->lead_installation_preferences_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_installation_preferences_id }}">{{ $item->lead_installation_preferences_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Does the customer have An Existing Alarm And/ Or Monitoring System?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_have_item_before_it == 1 ) Yes @else No @endif" readonly>--}}
                                    <select class="select2 form-control" name="lead_have_item_before_it" id="lead_have_item_before_it" data-placeholder="Choose ...">
                                        <optgroup label="Do You Have An Existing Alarm And/ Or Monitoring System?">
                                            <option value="1" @if($leadReviews->lead_have_item_before_it == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_have_item_before_it == 0) selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Property Type</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->property_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="property_type_c" id="property_type_c" data-placeholder="Choose ...">
                                        <optgroup label="Property Type">
                                            @if( !empty($listOfproperty) )
                                                @foreach($listOfproperty as $item)
                                                    @if( $item->property_type_campaign == $leadReviews->property_type_campaign )
                                                        <option value="{{ $item->property_type_campaign_id }}" selected>{{ $item->property_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->property_type_campaign_id }}">{{ $item->property_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 4 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of flooring</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_type_of_flooring_name }}" readonly>--}}
                                    <select class="select2 form-control" name="type_of_flooring" id="type_of_flooring" data-placeholder="Choose ...">
                                        <optgroup label="What type of flooring are you interested in?">
                                            @if( !empty($listOflead_type_of_flooring) )
                                                @foreach($listOflead_type_of_flooring as $item)
                                                    @if( $item->lead_type_of_flooring_name == $leadReviews->lead_type_of_flooring_name )
                                                        <option value="{{ $item->lead_type_of_flooring_id }}" selected>{{ $item->lead_type_of_flooring_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_type_of_flooring_id }}">{{ $item->lead_type_of_flooring_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of the project</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_nature_flooring_project_name }}" readonly>--}}
                                    <select class="select2 form-control" name="nature_flooring_project" id="nature_flooring_project" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your flooring project?">
                                            @if( !empty($listOflead_nature_flooring_project) )
                                                @foreach($listOflead_nature_flooring_project as $item)
                                                    @if( $item->lead_nature_flooring_project_name == $leadReviews->lead_nature_flooring_project_name )
                                                        <option value="{{ $item->lead_nature_flooring_project_id }}" selected>{{ $item->lead_nature_flooring_project_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_nature_flooring_project_id }}">{{ $item->lead_nature_flooring_project_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 5 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of Walk-In Tub?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_walk_in_tub_name }}" readonly>--}}
                                    <select class="select2 form-control" name="walk_in_tub" id="walk_in_tub" data-placeholder="Choose ...">
                                        <optgroup label="Why Do You Want A Walk-In Tub?">
                                            @if( !empty($listOflead_walk_in_tub) )
                                                @foreach($listOflead_walk_in_tub as $item)
                                                    @if( $item->lead_walk_in_tub_name == $leadReviews->lead_walk_in_tub_name )
                                                        <option value="{{ $item->lead_walk_in_tub_id }}" selected>{{ $item->lead_walk_in_tub_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_walk_in_tub_id }}">{{ $item->lead_walk_in_tub_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Desired Features?</label>
                                    <select class="select2 form-control select2-multiple" multiple name="desired_featuers[]" id="desired_featuers" data-placeholder="Choose ...">
                                        <optgroup label="What Are The Desired Features?">
                                            <?php  $lead_desired_featuers_id_array = json_decode($leadReviews->lead_desired_featuers_id, true);?>
                                            @if(!empty($listOflead_desired_featuers))
                                                @foreach($listOflead_desired_featuers as $item)
                                                    <option value="{{ $item->lead_desired_featuers_id }}"
                                                            @if(in_array($item->lead_desired_featuers_id, $lead_desired_featuers_id_array )) selected @endif>{{ $item->lead_desired_featuers_name }}</option>
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 6 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of roofing?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_type_of_roofing_name }}" readonly>--}}
                                    <select class="select2 form-control" name="type_of_roofing" id="type_of_roofing" data-placeholder="Choose ...">
                                        <optgroup label="What type of roofing are you interested in?">
                                            @if( !empty($listOflead_type_of_roofings) )
                                                @foreach($listOflead_type_of_roofings as $item)
                                                    @if( $item->lead_type_of_roofing_name == $leadReviews->lead_type_of_roofing_name )
                                                        <option value="{{ $item->lead_type_of_roofing_id }}" selected>{{ $item->lead_type_of_roofing_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_type_of_roofing_id }}">{{ $item->lead_type_of_roofing_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of the project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_nature_of_roofing_name }}" readonly>--}}
                                    <select class="select2 form-control" name="nature_of_roofing" id="nature_of_roofing" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your roofing project?">
                                            @if( !empty($listOflead_nature_of_roofings) )
                                                @foreach($listOflead_nature_of_roofings as $item)
                                                    @if( $item->lead_nature_of_roofing_name == $leadReviews->lead_nature_of_roofing_name )
                                                        <option value="{{ $item->lead_nature_of_roofing_id }}" selected>{{ $item->lead_nature_of_roofing_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_nature_of_roofing_id }}">{{ $item->lead_nature_of_roofing_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Property Type</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_property_type_roofing_name }}" readonly>--}}
                                    <select class="select2 form-control" name="property_type_roofing" id="property_type_roofing" data-placeholder="Choose ...">
                                        <optgroup label="What is the property type?">
                                            @if( !empty($listOflead_property_type_roofings) )
                                                @foreach($listOflead_property_type_roofings as $item)
                                                    @if( $item->lead_property_type_roofing_name == $leadReviews->lead_property_type_roofing_name )
                                                        <option value="{{ $item->lead_property_type_roofing_id }}" selected>{{ $item->lead_property_type_roofing_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_property_type_roofing_id }}">{{ $item->lead_property_type_roofing_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>

                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 7 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of siding?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->type_of_siding_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="type_of_siding" id="type_of_siding" data-placeholder="Choose ...">
                                        <optgroup label="What type of siding are you interested in?">
                                            @if( !empty($type_of_siding_leads) )
                                                @foreach($type_of_siding_leads as $item)
                                                    @if( $item->type_of_siding_lead_type == $leadReviews->type_of_siding_lead_type  )
                                                        <option value="{{ $item->type_of_siding_lead_id }}" selected>{{ $item->type_of_siding_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->type_of_siding_lead_id }}">{{ $item->type_of_siding_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of the project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->nature_of_siding_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="nature_of_siding" id="nature_of_siding" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your siding project?">
                                            @if( !empty($nature_of_siding_leads) )
                                                @foreach($nature_of_siding_leads as $item)
                                                    @if( $item->nature_of_siding_lead_type == $leadReviews->nature_of_siding_lead_type )
                                                        <option value="{{ $item->nature_of_siding_lead_id }}" selected>{{ $item->nature_of_siding_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->nature_of_siding_lead_id }}">{{ $item->nature_of_siding_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 8 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Services required? </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->service_kitchen_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="service_kitchen" id="service_kitchen" data-placeholder="Choose ...">
                                        <optgroup label="What service are you interested in?">
                                            @if( !empty($service_kitchen_leads) )
                                                @foreach($service_kitchen_leads as $item)
                                                    @if( $item->service_kitchen_lead_type == $leadReviews->service_kitchen_lead_type )
                                                        <option value="{{ $item->service_kitchen_lead_id }}" selected>{{ $item->service_kitchen_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->service_kitchen_lead_id }}">{{ $item->service_kitchen_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Demolishing/building walls? </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->campaign_kitchen_r_a_walls_status == 1 ) Yes @else No @endif" readonly>--}}
                                    <select class="select2 form-control" name="removing_adding_walls" id="removing_adding_walls" data-placeholder="Choose ...">
                                        <optgroup label="Does your kitchen remodel require removing or adding any walls?">
                                            <option value="1" @if($leadReviews->campaign_kitchen_r_a_walls_status == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->campaign_kitchen_r_a_walls_status == 0) selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 9 )
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">Services required?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->campaign_bathroomtype_type }}" readonly>--}}
                                    <select class="select2 form-control" name="bathroom_type" id="bathroom_type" data-placeholder="Choose ...">
                                        <optgroup label="What type of Bathroom project are you interested in?">
                                            @if( !empty($campaign_bathroomtypes) )
                                                @foreach($campaign_bathroomtypes as $item)
                                                    @if( $item->campaign_bathroomtype_type == $leadReviews->campaign_bathroomtype_type )
                                                        <option value="{{ $item->campaign_bathroomtype_id }}" selected>{{ $item->campaign_bathroomtype_type }}</option>
                                                    @else
                                                        <option value="{{ $item->campaign_bathroomtype_id }}">{{ $item->campaign_bathroomtype_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 10 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of stairs? </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->stairs_type_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="stairs_type" id="stairs_type" data-placeholder="Choose ...">
                                        <optgroup label="What type of stairs?">
                                            @if( !empty($stairs_type_leads) )
                                                @foreach($stairs_type_leads as $item)
                                                    @if( $item->stairs_type_lead_type == $leadReviews->stairs_type_lead_type )
                                                        <option value="{{ $item->stairs_type_lead_id }}" selected>{{ $item->stairs_type_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->stairs_type_lead_id }}">{{ $item->stairs_type_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The reason for installing the stairlift </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->stairs_reason_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="stairs_reason" id="stairs_reason" data-placeholder="Choose ...">
                                        <optgroup label="What is the reason that you are looking for stair lift?">
                                            @if( !empty($stairs_reason_leads) )
                                                @foreach($stairs_reason_leads as $item)
                                                    @if( $item->stairs_reason_lead_type == $leadReviews->stairs_reason_lead_type )
                                                        <option value="{{ $item->stairs_reason_lead_id }}" selected>{{ $item->stairs_reason_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->stairs_reason_lead_id }}">{{ $item->stairs_reason_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 11 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of the project? </label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of central heating system required? </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->furnance_type_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="furnance_type" id="furnance_type" data-placeholder="Choose ...">
                                        <optgroup label="What type of central heating system do you want?">
                                            @if( !empty($furnance_type_leads) )
                                                @foreach($furnance_type_leads as $item)
                                                    @if( $item->furnance_type_lead_type == $leadReviews->furnance_type_lead_type )
                                                        <option value="{{ $item->furnance_type_lead_id }}" selected>{{ $item->furnance_type_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 12 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of the project? </label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of boiler system required? </label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->furnance_type_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="furnance_type" id="furnance_type" data-placeholder="Choose ...">
                                        <optgroup label="What type of central heating system do you want?">
                                            @if( !empty($furnance_type_leads) )
                                                @foreach($furnance_type_leads as $item)
                                                    @if( $item->furnance_type_lead_type == $leadReviews->furnance_type_lead_type )
                                                        <option value="{{ $item->furnance_type_lead_id }}" selected>{{ $item->furnance_type_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->furnance_type_lead_id }}">{{ $item->furnance_type_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 13 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of the project? </label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 14 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of the project? </label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 15 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="nutureofpro">Type of services required? </label>
                                    {{--<input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->plumbing_service_list_type }}" readonly>--}}
                                    <select class="select2 form-control" name="plumbing_service" id="plumbing_service" data-placeholder="Choose ...">
                                        <optgroup label="What kind of service do you need?">
                                            @if( !empty($plumbing_service_lists) )
                                                @foreach($plumbing_service_lists as $item)
                                                    @if( $item->plumbing_service_list_type == $leadReviews->plumbing_service_list_type )
                                                        <option value="{{ $item->plumbing_service_list_id }}" selected>{{ $item->plumbing_service_list_type }}</option>
                                                    @else
                                                        <option value="{{ $item->plumbing_service_list_id }}">{{ $item->plumbing_service_list_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 16 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 17 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of the property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_property_type_roofing_name }}" readonly>--}}
                                    <select class="select2 form-control" name="property_type_roofing" id="property_type_roofing" data-placeholder="Choose ...">
                                        <optgroup label="What is the property type?">
                                            @if( !empty($listOflead_property_type_roofings) )
                                                @foreach($listOflead_property_type_roofings as $item)
                                                    @if( $item->lead_property_type_roofing_name == $leadReviews->lead_property_type_roofing_name )
                                                        <option value="{{ $item->lead_property_type_roofing_id }}" selected>{{ $item->lead_property_type_roofing_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_property_type_roofing_id }}">{{ $item->lead_property_type_roofing_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project/services required?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->sunroom_service_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="sunroom_service" id="sunroom_service" data-placeholder="Choose ...">
                                        <optgroup label="What best describes the project?">
                                            @if( !empty($sunroom_service_leads) )
                                                @foreach($sunroom_service_leads as $item)
                                                    @if( $item->sunroom_service_lead_type == $leadReviews->sunroom_service_lead_type )
                                                        <option value="{{ $item->sunroom_service_lead_id }}" selected>{{ $item->sunroom_service_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->sunroom_service_lead_id }}">{{ $item->sunroom_service_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 18 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->handyman_ammount_work_type }}" readonly>--}}
                                    <select class="select2 form-control" name="handyman_ammount" id="handyman_ammount" data-placeholder="Choose ...">
                                        <optgroup label="Describe the amount of work you need to have done?">
                                            @if( !empty($handyman_ammount_works) )
                                                @foreach($handyman_ammount_works as $item)
                                                    @if( $item->handyman_ammount_work_type ==$leadReviews->handyman_ammount_work_type )
                                                        <option value="{{ $item->handyman_ammount_work_id }}" selected>{{ $item->handyman_ammount_work_type }}</option>
                                                    @else
                                                        <option value="{{ $item->handyman_ammount_work_id }}">{{ $item->handyman_ammount_work_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 19 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Countertop material:</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->countertops_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}

                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 20 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Interior/Exterior?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->door_typeproject_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="door_typeproject" id="door_typeproject" data-placeholder="Choose ...">
                                        <optgroup label="What type of door project is this?">
                                            @if( !empty($door_typeproject_leads) )
                                                @foreach($door_typeproject_leads as $item)
                                                    @if( $item->door_typeproject_lead_type == $leadReviews->door_typeproject_lead_type )
                                                        <option value="{{ $item->door_typeproject_lead_id }}" selected>{{ $item->door_typeproject_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->door_typeproject_lead_id }}">{{ $item->door_typeproject_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of doors involved?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->number_of_door_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="number_of_door" id="number_of_door" data-placeholder="Choose ...">
                                        <optgroup label="How many doors are included in this project?">
                                            @if( !empty($number_of_door_leads) )
                                                @foreach($number_of_door_leads as $item)
                                                    @if( $item->number_of_door_lead_type == $leadReviews->number_of_door_lead_type )
                                                        <option value="{{ $item->number_of_door_lead_id }}" selected>{{ $item->number_of_door_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->number_of_door_lead_id }}">{{ $item->number_of_door_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->installing_type_campaign }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 21 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Gutter meterial:</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->gutters_meterial_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="gutters_meterial" id="gutters_meterial" data-placeholder="Choose ...">
                                        <optgroup label="Select the type of Gutter material you want:">
                                            @if( !empty($gutters_meterial_leads) )
                                                @foreach($gutters_meterial_leads as $item)
                                                    @if( $item->gutters_meterial_lead_type == $leadReviews->gutters_meterial_lead_type )
                                                        <option value="{{ $item->gutters_meterial_lead_id }}" selected>{{ $item->gutters_meterial_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->gutters_meterial_lead_id }}">{{ $item->gutters_meterial_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->installing_type_campaign }}" readonly>--}}
                                    <select class="select2 form-control" name="projectnature" id="projectnature" data-placeholder="Choose ...">
                                        <optgroup label="What is the nature of your project?">
                                            @if( !empty($campain_inistallings) )
                                                @foreach($campain_inistallings as $item)
                                                    @if( $item->installing_type_campaign == $leadReviews->installing_type_campaign )
                                                        <option value="{{ $item->installing_type_campaign_id }}" selected>{{ $item->installing_type_campaign }}</option>
                                                    @else
                                                        <option value="{{ $item->installing_type_campaign_id }}">{{ $item->installing_type_campaign }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>


                    @elseif( $leadReviews->lead_type_service_id == 22 )
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_service_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="paving_service" id="paving_service" data-placeholder="Choose ...">
                                        <optgroup label="What service are you interested in?">
                                            @if( !empty($paving_service_lead) )
                                                @foreach($paving_service_lead as $item)
                                                    @if( $item->paving_service_lead_type == $leadReviews->paving_service_lead_type )
                                                        <option value="{{ $item->paving_service_lead_id }}" selected>{{ $item->paving_service_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->paving_service_lead_id }}">{{ $item->paving_service_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h6>Asphalt Paving - Install</h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">The area needing asphalt</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_asphalt_type }}" readonly>--}}
                                    <select class="select2 form-control" name="paving_asphalt_type" id="paving_asphalt_type" data-placeholder="Choose ...">
                                        <optgroup label="What best describes the area needing asphalt?">
                                            @if( !empty($paving_asphalt_type) )
                                                @foreach($paving_asphalt_type as $item)
                                                    @if( $item->paving_asphalt_type == $leadReviews->paving_asphalt_type )
                                                        <option value="{{ $item->paving_asphalt_type_id }}" selected>{{ $item->paving_asphalt_type }}</option>
                                                    @else
                                                        <option value="{{ $item->paving_asphalt_type_id }}">{{ $item->paving_asphalt_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h6>Gravel or Loose Fill Paving - Install, Spread or Scrape</h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">Material of loose fill required</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_loose_fill_type }}" readonly>--}}
                                    <select class="select2 form-control" name="paving_loose_fill_type" id="paving_loose_fill_type" data-placeholder="Choose ...">
                                        <optgroup label="What type of loose fill do you want?">
                                            @if( !empty($paving_loose_fill_type) )
                                                @foreach($paving_loose_fill_type as $item)
                                                    @if( $item->paving_loose_fill_type == $leadReviews->paving_loose_fill_type )
                                                        <option value="{{ $item->paving_loose_fill_type_id }}" selected>{{ $item->paving_loose_fill_type }}</option>
                                                    @else
                                                        <option value="{{ $item->paving_loose_fill_type_id }}">{{ $item->paving_loose_fill_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_best_describes_priject_type }}" readonly>--}}
                                    <select class="select2 form-control" name="paving_best_describes_priject" id="paving_best_describes_priject" data-placeholder="Choose ...">
                                        <optgroup label="What best describes this project?">
                                            @if( !empty($paving_best_describes_priject) )
                                                @foreach($paving_best_describes_priject as $item)
                                                    @if( $item->paving_best_describes_priject_type == $leadReviews->paving_best_describes_priject_type )
                                                        <option value="{{ $item->paving_best_describes_priject_id }}" selected>{{ $item->paving_best_describes_priject_type }}</option>
                                                    @else
                                                        <option value="{{ $item->paving_best_describes_priject_id }}">{{ $item->paving_best_describes_priject_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>--}}
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 23 )
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting_service" id="painting_service" data-placeholder="Choose ...">
                                        <optgroup label="What service are you interested in?">
                                            @if( !empty($painting_service_lead) )
                                                @foreach($painting_service_lead as $item)
                                                    @if( $item->painting_service_lead_type == $leadReviews->painting_service_lead_type )
                                                        <option value="{{ $item->painting_service_lead_id }}" selected>{{ $item->painting_service_lead_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting_service_lead_id }}">{{ $item->painting_service_lead_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h6>Exterior Home or Structure - Paint or Stain</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_typeof_project_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting1_typeof_project" id="painting1_typeof_project" data-placeholder="Choose ...">
                                        <optgroup label="What type of project is this?">
                                            @if( !empty($painting1_typeof_project) )
                                                @foreach($painting1_typeof_project as $item)
                                                    @if( $item->painting1_typeof_project_type == $leadReviews->painting1_typeof_project_type )
                                                        <option value="{{ $item->painting1_typeof_project_id }}" selected>{{ $item->painting1_typeof_project_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting1_typeof_project_id }}">{{ $item->painting1_typeof_project_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What kinds of surfaces need to be painted and/or stained?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_kindsof_surfaces_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting1_kindsof_surfaces" id="painting1_kindsof_surfaces" data-placeholder="Choose ...">
                                        <optgroup label="What kinds of surfaces need to be painted and/or stained?">
                                            @if( !empty($painting1_kindsof_surfaces) )
                                                @foreach($painting1_kindsof_surfaces as $item)
                                                    @if( $item->painting1_kindsof_surfaces_type == $leadReviews->painting1_kindsof_surfaces_type )
                                                        <option value="{{ $item->painting1_kindsof_surfaces_id }}" selected>{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting1_kindsof_surfaces_id }}">{{ $item->painting1_kindsof_surfaces_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h6>Interior Home or Surfaces - Paint or Stain</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of rooms need to be painted</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting2_rooms_number_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting2_rooms_number" id="painting2_rooms_number" data-placeholder="Choose ...">
                                        <optgroup label="How many rooms or areas need to be painted?">
                                            @if( !empty($painting2_rooms_number) )
                                                @foreach($painting2_rooms_number as $item)
                                                    @if( $item->painting2_rooms_number_type == $leadReviews->painting2_rooms_number_type )
                                                        <option value="{{ $item->painting2_rooms_number_id }}" selected>{{ $item->painting2_rooms_number_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting2_rooms_number_id }}">{{ $item->painting2_rooms_number_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What needs to be painted?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting2_typeof_paint_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting2_typeof_paint" id="painting2_typeof_paint" data-placeholder="Choose ...">
                                        <optgroup label="What needs to be painted?">
                                            @if( !empty($painting2_typeof_paint) )
                                                @foreach($painting2_typeof_paint as $item)
                                                    @if( $item->painting2_typeof_paint_type == $leadReviews->painting2_typeof_paint_type )
                                                        <option value="{{ $item->painting2_typeof_paint_id }}" selected>{{ $item->painting2_typeof_paint_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting2_typeof_paint_id }}">{{ $item->painting2_typeof_paint_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h6>Painting or Staining - Small Projects</h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">Areas need to be painted/stained</label>
                                <select class="select2 form-control select2-multiple" multiple name="painting3_each_feature[]" id="painting3_each_feature" data-placeholder="Choose ...">
                                    <optgroup label="Check off each feature that will need to be painted/stained. (Check all that apply)">
                                        <?php $painting3_each_feature_id_array = json_decode($leadReviews->painting3_each_feature_id, true); ?>
                                        @if(!empty($painting3_each_feature))
                                            @foreach($painting3_each_feature as $item)
                                                <option value="{{ $item->painting3_each_feature_id }}"
                                                        @if(!empty($painting3_each_feature_id_array)) @if(in_array($item->painting3_each_feature_id, $painting3_each_feature_id_array )) selected @endif @endif>{{ $item->painting3_each_feature_type }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <h6>Metal Roofing - Paint</h6>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">The condition of the existing roof</label>
                                <select class="select2 form-control select-multiple" multiple name="painting4_existing_roof[]" id="painting4_existing_roof" data-placeholder="Choose ...">
                                    <optgroup label="What best describes the condition of the existing roof?">
                                        <?php $painting4_existing_roof_id_array = json_decode($leadReviews->painting4_existing_roof_id, true); ?>
                                        @if(!empty($painting4_existing_roof))
                                            @foreach($painting4_existing_roof as $item)
                                                <option value="{{ $item->painting4_existing_roof_id }}"
                                                        @if(!empty($painting4_existing_roof_id_array)) @if(in_array($item->painting4_existing_roof_id, $painting4_existing_roof_id_array )) selected @endif @endif>{{ $item->painting4_existing_roof_type }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <h6>Specialty Painting - Textures</h6>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What surfaces need to be textured?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting5_surfaces_textured_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting5_surfaces_textured" id="painting5_surfaces_textured" data-placeholder="Choose ...">
                                        <optgroup label="What surfaces need to be textured?">
                                            @if( !empty($painting5_surfaces_textured) )
                                                @foreach($painting5_surfaces_textured as $item)
                                                    @if( $item->painting5_surfaces_textured_type == $leadReviews->painting5_surfaces_textured_type )
                                                        <option value="{{ $item->painting5_surfaces_textured_id }}" selected>{{ $item->painting5_surfaces_textured_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting5_surfaces_textured_id }}">{{ $item->painting5_surfaces_textured_type }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="numberOfitem">What kind of texturing needed?</label>
                                <select class="select2 form-control select2-multiple" multiple name="painting5_kindof_texturing[]" id="painting5_kindof_texturing" data-placeholder="Choose ...">
                                    <optgroup label="What kind of texturing do you need?">
                                        <?php $painting5_kindof_texturing_array = json_decode($leadReviews->painting5_kindof_texturing_id, true); ?>
                                        @if(!empty($painting5_kindof_texturing))
                                            @foreach($painting5_kindof_texturing as $item)
                                                <option value="{{ $item->painting5_kindof_texturing_id }}"
                                                        @if(!empty($painting5_kindof_texturing_array)) @if(in_array($item->painting5_kindof_texturing_id, $painting5_kindof_texturing_array )) selected @endif @endif>{{ $item->painting5_kindof_texturing_type }}</option>
                                            @endforeach
                                        @endif
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of stories of the property</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_stories_number_type }}" readonly>--}}
                                    <select class="select2 form-control" name="painting1_stories" id="painting1_stories" data-placeholder="Choose ...">
                                        <optgroup label="How many stories is your home?">
                                            @if( !empty($painting1_stories_number) )
                                                @foreach($painting1_stories_number as $item)
                                                    @if( $item->painting1_stories_number_type == $leadReviews->painting1_stories_number_type )
                                                        <option value="{{ $item->painting1_stories_number_id }}" selected>{{ $item->painting1_stories_number_type }}</option>
                                                    @else
                                                        <option value="{{ $item->painting1_stories_number_id }}">{{ $item->painting1_stories_number_type }}</option>
                                                    @endif

                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->historical_structure == 1 ) Yes @else No @endif " readonly>--}}
                                    <select class="select2 form-control" name="interior_historical" id="interior_historical" data-placeholder="Choose ...">
                                        <optgroup label="Is this location a historical structure?">
                                            <option value="1" @if($leadReviews->historical_structure == 1) selected @endif>Yes</option>
                                            <option value="2" @if($leadReviews->historical_structure == 2) selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Owner of the Property?</label>
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Do you own your home?">
                                            <option value="1" @if($leadReviews->lead_ownership == 1) selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == 0) selected @endif>No</option>
                                            <option value="3" @if($leadReviews->lead_ownership == 3) selected @endif>No, But Authorized to Make Changes</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">The project is starting</label>
                                    {{--<input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_priority_name }}" readonly>--}}
                                    <select class="select2 form-control" name="priority" id="priority" data-placeholder="Choose ...">
                                        <optgroup label="Time to start project?">
                                            @if( !empty($lead_prioritys) )
                                                @foreach($lead_prioritys as $item)
                                                    @if( $item->lead_priority_name == $leadReviews->lead_priority_name )
                                                        <option value="{{ $item->lead_priority_id }}" selected>{{ $item->lead_priority_name }}</option>
                                                    @else
                                                        <option value="{{ $item->lead_priority_id }}">{{ $item->lead_priority_name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 24 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="VehicleYear">Vehicle Make Year:</label>
                                    <input type="text" class="form-control" id="VehicleYear" name="VehicleYear" value="{{ $leadReviews->VehicleYear }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="VehicleMake">Vehicle Brand::</label>
                                    <input type="text" class="form-control" id="VehicleMake" name="VehicleMake" value="{{ $leadReviews->VehicleMake }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="car_model">Car Model:</label>
                                    <input type="text" class="form-control" id="car_model" name="car_model" value="{{ $leadReviews->car_model }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="InsuranceCarrier">Current insurance company:</label>
                                    <input type="text" class="form-control" id="InsuranceCarrier" name="InsuranceCarrier" value="{{ $leadReviews->InsuranceCarrier }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="more_than_one_vehicle">Do you have more than one vehicle?</label>
                                    <select class="select2 form-control" id="more_than_one_vehicle" name="more_than_one_vehicle" data-placeholder="Choose ...">
                                        <optgroup label="Do you have more than one vehicle?">
                                            <option value="Yes" @if($leadReviews->more_than_one_vehicle == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->more_than_one_vehicle == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="driversNum">Is there more than one driver in your household?</label>
                                    <select class="select2 form-control" id="driversNum" name="driversNum" data-placeholder="Choose ...">
                                        <optgroup label="Is there more than one driver in your household?">
                                            <option value="Yes" @if($leadReviews->driversNum == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->driversNum == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="genders">Driver's Gender:</label>
                                    <select class="select2 form-control" id="genders" name="genders" data-placeholder="Choose ...">
                                        <optgroup label="Driver's Gender:">
                                            <option value="Male" @if($leadReviews->genders == "Male") selected @endif>Male</option>
                                            <option value="Female" @if($leadReviews->genders == "Female") selected @endif>Female</option>
                                            <option value="Non-binary" @if($leadReviews->genders == "Non-binary") selected @endif>Non-binary</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-married">
                                    <label for="married">Marital State of the driver:</label>
                                    <select class="select2 form-control" id="married" name="married" data-placeholder="Choose ...">
                                        <optgroup label="Marital State of the driver:">
                                            <option value="Yes" @if($leadReviews->married == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->married == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="license">Owner of a valid driving license:</label>
                                    <select class="select2 form-control" id="license" name="license" data-placeholder="Choose ...">
                                        <optgroup label="Owner of a valid driving license:">
                                            <option value="Yes" @if($leadReviews->license == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->license == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="driver_experience">Is the driver experienced?</label>
                                    <select class="select2 form-control" id="driver_experience" name="driver_experience" data-placeholder="Choose ...">
                                        <optgroup label="Is the driver experienced?">
                                            <option value="Yes" @if($leadReviews->driver_experience == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->driver_experience == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="number_of_tickets">Number of tickets or accidents:</label>
                                    <select class="select2 form-control" id="number_of_tickets" name="number_of_tickets" data-placeholder="Choose ...">
                                        <optgroup label="Number of tickets or accidents:">
                                            <option value="0" @if($leadReviews->number_of_tickets == "0") selected @endif>0</option>
                                            <option value="1" @if($leadReviews->number_of_tickets == "1") selected @endif>1</option>
                                            <option value="2" @if($leadReviews->number_of_tickets == "2") selected @endif>2</option>
                                            <option value="3" @if($leadReviews->number_of_tickets == "3") selected @endif>3</option>
                                            <option value="4" @if($leadReviews->number_of_tickets == "4") selected @endif>4</option>
                                            <option value="5+" @if($leadReviews->number_of_tickets == "5+") selected @endif>5+</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="DUI_charges">DUI charges:</label>
                                    <select class="select2 form-control" id="DUI_charges" name="DUI_charges" data-placeholder="Choose ...">
                                        <optgroup label="DUI charges:">
                                            <option value="Yes" @if($leadReviews->DUI_charges == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->DUI_charges == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="submodel">Car sub model:</label>
                                    <select class="select2 form-control" id="submodel" name="submodel" data-placeholder="Choose ...">
                                        <optgroup label="Car sub model:">
                                            <option value="Micros" @if($leadReviews->submodel == "Micros") selected @endif>Micros</option>
                                            <option value="Hatchback" @if($leadReviews->submodel == "Hatchback") selected @endif>Hatchback</option>
                                            <option value="Fastback" @if($leadReviews->submodel == "Fastback") selected @endif>Fastback</option>
                                            <option value="Sedan" @if($leadReviews->submodel == "Sedan") selected @endif>Sedan</option>
                                            <option value="Crossover" @if($leadReviews->submodel == "Crossover") selected @endif>Crossover</option>
                                            <option value="SUV" @if($leadReviews->submodel == "SUV") selected @endif>SUV</option>
                                            <option value="MPV" @if($leadReviews->submodel == "MPV") selected @endif>MPV</option>
                                            <option value="Convertible" @if($leadReviews->submodel == "Convertible") selected @endif>Convertible</option>
                                            <option value="Wagon" @if($leadReviews->submodel == "Wagon") selected @endif>Wagon</option>
                                            <option value="Luxury" @if($leadReviews->submodel == "Luxury") selected @endif>Luxury</option>
                                            <option value="Antique" @if($leadReviews->submodel == "Antique") selected @endif>Antique</option>
                                            <option value="Coupe" @if($leadReviews->submodel == "Coupe") selected @endif>Coupe</option>
                                            <option value="Sports Car" @if($leadReviews->submodel == "Sports Car") selected @endif>Sports Car</option>
                                            <option value="Supercar" @if($leadReviews->submodel == "Supercar") selected @endif>Supercar</option>
                                            <option value="Muscle Car" @if($leadReviews->submodel == "Muscle Car") selected @endif>Muscle Car</option>
                                            <option value="Limousine" @if($leadReviews->submodel == "Limousine") selected @endif>Limousine</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="license_status">License status:</label>
                                    <select class="select2 form-control" id="license_status" name="license_status" data-placeholder="Choose ...">
                                        <optgroup label="License status:">
                                            <option value="Active" @if($leadReviews->license_status == "Active") selected @endif>Active</option>
                                            <option value="International" @if($leadReviews->license_status == "International") selected @endif>International</option>
                                            <option value="Learner" @if($leadReviews->license_status == "Learner") selected @endif>Learner</option>
                                            <option value="Probation" @if($leadReviews->license_status == "Probation") selected @endif>Probation</option>
                                            <option value="Restricted" @if($leadReviews->license_status == "Restricted") selected @endif>Restricted</option>
                                            <option value="Suspended" @if($leadReviews->license_status == "Suspended") selected @endif>Suspended</option>
                                            <option value="Temporary" @if($leadReviews->license_status == "Temporary") selected @endif>Temporary</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="coverage_type">Insurance coverage type:</label>
                                    <select class="select2 form-control" id="coverage_type" name="coverage_type" data-placeholder="Choose ...">
                                        <optgroup label="Insurance coverage type:">
                                            <option value="Preferred" @if($leadReviews->coverage_type == "Preferred") selected @endif>Preferred</option>
                                            <option value="Premium" @if($leadReviews->coverage_type == "Premium") selected @endif>Premium</option>
                                            <option value="Standard" @if($leadReviews->coverage_type == "Standard") selected @endif>Standard</option>
                                            <option value="State Minimum" @if($leadReviews->coverage_type == "State Minimum") selected @endif>State Minimum</option>
                                            <option value="Interested" @if($leadReviews->coverage_type == "Interested") selected @endif>Interested</option>
                                            <option value="Not Interested" @if($leadReviews->coverage_type == "Not Interested") selected @endif>Not Interested</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="SR_22_need">Need for SR-22:</label>
                                    <select class="select2 form-control" id="SR_22_need" name="SR_22_need" data-placeholder="Choose ...">
                                        <optgroup label="Need for SR-22:">
                                            <option value="Yes" @if($leadReviews->SR_22_need == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->SR_22_need == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="ownership">Are you a home owner?</label>
                                    <select class="select2 form-control" name="ownership" id="ownership" data-placeholder="Choose ...">
                                        <optgroup label="Are you a home owner?">
                                            <option value="1" @if($leadReviews->lead_ownership == "1") selected @endif>Yes</option>
                                            <option value="0" @if($leadReviews->lead_ownership == "0") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="birthday">Birthdate of the driver:</label>
                                    <input type="text" id="datepicker1" name="birthday" placeholder="birthday" value="{{ $leadReviews->birthday }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="ticket_date">Last ticket date:</label>
                                    <input type="text" id="datepicker1" name="ticket_date" placeholder="ticket_date" value="{{ $leadReviews->ticket_date }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="violation_date">Violation date:</label>
                                    <input type="text" id="datepicker1" name="violation_date" placeholder="violation_date" value="{{ $leadReviews->violation_date }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="accident_date">Accident date:</label>
                                    <input type="text" id="datepicker1" name="accident_date" placeholder="accident_date" value="{{ $leadReviews->accident_date }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="claim_date">Insurance claim date:</label>
                                    <input type="text" id="datepicker1" name="claim_date" placeholder="claim_date" value="{{ $leadReviews->claim_date }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="expiration_date">Current insurance expiration date:</label>
                                    <input type="text" id="datepicker1" name="expiration_date" placeholder="expiration_date" value="{{ $leadReviews->expiration_date }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 25 )
                        {{-- home insurance--}}
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="house_type">What kind of home Do You Live In?</label>
                                    <select class="select2 form-control" id="house_type" name="house_type" data-placeholder="Choose ...">
                                        <optgroup label="What kind of home Do You Live In?">
                                            <option value="Single Family Home" @if($leadReviews->house_type == "Single Family Home") selected @endif>Single Family Home</option>
                                            <option value="Apartment" @if($leadReviews->house_type == "Apartment") selected @endif>Apartment</option>
                                            <option value="Condo/TownHouse" @if($leadReviews->house_type == "Condo/TownHouse") selected @endif>Condo/TownHouse</option>
                                            <option value="Duplex" @if($leadReviews->house_type == "Duplex") selected @endif>Duplex</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Year_Built">Year built (It's OK to estimate)</label>
                                    <input type="text" class="form-control" id="Year_Built" name="Year_Built" value="{{ $leadReviews->Year_Built }}" >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="primary_residence">Is this your primary residence?</label>
                                    <select class="select2 form-control" id="primary_residence" name="primary_residence" data-placeholder="Choose ...">
                                        <optgroup label="Is this your primary residence?">
                                            <option value="Yes" @if($leadReviews->primary_residence == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->primary_residence == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="new_purchase">Is this a new purchase?</label>
                                    <select class="select2 form-control" id="new_purchase" name="new_purchase" data-placeholder="Choose ...">
                                        <optgroup label="Is this a new purchase?">
                                            <option value="Yes" @if($leadReviews->new_purchase == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->new_purchase == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="previous_insurance_within_last30">Have you had insurance within the last 30 days?</label>
                                    <select class="select2 form-control" id="previous_insurance_within_last30" name="previous_insurance_within_last30" data-placeholder="Choose ...">
                                        <optgroup label="Have you had insurance within the last 30 days?">
                                            <option value="Yes" @if($leadReviews->previous_insurance_within_last30 == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->previous_insurance_within_last30 == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="previous_insurance_claims_last3yrs">Have you made any home insurance claims in the past 3 years?</label>
                                    <select class="select2 form-control" id="previous_insurance_claims_last3yrs" name="previous_insurance_claims_last3yrs" data-placeholder="Choose ...">
                                        <optgroup label="Have you made any home insurance claims in the past 3 years?">
                                            <option value="Yes" @if($leadReviews->previous_insurance_claims_last3yrs == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->previous_insurance_claims_last3yrs == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="married">Are you married?</label>
                                    <select class="select2 form-control" id="married" name="married" data-placeholder="Choose ...">
                                        <optgroup label="Are you married?">
                                            <option value="Yes" @if($leadReviews->married == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->married == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="credit_rating">Credit rating</label>
                                    <select class="select2 form-control" id="credit_rating" name="credit_rating" data-placeholder="Choose ...">
                                        <optgroup label="Credit rating?">
                                            <option value="Excellent" @if($leadReviews->credit_rating == "Excellent") selected @endif>Excellent</option>
                                            <option value="Good" @if($leadReviews->credit_rating == "Good") selected @endif>Good</option>
                                            <option value="Poor" @if($leadReviews->credit_rating == "Poor") selected @endif>Poor</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="birthday">When is your birthday?</label>
                                    <input type="text" id="datepicker1" name="birthday" placeholder="birthday" value="{{ $leadReviews->birthday }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>
                        </div>

                    @elseif( $leadReviews->lead_type_service_id == 26 || $leadReviews->lead_type_service_id == 27 )
                        {{-- // Life Insurance & Disability insurance --}}
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="genders">What's your gender?</label>
                                    <select class="select2 form-control" id="genders" name="genders" data-placeholder="Choose ...">
                                        <optgroup label="Driver's Gender:">
                                            <option value="Male" @if($leadReviews->genders == "Male") selected @endif>Male</option>
                                            <option value="Female" @if($leadReviews->genders == "Female") selected @endif>Female</option>
                                            <option value="Non-binary" @if($leadReviews->genders == "Non-binary") selected @endif>Non-binary</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Height">Select Your Height?</label>
                                    <select class="select2 form-control" id="Height" name="Height" data-placeholder="Choose ...">
                                        <optgroup label="Select Your Height?">
                                            <option value="under 5" @if($leadReviews->Height == 'under 5') selected @endif>Under 5' 0"</option>
                                            <option value="5 0" @if($leadReviews->Height == "5 0") selected @endif>5' 0"</option>
                                            <option value="5 1" @if($leadReviews->Height == "5 1") selected @endif>5' 1"</option>
                                            <option value="5 2" @if($leadReviews->Height == "5 2") selected @endif>5' 2"</option>
                                            <option value="5 3" @if($leadReviews->Height == "5 3") selected @endif>5' 3"</option>
                                            <option value="5 4" @if($leadReviews->Height == "5 4") selected @endif>5' 4"</option>
                                            <option value="5 5" @if($leadReviews->Height == "5 5") selected @endif>5' 5"</option>
                                            <option value="5 6" @if($leadReviews->Height == "5 6") selected @endif>5' 6"</option>
                                            <option value="5 7" @if($leadReviews->Height == "5 7") selected @endif>5' 7"</option>
                                            <option value="5 8" @if($leadReviews->Height == "5 8") selected @endif>5' 8"</option>
                                            <option value="5 9" @if($leadReviews->Height == "5 9") selected @endif>5' 9"</option>
                                            <option value="5 10" @if($leadReviews->Height == "5 10") selected @endif>5' 10"</option>
                                            <option value="5 11" @if($leadReviews->Height == "5 11") selected @endif>5' 11"</option>
                                            <option value="6 0" @if($leadReviews->Height == "6 0") selected @endif>6' 0"</option>
                                            <option value="6 1" @if($leadReviews->Height == "6 1") selected @endif>6' 1"</option>
                                            <option value="6 2" @if($leadReviews->Height == "6 2") selected @endif>6' 2"</option>
                                            <option value="6 3" @if($leadReviews->Height == "6 3") selected @endif>6' 3"</option>
                                            <option value="6 4" @if($leadReviews->Height == "6 4") selected @endif>6' 4"</option>
                                            <option value="6 5" @if($leadReviews->Height == "6 5") selected @endif>6' 5"</option>
                                            <option value="6 6" @if($leadReviews->Height == "6 6") selected @endif>6' 6"</option>
                                            <option value="over 6" @if($leadReviews->Height == "over 6") selected @endif>Over 6' 6"</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="weight">Enter Your Weight?</label>
                                    <input type="text" class="form-control" id="weight" name="weight" value="{{ $leadReviews->weight }}" >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="birthday">When is your birthday?</label>
                                    <input type="text" id="datepicker1" name="birthday" placeholder="birthday" value="{{ $leadReviews->birthday }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="primary_residence">Amount of Coverage You are Considering ?</label>
                                    <select class="select2 form-control" id="amount_coverage" name="amount_coverage" data-placeholder="Choose ...">
                                        <optgroup label="Amount of Coverage You are Considering ?">
                                            <option value="5000" @if($leadReviews->amount_coverage == "5000") selected @endif>$5,000</option>
                                            <option value="10000" @if($leadReviews->amount_coverage == "10000") selected @endif>$10,000</option>
                                            <option value="20000" @if($leadReviews->amount_coverage == "20000") selected @endif>$20,000</option>
                                            <option value="30000" @if($leadReviews->amount_coverage == "30000") selected @endif>$30,000</option>
                                            <option value="40000" @if($leadReviews->amount_coverage == "40000") selected @endif>$40,000</option>
                                            <option value="50000" @if($leadReviews->amount_coverage == "50000") selected @endif>$50,000</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="military_personnel_status">Are you active or retired military personnel?</label>
                                    <select class="select2 form-control" id="military_personnel_status" name="military_personnel_status" data-placeholder="Choose ...">
                                        <optgroup label="Are you active or retired military personnel?">
                                            <option value="Yes" @if($leadReviews->military_personnel_status == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->military_personnel_status == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Militarystatus">Military status?</label>
                                    <select class="select2 form-control" id="military_status" name="military_status" data-placeholder="Choose ...">
                                        <optgroup label="Military status ?">
                                            <option value="Active" @if($leadReviews->military_status == "Active") selected @endif>Active</option>
                                            <option value="Inactive Reserve" @if($leadReviews->military_status == "Inactive Reserve") selected @endif>Inactive Reserve</option>
                                            <option value="Retired" @if($leadReviews->military_status == "Retired") selected @endif>Retired</option>
                                            <option value="Reservist" @if($leadReviews->military_status == "Reservist") selected @endif>Reservist</option>
                                            <option value="Honorably Discharged" @if($leadReviews->military_status == "Honorably Discharged") selected @endif>Honorably Discharged</option>
                                            <option value="Separated within 120 days" @if($leadReviews->military_status == "Separated within 120 days") selected @endif>Separated within 120 days</option>
                                            <option value="Separated over 120 days" @if($leadReviews->military_status == "Separated over 120 days") selected @endif>Separated over 120 days</option>
                                            <option value="Not A Military Personnel" @if($leadReviews->military_status == "Not A Military Personnel") selected @endif>Not A Military Personnel</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="service_branch">Service branch?</label>
                                    <select class="select2 form-control" id="service_branch" name="service_branch" data-placeholder="Choose ...">
                                        <optgroup label="Service branch ?">
                                            <option value="US Air Force" @if($leadReviews->service_branch == "US Air Force") selected @endif>US Air Force</option>
                                            <option value="US Army" @if($leadReviews->service_branch == "US Army") selected @endif>US Army</option>
                                            <option value="US Coast Guard" @if($leadReviews->service_branch == "US Coast Guard") selected @endif>US Coast Guard</option>
                                            <option value="US Marine Corps" @if($leadReviews->service_branch == "US Marine Corps") selected @endif>US Marine Corps</option>
                                            <option value="US Navy" @if($leadReviews->service_branch == "US Navy") selected @endif>US Navy</option>
                                            <option value="Not A Military Personnel" @if($leadReviews->service_branch == "Not A Military Personnel") selected @endif>Not A Military Personnel</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 28 )
                        {{-- Business insurance--}}
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="CommercialCoverage">What coverage does your business need?</label>
                                    <select id="CommercialCoverage" name="CommercialCoverage[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                        <optgroup label="What coverage does your business need?">
                                            <?php
                                            $CommercialCoverageArray = json_decode($leadReviews->CommercialCoverage,true);
                                            ?>
                                            <option value="Liability and/or Umbrella Insurance" @if(in_array("Liability and/or Umbrella Insurance",$CommercialCoverageArray)) selected @endif>Liability and/or Umbrella Insurance</option>
                                            <option value="Business Owners Policy" @if(in_array("Business Owners Policy",$CommercialCoverageArray)) selected @endif>Business Owners Policy</option>
                                            <option value="Workers' Compensation" @if(in_array("Workers' Compensation",$CommercialCoverageArray)) selected @endif>Workers' Compensation</option>
                                            <option value="Commercial Auto" @if(in_array("Commercial Auto",$CommercialCoverageArray)) selected @endif>Commercial Auto</option>
                                            <option value="Professional Liability/Errors & Omissions" @if(in_array("Professional Liability/Errors & Omissions",$CommercialCoverageArray)) selected @endif>Professional Liability/Errors & Omissions</option>
                                            <option value="Commercial Property Insurance" @if(in_array("Commercial Property Insurance",$CommercialCoverageArray)) selected @endif>Commercial Property Insurance</option>
                                            <option value="Cyber Liability" @if(in_array("Cyber Liability",$CommercialCoverageArray)) selected @endif>Cyber Liability</option>
                                            <option value="Other" @if(in_array("Other",$CommercialCoverageArray)) selected @endif>Other</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="company_benefits_quote">Would you also like to get quotes for your company's benefits?</label>
                                    <select class="select2 form-control" id="company_benefits_quote" name="company_benefits_quote" data-placeholder="Choose ...">
                                        <optgroup label="Would you also like to get quotes for your company's benefits?">
                                            <option value="Yes" @if($leadReviews->company_benefits_quote == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->company_benefits_quote == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="business_start_date">When did you start your business?</label>
                                    <input type="text" class="form-control" id="business_start_date" name="business_start_date" value="{{ $leadReviews->business_start_date }}" >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="estimated_annual_payroll">What is Your Estimated Annual Employee Payroll in the Next 12 Months?</label>
                                    <input type="text" class="form-control" id="estimated_annual_payroll" name="estimated_annual_payroll" value="{{$leadReviews->estimated_annual_payroll }}" >
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="number_of_employees">Total # of Employees including Yourself ?</label>
                                    <input type="text" class="form-control" id="number_of_employees" name="number_of_employees" value="{{$leadReviews->number_of_employees}}" >
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="Height">When would you like the coverage to begin?</label>
                                    <select class="select2 form-control" id="coverage_start_month" name="coverage_start_month" data-placeholder="Choose ...">
                                        <optgroup label="When would you like the coverage to begin?">
                                            <option value="January" @if($leadReviews->coverage_start_month == "January") selected @endif>January</option>
                                            <option value="February" @if($leadReviews->coverage_start_month == "February") selected @endif>February</option>
                                            <option value="March" @if($leadReviews->coverage_start_month == "March") selected @endif>March</option>
                                            <option value="April" @if($leadReviews->coverage_start_month == "April") selected @endif>April</option>
                                            <option value="May" @if($leadReviews->coverage_start_month == "May") selected @endif>May</option>
                                            <option value="June" @if($leadReviews->coverage_start_month == "June") selected @endif>June</option>
                                            <option value="July" @if($leadReviews->coverage_start_month == "July") selected @endif>July</option>
                                            <option value="August" @if($leadReviews->coverage_start_month == "August") selected @endif>August</option>
                                            <option value="September" @if($leadReviews->coverage_start_month == "September") selected @endif>September</option>
                                            <option value="October" @if($leadReviews->coverage_start_month == "October") selected @endif>October</option>
                                            <option value="November" @if($leadReviews->coverage_start_month == "November") selected @endif>November</option>
                                            <option value="December" @if($leadReviews->coverage_start_month == "December") selected @endif>December</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="business_name">Business Name ?</label>
                                    <input type="text" class="form-control" id="business_name" name="business_name" value="{{ $leadReviews->business_name }}" >
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->lead_type_service_id == 29 || $leadReviews->lead_type_service_id == 30 )
                        {{-- // Health Insurance &  long term insurance --}}
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="genders">What's your gender?</label>
                                    <select class="select2 form-control" id="genders" name="genders" data-placeholder="Choose ...">
                                        <optgroup label="Driver's Gender:">
                                            <option value="Male" @if($leadReviews->genders == "Male") selected @endif>Male</option>
                                            <option value="Female" @if($leadReviews->genders == "Female") selected @endif>Female</option>
                                            <option value="Non-binary" @if($leadReviews->genders == "Non-binary") selected @endif>Non-binary</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="birthday">When is your birthday?</label>
                                    <input type="text" id="datepicker1" name="birthday" placeholder="birthday" value="{{ $leadReviews->birthday }}" autocomplete="false" class="form-control"/>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="pregnancy">Are you or your spouse pregnant right now, or adopting a child?</label>
                                    <select class="select2 form-control" id="pregnancy" name="pregnancy" data-placeholder="Choose ...">
                                        <optgroup label="Are you or your spouse pregnant right now, or adopting a child?">
                                            <option value="Yes" @if($leadReviews->pregnancy == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->pregnancy == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="pregnancy">Do you use tobacco?</label>
                                    <select class="select2 form-control" id="tobacco_usage" name="tobacco_usage" data-placeholder="Choose ...">
                                        <optgroup label="Do you use tobacco?">
                                            <option value="Yes" @if($leadReviews->tobacco_usage == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->tobacco_usage == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="health_conditions">Do you have any of these health conditions?</label>
                                    <select class="select2 form-control" id="health_conditions" name="health_conditions" data-placeholder="Choose ...">
                                        <optgroup label="Do you have any of these health conditions?">
                                            <option value="Yes" @if($leadReviews->health_conditions == "Yes") selected @endif>Yes</option>
                                            <option value="No" @if($leadReviews->health_conditions == "No") selected @endif>No</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="number_of_people_in_household">How many people are in your household?</label>
                                    <select class="select2 form-control" id="number_of_people_in_household" name="number_of_people_in_household" data-placeholder="Choose ...">
                                        <optgroup label="How many people are in your household?">
                                            <option value="1" @if($leadReviews->number_of_people_in_household == "1") selected @endif>1</option>
                                            <option value="2" @if($leadReviews->number_of_people_in_household == "2") selected @endif>2</option>
                                            <option value="3" @if($leadReviews->number_of_people_in_household == "3") selected @endif>3</option>
                                            <option value="4" @if($leadReviews->number_of_people_in_household == "4") selected @endif>4</option>
                                            <option value="5" @if($leadReviews->number_of_people_in_household == "5") selected @endif>5</option>
                                            <option value="6 or more" @if($leadReviews->number_of_people_in_household == "6 or more") selected @endif>6 or more</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="addPeople">Is your insurance just for you, or do you plan on covering others?</label>
                                    <select id="addPeople" name="addPeople[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                                        <optgroup label="What coverage does your business need?">
                                            <?php
                                            $addPeopleArray = json_decode($leadReviews->addPeople,true);
                                            ?>
                                            <option value="Me Only/Me" @if(in_array("Me Only/Me", $addPeopleArray) ) selected @endif> Me Only/Me</option>
                                            <option value="My Spouse" @if(in_array("My Spouse", $addPeopleArray) ) selected @endif> My Spouse</option>
                                            <option value="My Child/Children" @if(in_array("My Child/Children", $addPeopleArray) ) selected @endif>My Child/Children</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="annual_income">What's your annual household income?</label>
                                    <select class="select2 form-control" id="annual_income" name="annual_income" data-placeholder="Choose ...">
                                        <optgroup label="What's your annual household income?">
                                            <option value="$23,340 to $35,010" @if($leadReviews->annual_income == "$23,340 to $35,010") selected @endif>$23,340 to $35,010</option>
                                            <option value="$35,011 to $46,680" @if($leadReviews->annual_income == "$35,011 to $46,680") selected @endif>$35,011 to $46,680</option>
                                            <option value="Over $46,680" @if($leadReviews->annual_income == "Over $46,680") selected @endif>Over $46,680</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="numberOfitem">is Lead Completed ? (Convert To UnSold Lead)</label>
                        <select class="select2 form-control" name="completed" id="Completed" data-placeholder="Choose ...">
                            <optgroup label="is Lead Completed">
                                <option value="0" @if($leadReviews->is_completed == 0) selected @endif>NO</option>
                                <option value="1" @if($leadReviews->is_completed == 1) selected @endif>YES ,Convert To UnSold Lead </option>
                            </optgroup>
                        </select>
                    </div>
                @endif

                <input type="hidden" class="form-control" name="is_multi_service" value="{{ $leadReviews->is_multi_service }}">
                <input type="hidden" class="form-control" name="lead_browser_name" id="lead_browser_name" value="{{ $leadReviews->lead_browser_name }}" readonly>
                <input type="hidden" class="form-control" name="lead_timeInBrowseData" id="lead_timeInBrowseData" value="{{ $leadReviews->lead_timeInBrowseData }}" readonly>
                <input type="hidden" class="form-control" name="lead_ipaddress" id="lead_ipaddress" value="{{ $leadReviews->lead_ipaddress }}" readonly>
                <input type="hidden" class="form-control" name="lead_serverDomain" id="lead_serverDomain" value="{{ $leadReviews->lead_serverDomain }}" readonly>
                <input type="hidden" class="form-control" name="website" id="website" value="{{ $leadReviews->lead_website }}" readonly>
                <input type="hidden" class="form-control" name="lead_source_text" id="lead_source_text" value="{{ $leadReviews->lead_source_text }}" readonly>
                <input type="hidden" class="form-control" name="traffic_source" id="traffic_source" value="{{ $leadReviews->traffic_source }}" readonly>
                <input type="hidden" class="form-control" name="google_ts" id="google_ts" value="{{ $leadReviews->google_ts }}" readonly>
                <input type="hidden" class="form-control" name="google_c" id="google_c" value="{{ $leadReviews->google_c }}" readonly>
                <input type="hidden" class="form-control" name="google_g" id="google_g" value="{{ $leadReviews->google_g }}" readonly>
                <input type="hidden" class="form-control" name="google_k" id="google_k" value="{{ $leadReviews->google_k }}" readonly>
                <input type="hidden" class="form-control" name="token" id="token" value="{{ $leadReviews->token }}" readonly>
                <input type="hidden" class="form-control" name="visitor_id" id="visitor_id" value="{{ $leadReviews->visitor_id }}" readonly>
                <input type="hidden" class="form-control" name="lead_FullUrl" id="lead_FullUrl" value="{{ $leadReviews->lead_FullUrl }}" readonly>
                <input type="hidden" class="form-control" name="lead_aboutUserBrowser" id="lead_aboutUserBrowser" value="{{ $leadReviews->lead_aboutUserBrowser }}" readonly>
                <input type="hidden" class="form-control" name="appointment_date" id="appointment_date" value="{{ $leadReviews->appointment_date }}" readonly>
                <input type="hidden" class="form-control" name="trusted_form" id="trusted_form" value="{{ $leadReviews->trusted_form }}" readonly>
                <input type="hidden" class="form-control" name="universal_leadid" id="universal_leadid" value="{{ $leadReviews->universal_leadid }}" readonly>
                <input type="hidden" class="form-control" name="lead_source" id="lead_source" value="{{ $leadReviews->lead_source }}" readonly>
                <input type="hidden" class="form-control" name="service_id" id="service_id" value="{{ $leadReviews->lead_type_service_id }}" readonly>

                <button type="submit" class="btn btn-success">Submit</button>
            </div>
            </form>
        </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
