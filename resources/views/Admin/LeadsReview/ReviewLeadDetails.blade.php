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
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Information</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" class="form-control" id="fname" value="{{ $leadReviews->lead_fname }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" value="{{ $leadReviews->lead_lname }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" value="{{ $leadReviews->lead_email }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="{{ $leadReviews->lead_phone_number }}" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Address</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" value="{{ $leadReviews->state_name }}" readonly>
                        </div>
                    </div>
                    <?php
                    $city_arr = explode('=>', $leadReviews->city_name);
                    $county_arr = explode('=>', $leadReviews->county_name);
                    ?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="county">County</label>
                            <input type="text" class="form-control" id="county" value="{{ $county_arr[0] }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" value="{{ $city_arr[0] }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label for="Street">Street Address</label>
                            <input type="text" class="form-control" id="Street" value="{{ $leadReviews->lead_address }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ZipCode">ZipCode</label>
                            <input type="text" class="form-control" id="ZipCode" value="{{ $leadReviews->zip_code_list }}" readonly>
                        </div>
                    </div>
                </div>

                {{--For Lead Reviews Data--}}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="CitiesSelected">Cities Selected</label>
                            <input type="text" class="form-control" id="CitiesSelected" value="{{ $leadReviews->cities_changes }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ZipCodeSelected<">ZipCodes Selected</label>
                            <input type="text" class="form-control" id="ZipCodeSelected" value="{{ $leadReviews->zipcodes_changes }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Details</h6>
                    </div>
                </div>
                @php
                    $is_multi_service = $leadReviews->is_multi_service;
                    if( $is_multi_service == 1 ){
                        $lead_type_service_id = json_decode($leadReviews->lead_type_service_id, true);
                        $installing_type_campaign = json_decode($leadReviews->lead_installing_id, true);
                    } else {
                        $lead_type_service_id[] = $leadReviews->lead_type_service_id;
                        $installing_type_campaign = $leadReviews->installing_type_campaign;
                        $lead_installing_id = $leadReviews->lead_installing_id;
                    }
                @endphp
                @if( in_array(1, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Window Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project?</label>
                                <input type="text" class="form-control" id="nutureofpro" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[1]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">How many windows are involved?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->number_of_windows_c_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(2, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Solar Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_solor_solution_list_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Property's sun exposure</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_solor_sun_expouser_list_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">What is the current utility provider for the customer?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_current_utility_provider_id }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">What is the average monthly electricity bill for the customer?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_avg_money_electicity_list_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(3, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Home Security Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Installation Preferences:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_installation_preferences_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Does the customer have An Existing Alarm And/ Or Monitoring System?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->lead_have_item_before_it == 1 ) Yes @else No @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(4, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Flooring Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of flooring</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_type_of_flooring_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_nature_flooring_project_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(5, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>WALK-IN TUB Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of Walk-In Tub?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_walk_in_tub_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Desired Features?</label>
                                <ul>
                                    <?php
                                    $datajason = $leadReviews->lead_desired_featuers_id;
                                    $datajasonArray = json_decode($datajason);
                                    ?>
                                    @if( !empty($datajasonArray) )
                                        @foreach( $listOFlead_desired_featuers as $itm )
                                            @if( in_array($itm->lead_desired_featuers_id, $datajasonArray) )
                                                <li>{{ $itm->lead_desired_featuers_name }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(6, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Roofing Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of roofing?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_type_of_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_nature_of_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Property Type</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_property_type_roofing_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(7, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Home Siding Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of siding?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->type_of_siding_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->nature_of_siding_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(8, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Kitchen Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Services required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->service_kitchen_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Demolishing/building walls? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->campaign_kitchen_r_a_walls_status == 1 ) Yes @else No @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(9, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Bathroom Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="numberOfitem">Services required?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->campaign_bathroomtype_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(10, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>StairLift Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of stairs? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->stairs_type_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The reason for installing the stairlift </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->stairs_reason_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(11, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Furnace Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[11]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of central heating system required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if($is_multi_service == 1) {{ $leadReviews->furnance_type_lead_type_B }} @else {{ $leadReviews->furnance_type_lead_type_A }} @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(12, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Boiler Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[12]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of boiler system required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if($is_multi_service == 1) {{ $leadReviews->furnance_type_lead_type_C }} @else {{ $leadReviews->furnance_type_lead_type_A }} @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(13, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Central A/C Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project?</label>
                                <input type="text" class="form-control" id="nutureofpro" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[13]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(14, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Cabinet Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                @php
                                    if($is_multi_service == 1){
                                        if(is_array($installing_type_campaign)){
                                            $lead_installing_id = $installing_type_campaign[14];
                                        }
                                    }
                                @endphp
                                <input type="text" class="form-control" id="nutureofpro" value="@if( $lead_installing_id == 1 ) Cabinet Install @else Cabinet Refacing @endif" readonly>

                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(15, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Plumbing Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="nutureofpro">Type of services required? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $leadReviews->plumbing_service_list_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(16, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Bathtub Service</h6>
                        </div>
                    </div>
                @endif
                @if( in_array(17, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>SunRoom Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->lead_property_type_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project/services required?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->sunroom_service_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(18, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Handyman Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->handyman_ammount_work_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(19, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>CounterTop Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">CounterTop material:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->countertops_service_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[19]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(20, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Door Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Interior/Exterior?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->door_typeproject_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Number of doors involved?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->number_of_door_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[20]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(21, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Gutter Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Gutter meterial:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->gutters_meterial_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if($is_multi_service == 1) @php if(is_array($installing_type_campaign)){$installing_name = $lead_installing_array[$installing_type_campaign[21]]; }else{$installing_name = "";} @endphp {{ $installing_name }} @else {{ $installing_type_campaign }} @endif" readonly>
                            </div>
                        </div>
                    </div>



                @elseif( in_array(22, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Paving Service</h6>
                        </div>
                    </div>
                    @if( $leadReviews->paving_service_lead_id == 1 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The area needing asphalt</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_asphalt_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>

                    @elseif( $leadReviews->paving_service_lead_id == 3 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Material of loose fill required</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_loose_fill_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif( in_array(23, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Painting Service</h6>
                        </div>
                    </div>
                    @if( $leadReviews->painting_service_lead_id == 1 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_typeof_project_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of stories of the property</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_stories_number_type }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What kinds of surfaces need to be painted and/or stained?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_kindsof_surfaces_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->painting_service_lead_id == 2 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of rooms need to be painted</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting2_rooms_number_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What needs to be painted?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting2_typeof_paint_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @elseif( $leadReviews->painting_service_lead_id == 3 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">Areas need to be painted/stained</label>
                                <ul>
                                    <?php
                                    $datajason = $leadReviews->painting3_each_feature_id;
                                    $datajasonArray = json_decode($datajason, true);
                                    ?>
                                    @if( !empty($datajasonArray) )
                                        @foreach( $painting3_each_feature as $itm )
                                            @if( in_array($itm->painting3_each_feature_id, $datajasonArray) )
                                                <li>{{ $itm->painting3_each_feature_type }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @elseif( $leadReviews->painting_service_lead_id == 4 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $leadReviews->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of stories of the property</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting1_stories_number_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="numberOfitem">The condition of the existing roof</label>
                                <ul>
                                    <?php
                                    $datajason = $leadReviews->painting4_existing_roof_id;
                                    $datajasonArray = json_decode($datajason, true);
                                    ?>
                                    @if( !empty($datajasonArray) )
                                        @foreach( $painting4_existing_roof as $itm )
                                            @if( in_array($itm->painting4_existing_roof_id, $datajasonArray) )
                                                <li>{{ $itm->painting4_existing_roof_type }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What surfaces need to be textured?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->painting5_surfaces_textured_type }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">What kind of texturing needed?</label>
                                <ul>
                                    <?php
                                    $datajason = $leadReviews->painting5_kindof_texturing_id;
                                    $datajasonArray = json_decode($datajason, true);
                                    ?>
                                    @if( !empty($datajasonArray) )
                                        @foreach( $painting5_kindof_texturing as $itm )
                                            @if( in_array($itm->painting5_kindof_texturing_id, $datajasonArray) )
                                                <li>{{ $itm->painting5_kindof_texturing_type }}</li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
                @endif
                @if( in_array(24, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Auto Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">Vehicle Make Year:</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $leadReviews->VehicleYear }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Vehicle Brand:</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $leadReviews->VehicleMake }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Car Model:</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $leadReviews->car_model }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Current insurance company:</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $leadReviews->InsuranceCarrier }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Do you have more than one vehicle?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $leadReviews->more_than_one_vehicle }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Is there more than one driver in your household?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $leadReviews->driversNum }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Driver's Gender:</label>
                                <input type="text" class="form-control" id="genders" value="{{ $leadReviews->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">Marital State of the driver:</label>
                                <input type="text" class="form-control" id="married" value="{{ $leadReviews->married }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license">Owner of a valid driving license:</label>
                                <input type="text" class="form-control" id="license" value="{{ $leadReviews->license }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driver_experience">Is the driver experienced?</label>
                                <input type="text" class="form-control" id="driver_experience" value="{{ $leadReviews->driver_experience }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="number_of_tickets">Number of tickets or accidents:</label>
                                <input type="text" class="form-control" id="number_of_tickets" value="{{ $leadReviews->number_of_tickets }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="DUI_charges">DUI charges:</label>
                                <input type="text" class="form-control" id="DUI_charges" value="{{ $leadReviews->DUI_charges }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="SR_22_need">Need for SR-22:</label>
                                <input type="text" class="form-control" id="SR_22_need" value="{{ $leadReviews->SR_22_need }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="birthday">Birthdate of the driver:</label>
                                <input type="text" class="form-control" id="birthday" value="{{ $leadReviews->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="ticket_date">Last ticket date:</label>
                                <input type="text" class="form-control" id="ticket_date" value="{{ $leadReviews->ticket_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="violation_date">Violation date:</label>
                                <input type="text" class="form-control" id="violation_date" value="{{ $leadReviews->violation_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="accident_date">Accident date:</label>
                                <input type="text" class="form-control" id="accident_date" value="{{ $leadReviews->accident_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="claim_date">Insurance claim date:</label>
                                <input type="text" class="form-control" id="claim_date" value="{{ $leadReviews->claim_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="submodel">Car sub model:</label>
                                <input type="text" class="form-control" id="submodel" value="{{ $leadReviews->submodel }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="expiration_date">Current insurance expiration date:</label>
                                <input type="text" class="form-control" id="expiration_date" value="{{ $leadReviews->expiration_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="coverage_type">Insurance coverage type:</label>
                                <input type="text" class="form-control" id="coverage_type" value="{{ $leadReviews->coverage_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license_status">License status:</label>
                                <input type="text" class="form-control" id="license_status" value="{{ $leadReviews->license_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license_state">License state:</label>
                                <input type="text" class="form-control" id="license_state" value="{{ $leadReviews->license_state }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(25, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Home Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What kind of home Do You Live In?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $leadReviews->house_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Year built ?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $leadReviews->Year_Built }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Is this your primary residence?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $leadReviews->primary_residence }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Is this a new purchase?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $leadReviews->new_purchase }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Have you had insurance within the last 30 days?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $leadReviews->previous_insurance_within_last30 }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Have you made any home insurance claims in the past 3 years?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $leadReviews->previous_insurance_claims_last3yrs }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Are you married?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $leadReviews->married }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">Credit rating</label>
                                <input type="text" class="form-control" id="married" value="{{ $leadReviews->credit_rating }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license">When is your birthday?</label>
                                <input type="text" class="form-control" id="license" value="{{ $leadReviews->birthday }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(26, $lead_type_service_id) || in_array(27, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Life & Disability Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What's your gender?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $leadReviews->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Select Your Height?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $leadReviews->Height }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Enter Your Weight?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $leadReviews->weight }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">When is your birthday?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $leadReviews->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Amount of Coverage You are Considering ?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $leadReviews->amount_coverage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Are you active or retired military personnel?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $leadReviews->military_personnel_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Military status ?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $leadReviews->military_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">Service branch ?</label>
                                <input type="text" class="form-control" id="married" value="{{ $leadReviews->service_branch }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(28, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Business Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What coverage does your business need?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $leadReviews->CommercialCoverage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Would you also like to get quotes for your company's benefits?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $leadReviews->company_benefits_quote }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">When did you start your business?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $leadReviews->business_start_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">What is Your Estimated Annual Employee Payroll in the Next 12 Months?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $leadReviews->estimated_annual_payroll }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Total # of Employees including Yourself ?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $leadReviews->number_of_employees }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">When would you like the coverage to begin?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $leadReviews->coverage_start_month }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Business Name ?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $leadReviews->business_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( in_array(29, $lead_type_service_id) || in_array(30, $lead_type_service_id) )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Health & long term Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What's your gender?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $leadReviews->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">When is your birthday?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $leadReviews->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Are you or your spouse pregnant right now , or adopting a child?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $leadReviews->pregnancy }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Do you use tobacco?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $leadReviews->tobacco_usage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Do you have any of these health conditions?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $leadReviews->health_conditions }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">How many people are in your household?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $leadReviews->number_of_people_in_household }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Is your insurance just for you, or do you plan on covering others?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $leadReviews->addPeople }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">What's your annual household income?</label>
                                <input type="text" class="form-control" id="married" value="{{ $leadReviews->annual_income }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Default Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="Priority">The project is starting</label>
                            <input type="text" class="form-control" id="Priority" value="{{ $leadReviews->lead_priority_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="Ownership">Owner of the Property?</label>
                            <input type="text" class="form-control" id="Ownership" value="@if( $leadReviews->lead_ownership == 1 ) Yes @elseif( $leadReviews->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="numberOfitem">Property Type</label>
                            <input type="text" class="form-control" id="numberOfitem" value="{{ $leadReviews->property_type_campaign }}" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Leads Access Log</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_browser_name">Browser Name</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $leadReviews->lead_browser_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_timeInBrowseData">Time In Browse Data</label>
                            <input type="text" class="form-control" id="lead_timeInBrowseData" value="{{ $leadReviews->lead_timeInBrowseData }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="lead_ipaddress">IP Address</label>
                            <input type="text" class="form-control" id="lead_ipaddress" value="{{ $leadReviews->lead_ipaddress }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_serverDomain">Domain</label>
                            <input type="text" class="form-control" id="lead_serverDomain" value="{{ $leadReviews->lead_serverDomain }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Web Site</label>
                            <input type="text" class="form-control" id="website" value="{{ $leadReviews->lead_website }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Lead Source</label>
                            <input type="text" class="form-control" id="traffic_source" value="{{ $leadReviews->lead_source_text }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Traffic Source</label>
                            <input type="text" class="form-control" id="traffic_source" value="{{ $leadReviews->traffic_source }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead TS</label>
                            <input type="text" class="form-control" id="google_ts" value="{{ $leadReviews->google_ts }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead C</label>
                            <input type="text" class="form-control" id="google_c" value="{{ $leadReviews->google_c }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead G</label>
                            <input type="text" class="form-control" id="google_g" value="{{ $leadReviews->google_g }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead K</label>
                            <input type="text" class="form-control" id="google_k" value="{{ $leadReviews->google_k }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead gclid</label>
                            <input type="text" class="form-control" id="google_gclid" value="{{ $leadReviews->google_gclid }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead S1</label>
                            <input type="text" class="form-control" id="pushnami_s1" value="{{ $leadReviews->pushnami_s1 }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead S2</label>
                            <input type="text" class="form-control" id="pushnami_s2" value="{{ $leadReviews->pushnami_s2 }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="website">Lead S3</label>
                            <input type="text" class="form-control" id="pushnami_s3" value="{{ $leadReviews->pushnami_s3 }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Lead Token</label>
                            <input type="text" class="form-control" id="token" value="{{ $leadReviews->token }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Lead Visitor ID</label>
                            <input type="text" class="form-control" id="visitor_id" value="{{ $leadReviews->visitor_id }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_FullUrl">Full URL</label>
                            <textarea class="form-control" readonly>
                                    {{ $leadReviews->lead_FullUrl }}
                                </textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_aboutUserBrowser">About User Browser</label>
                            <textarea class="form-control" readonly>
                                    {{ $leadReviews->lead_aboutUserBrowser }}
                                </textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Trusted Form</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $leadReviews->trusted_form }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Lead Id</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $leadReviews->universal_leadid }}" readonly>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
