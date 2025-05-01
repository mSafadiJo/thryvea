@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Campaign Lead Details</h4>
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
                            <input type="text" class="form-control" id="fname" value="{{ $campaignLeads->lead_fname }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" class="form-control" id="lname" value="{{ $campaignLeads->lead_lname }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" value="{{ $campaignLeads->lead_email }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" value="{{ $campaignLeads->lead_phone_number }}" readonly>
                        </div>
                    </div>
                </div>
                @if( $is_with_campaign_details == 1 )
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Buyer Information</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="namebuyers">Buyer Name</label>
                                <input type="text" class="form-control" id="namebuyers" value="{{ $campaignLeads->user_business_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="emailbuyers">Buyer Email</label>
                                <input type="text" class="form-control" id="namebuyers" value="{{ $campaignLeads->email }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phoneb">Phone Number</label>
                                <input type="text" class="form-control" id="phoneb" value="{{ $campaignLeads->user_phone_number }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="Mobileb">Mobile Number</label>
                                <input type="text" class="form-control" id="Mobileb" value="{{ $campaignLeads->user_mobile_number }}" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Campaign Information</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="campname">Campaign Name</label>
                                <input type="text" class="form-control" id="campname" value="{{ $campaignLeads->campaign_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="service">Service</label>
                                <input type="text" class="form-control" id="service" value="{{ $campaignLeads->service_campaign_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
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
                            <input type="text" class="form-control" id="state" value="{{ $campaignLeads->state_name }}" readonly>
                        </div>
                    </div>
                    <?php
                    $city_arr = explode('=>', $campaignLeads->city_name);
                    $county_arr = explode('=>', $campaignLeads->county_name);
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
                            <input type="text" class="form-control" id="Street" value="{{ $campaignLeads->lead_address }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="ZipCode">ZipCode</label>
                            <input type="text" class="form-control" id="ZipCode" value="{{ $campaignLeads->zip_code_list }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Lead Details</h6>
                    </div>
                </div>
                @if( $is_with_campaign_details == 1 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Lead Token</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_id_token_md }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Lead ID</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_id }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="bid">Bid</label>
                                <input type="text" class="form-control" id="bid" value="{{ $campaignLeads->campaigns_leads_users_bid }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif
                @if( $campaignLeads->lead_type_service_id == 1 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project?</label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="Priority">The project is starting</label>
                                <input type="text" class="form-control" id="Priority" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="Ownership">Owner of the Property?</label>
                                <input type="text" class="form-control" id="Ownership" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @else No @endif " readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="numberOfitem">How many windows are involved?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->number_of_windows_c_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 2 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_solor_solution_list_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Property's sun exposure</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_solor_sun_expouser_list_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="numberOfitem">What is the current utility provider for the customer?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_current_utility_provider_id }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">What is the average monthly electricity bill for the customer?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_avg_money_electicity_list_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Property Type</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->property_type_campaign }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 3 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Installation Preferences:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_installation_preferences_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Does the customer have An Existing Alarm And/ Or Monitoring System?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_have_item_before_it == 1 ) Yes @else No @endif" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Property Type</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->property_type_campaign }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 4 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of flooring</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_type_of_flooring_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_nature_flooring_project_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 5 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of Walk-In Tub?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_walk_in_tub_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Desired Features?</label>
                                <ul>
                                    <?php
                                    $datajason = $campaignLeads->lead_desired_featuers_id;
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 6 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of roofing?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_type_of_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_nature_of_roofing_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Property Type</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_property_type_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 7 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of siding?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->type_of_siding_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->nature_of_siding_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 8 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Services required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->service_kitchen_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Demolishing/building walls? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->campaign_kitchen_r_a_walls_status == 1 ) Yes @else No @endif" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 9 )
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="numberOfitem">Services required?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->campaign_bathroomtype_type }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 10 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of stairs? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->stairs_type_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The reason for installing the stairlift </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->stairs_reason_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 11 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of central heating system required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->furnance_type_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 12 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of boiler system required? </label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->furnance_type_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 13 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project?</label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 14 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nutureofpro">Type of the project? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="@if( $campaignLeads->installing_type_campaign_id == 1 ) Cabinet Install @else Cabinet Refacing @endif" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 15 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="nutureofpro">Type of services required? </label>
                                <input type="text" class="form-control" id="nutureofpro" value="{{ $campaignLeads->plumbing_service_list_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 16 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 17 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of the property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_property_type_roofing_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project/services required?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->sunroom_service_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 18 )
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->handyman_ammount_work_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 19 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Countertop material:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->countertops_service_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 20 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Interior/Exterior?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->door_typeproject_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Number of doors involved?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->number_of_door_lead_type }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 21 )
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Gutter meterial:</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->gutters_meterial_lead_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Type of project?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->installing_type_campaign }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 22 )
                    @if( $campaignLeads->paving_service_lead_id == 1 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">The area needing asphalt</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_asphalt_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @elseif( $campaignLeads->paving_service_lead_id == 3 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Material of loose fill required</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_loose_fill_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->paving_best_describes_priject_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 23 )
                    @if( $campaignLeads->painting_service_lead_id == 1 )
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of project</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting1_typeof_project_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of stories of the property</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting1_stories_number_type }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What kinds of surfaces need to be painted and/or stained?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting1_kindsof_surfaces_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                    @elseif( $campaignLeads->painting_service_lead_id == 2 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of rooms need to be painted</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting2_rooms_number_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What needs to be painted?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting2_typeof_paint_type }}" readonly>
                                </div>
                            </div>
                        </div>
                    @elseif( $campaignLeads->painting_service_lead_id == 3 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">Areas need to be painted/stained</label>
                                <ul>
                                    <?php
                                    $datajason = $campaignLeads->painting3_each_feature_id;
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
                    @elseif( $campaignLeads->painting_service_lead_id == 4 )
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Type of service</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Is this location a historical structure?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->historical_structure == 1 ) Yes @else No @endif " readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">Number of stories of the property</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting1_stories_number_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="numberOfitem">The condition of the existing roof</label>
                                <ul>
                                    <?php
                                    $datajason = $campaignLeads->painting4_existing_roof_id;
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
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting_service_lead_type }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="numberOfitem">What surfaces need to be textured?</label>
                                    <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->painting5_surfaces_textured_type }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="numberOfitem">What kind of texturing needed?</label>
                                <ul>
                                    <?php
                                    $datajason = $campaignLeads->painting5_kindof_texturing_id;
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">Owner of the Property?</label>
                                <input type="text" class="form-control" id="numberOfitem" value="@if( $campaignLeads->lead_ownership == 1 ) Yes @elseif( $campaignLeads->lead_ownership == 0 ) No @else No, But Authorized to Make Changes @endif " readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="numberOfitem">The project is starting</label>
                                <input type="text" class="form-control" id="numberOfitem" value="{{ $campaignLeads->lead_priority_name }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 24 )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Auto Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">Vehicle Make Year:</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $campaignLeads->VehicleYear }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Vehicle Brand:</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $campaignLeads->VehicleMake }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Car Model:</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $campaignLeads->car_model }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Current insurance company:</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $campaignLeads->InsuranceCarrier }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Do you have more than one vehicle?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $campaignLeads->more_than_one_vehicle }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Is there more than one driver in your household?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $campaignLeads->driversNum }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Driver's Gender:</label>
                                <input type="text" class="form-control" id="genders" value="{{ $campaignLeads->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="married">Marital State of the driver:</label>
                                <input type="text" class="form-control" id="married" value="{{ $campaignLeads->married }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license">Owner of a valid driving license:</label>
                                <input type="text" class="form-control" id="license" value="{{ $campaignLeads->license }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driver_experience">Is the driver experienced?</label>
                                <input type="text" class="form-control" id="driver_experience" value="{{ $campaignLeads->driver_experience }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="number_of_tickets">Number of tickets or accidents:</label>
                                <input type="text" class="form-control" id="number_of_tickets" value="{{ $campaignLeads->number_of_tickets }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="DUI_charges">DUI charges:</label>
                                <input type="text" class="form-control" id="DUI_charges" value="{{ $campaignLeads->DUI_charges }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="SR_22_need">Need for SR-22:</label>
                                <input type="text" class="form-control" id="SR_22_need" value="{{ $campaignLeads->SR_22_need }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="birthday">Birthdate of the driver:</label>
                                <input type="text" class="form-control" id="birthday" value="{{ $campaignLeads->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="ticket_date">Last ticket date:</label>
                                <input type="text" class="form-control" id="ticket_date" value="{{ $campaignLeads->ticket_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="violation_date">Violation date:</label>
                                <input type="text" class="form-control" id="violation_date" value="{{ $campaignLeads->violation_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="accident_date">Accident date:</label>
                                <input type="text" class="form-control" id="accident_date" value="{{ $campaignLeads->accident_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="claim_date">Insurance claim date:</label>
                                <input type="text" class="form-control" id="claim_date" value="{{ $campaignLeads->claim_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="submodel">Car sub model:</label>
                                <input type="text" class="form-control" id="submodel" value="{{ $campaignLeads->submodel }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="expiration_date">Current insurance expiration date:</label>
                                <input type="text" class="form-control" id="expiration_date" value="{{ $campaignLeads->expiration_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="coverage_type">Insurance coverage type:</label>
                                <input type="text" class="form-control" id="coverage_type" value="{{ $campaignLeads->coverage_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license_status">License status:</label>
                                <input type="text" class="form-control" id="license_status" value="{{ $campaignLeads->license_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license_state">License state:</label>
                                <input type="text" class="form-control" id="license_state" value="{{ $campaignLeads->license_state }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 25 )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Home Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What kind of home Do You Live In?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $campaignLeads->house_type }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Year built ?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $campaignLeads->Year_Built }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Is this your primary residence?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $campaignLeads->primary_residence }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Is this a new purchase?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $campaignLeads->new_purchase }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Have you had insurance within the last 30 days?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $campaignLeads->previous_insurance_within_last30 }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Have you made any home insurance claims in the past 3 years?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $campaignLeads->previous_insurance_claims_last3yrs }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Are you married?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $campaignLeads->married }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">Credit rating</label>
                                <input type="text" class="form-control" id="married" value="{{ $campaignLeads->credit_rating }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="license">When is your birthday?</label>
                                <input type="text" class="form-control" id="license" value="{{ $campaignLeads->birthday }}" readonly>
                            </div>
                        </div>
                    </div>

                @elseif( $campaignLeads->lead_type_service_id == 26 || $campaignLeads->lead_type_service_id == 27 )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Life & Disability Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What's your gender?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $campaignLeads->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Select Your Height?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $campaignLeads->Height }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Enter Your Weight?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $campaignLeads->weight }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">When is your birthday?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $campaignLeads->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Amount of Coverage You are Considering ?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $campaignLeads->amount_coverage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">Are you active or retired military personnel?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $campaignLeads->military_personnel_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Military status ?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $campaignLeads->military_status }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">Service branch ?</label>
                                <input type="text" class="form-control" id="married" value="{{ $campaignLeads->service_branch }}" readonly>
                            </div>
                        </div>
                    </div>

                @elseif( $campaignLeads->lead_type_service_id == 28 )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Business Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What coverage does your business need?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $campaignLeads->CommercialCoverage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">Would you also like to get quotes for your company's benefits?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $campaignLeads->company_benefits_quote }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">When did you start your business?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $campaignLeads->business_start_date }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">What is Your Estimated Annual Employee Payroll in the Next 12 Months?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $campaignLeads->estimated_annual_payroll }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Total # of Employees including Yourself ?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $campaignLeads->number_of_employees }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">When would you like the coverage to begin?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $campaignLeads->coverage_start_month }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Business Name ?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $campaignLeads->business_name }}" readonly>
                            </div>
                        </div>
                    </div>

                @elseif( $campaignLeads->lead_type_service_id == 29 || $campaignLeads->lead_type_service_id == 30 )
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Health & long term Insurance Service</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleYear">What's your gender?</label>
                                <input type="text" class="form-control" id="VehicleYear" value="{{ $campaignLeads->genders }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="VehicleMake">When is your birthday?</label>
                                <input type="text" class="form-control" id="VehicleMake" value="{{ $campaignLeads->birthday }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="car_model">Are you or your spouse pregnant right now , or adopting a child?</label>
                                <input type="text" class="form-control" id="car_model" value="{{ $campaignLeads->pregnancy }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="InsuranceCarrier">Do you use tobacco?</label>
                                <input type="text" class="form-control" id="InsuranceCarrier" value="{{ $campaignLeads->tobacco_usage }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="more_than_one_vehicle">Do you have any of these health conditions?</label>
                                <input type="text" class="form-control" id="more_than_one_vehicle" value="{{ $campaignLeads->health_conditions }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="driversNum">How many people are in your household?</label>
                                <input type="text" class="form-control" id="driversNum" value="{{ $campaignLeads->number_of_people_in_household }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="genders">Is your insurance just for you, or do you plan on covering others?</label>
                                <input type="text" class="form-control" id="genders" value="{{ $campaignLeads->addPeople }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-married">
                                <label for="numberOfitem">What's your annual household income?</label>
                                <input type="text" class="form-control" id="married" value="{{ $campaignLeads->annual_income }}" readonly>
                            </div>
                        </div>
                    </div>
                @elseif( $campaignLeads->lead_type_service_id == 31 )
                    {{-- debt relief --}}
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="debt_amount">Debt Amount?</label>
                                <input type="text" class="form-control" id="debt_amount" value="{{ $campaignLeads->debt_amount }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="debt_type">Debt Type?</label>
                                <ul>
                                    <?php
                                    $datajason = $campaignLeads->debt_type;
                                    $datajasonArray = json_decode($datajason);
                                    ?>
                                    @if( !empty($datajasonArray) )
                                        @foreach( $datajasonArray as $item )
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <h6>Leads Access Log</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Lead Source</label>
                            <input type="text" class="form-control" id="traffic_source" value="{{ $campaignLeads->lead_source_text }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="website">Traffic Source</label>
                            <input type="text" class="form-control" id="traffic_source" value="{{ $campaignLeads->traffic_source }}" readonly>
                        </div>
                    </div>
                </div>
                @if (!(in_array($campaignLeads->lead_source, array(10,11,12))))
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="TCPA_Compliant">TCPA Compliant</label>
                                <input type="text" class="form-control" id="TCPA_Compliant" value="@if( $campaignLeads->tcpa_compliant == 1 ) Yes @else No @endif" readonly>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="TCPA_Language">TCPA Language</label>
                                <textarea class="form-control" readonly>
                                    {{ $campaignLeads->tcpa_consent_text }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lead_browser_name">Browser Name</label>
                                <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->lead_browser_name }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lead_timeInBrowseData">Time In Browse Data</label>
                                <input type="text" class="form-control" id="lead_timeInBrowseData" value="{{ $campaignLeads->lead_timeInBrowseData }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lead_ipaddress">IP Address</label>
                                <input type="text" class="form-control" id="lead_ipaddress" value="{{ $campaignLeads->lead_ipaddress }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lead_serverDomain">Domain</label>
                                <input type="text" class="form-control" id="lead_serverDomain" value="{{ $campaignLeads->lead_serverDomain }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="website">Web Site</label>
                                <input type="text" class="form-control" id="website" value="{{ $campaignLeads->lead_website }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead TS</label>
                                <input type="text" class="form-control" id="google_ts" value="{{ $campaignLeads->google_ts }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead C</label>
                                <input type="text" class="form-control" id="google_c" value="{{ $campaignLeads->google_c }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead G</label>
                                <input type="text" class="form-control" id="google_g" value="{{ $campaignLeads->google_g }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead K</label>
                                <input type="text" class="form-control" id="google_k" value="{{ $campaignLeads->google_k }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead gclid</label>
                                <input type="text" class="form-control" id="google_gclid" value="{{ $campaignLeads->google_gclid }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead S1</label>
                                <input type="text" class="form-control" id="pushnami_s1" value="{{ $campaignLeads->pushnami_s1 }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead S2</label>
                                <input type="text" class="form-control" id="pushnami_s2" value="{{ $campaignLeads->pushnami_s2 }}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="website">Lead S3</label>
                                <input type="text" class="form-control" id="pushnami_s3" value="{{ $campaignLeads->pushnami_s3 }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="website">Lead Token</label>
                                <input type="text" class="form-control" id="token" value="{{ $campaignLeads->token }}" readonly>
                            </div>
                        </div>
                        {{--                        <div class="col-sm-3">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                @php--}}
                        {{--                                    if( $campaignLeads->google_ts == 'p1' ){--}}
                        {{--                                       $id = "1GB471GA074GA54EB40B";--}}
                        {{--                                    } else if( $campaignLeads->google_ts == 'p2' ) {--}}
                        {{--                                       $id = "1GB470GA077G45B27CC6";--}}
                        {{--                                    } else {--}}
                        {{--                                       $id = "1GB3F8GA096GAFA46C6A";--}}
                        {{--                                    }--}}
                        {{--                                @endphp--}}
                        {{--                                <label for="website">Send Conversion(Token)</label>--}}
                        {{--                                <a href="https://tracking.propelmedia.com/?id={{ $id }}&value=0&token={{ $campaignLeads->token }}"--}}
                        {{--                                   class="btn btn-success btn-block" target="_blank">Send Conversion</a>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="website">Lead Visitor ID</label>
                                <input type="text" class="form-control" id="visitor_id" value="{{ $campaignLeads->visitor_id }}" readonly>
                            </div>
                        </div>
                        {{--                        <div class="col-sm-3">--}}
                        {{--                            <div class="form-group">--}}
                        {{--                                <label for="website">Send Conversion(Visitor ID)</label>--}}
                        {{--                                @if( $campaignLeads->is_verified_phone == 0 )--}}
                        {{--                                    <a href="http://ad.propellerads.com/conversion.php?aid=874155&pid=&tid=80572&visitor_id={{ $campaignLeads->visitor_id }}"--}}
                        {{--                                       class="btn btn-success btn-block" target="_blank">Send Conversion</a>--}}
                        {{--                                @else--}}
                        {{--                                    <a href="#" class="btn btn-success btn-block" target="_blank">It was verified</a>--}}
                        {{--                                @endif--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lead_FullUrl">Full URL</label>
                                <textarea class="form-control" readonly>
                                    {{ $campaignLeads->lead_FullUrl }}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lead_aboutUserBrowser">About User Browser</label>
                                <textarea class="form-control" readonly>
                                    {{ $campaignLeads->lead_aboutUserBrowser }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endif
                @if( $campaignLeads->lead_source == 11 || !empty($campaignLeads->appointment_date))
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>Appointment Date</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_browser_name">Appointment Date</label>
                                <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->appointment_date }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif

                <hr>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Trusted Form</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->trusted_form }}" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="lead_browser_name">Lead Id</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->universal_leadid }}" readonly>
                        </div>
                    </div>
                </div>
                @php
                    $permission_users = array();
                    if( !empty(Auth::user()->permission_users) ){
                        $permission_users = json_decode(Auth::user()->permission_users, true);
                    }
                @endphp
                @if( empty($permission_users) || in_array('8-16', $permission_users) )
                    @if( !empty($campaignLeads->lead_ping_id) )
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Affiliate Details</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="lead_browser_name">Purchasing Price</label>
                                    <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->ping_price }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="response_data">Response Status</label>
                                    <input type="text" class="form-control" id="response_data" value="{{ $campaignLeads->response_data }}" readonly>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {{--                <hr>--}}
                {{--                <div class="row">--}}
                {{--                    <div class="col-sm-12">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <label for="RecordedleadcallURL">Recorded lead call URL</label>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    @php--}}
                {{--                        $lead_recorded = \App\Models\Record::where('to', "+1".$campaignLeads->band_width_new_number)->get(['mediaUrl']);--}}
                {{--                        $lead_recorded_url = "";--}}
                {{--                    @endphp--}}
                {{--                    @if( !empty($lead_recorded) )--}}
                {{--                        @foreach ($lead_recorded as $key => $val)--}}
                {{--                            <?php $key++; ?>--}}
                {{--                            <div class="col-sm-2">--}}
                {{--                                <div class="form-group">--}}
                {{--                                    <?php echo "$key- <a href='$val->mediaUrl'>Call #$key</a><br>"; ?>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        @endforeach--}}
                {{--                    @endif--}}
                {{--                </div>--}}

                @if( empty($permission_users) || in_array('8-21', $permission_users) )
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>CRM Responses</h6>
                        </div>
                    </div>
                    @php
                        $response_ping_arr = \App\Models\CrmResponsePing::where('lead_id', $campaignLeads->lead_id)->get();
                        $response_ping = "";
                        $url_input_ping = "";
                        if( !empty($response_ping_arr) ){
                            foreach ($response_ping_arr as $item ){
                                if( !empty( $item->campaign_id ) ){
                                    $campaign = App\Campaign::where('campaign_id', $item->campaign_id)->first();
                                    $campaign_name = $campaign['campaign_name'];
                                    $response_ping .= "Campaign $campaign_name: \n";
                                    $url_input_ping .= "Campaign $campaign_name: \n";
                                }
                                $response_ping .= $item->response . "\n";
                                $response_ping .= "=================================================================\n";

                                $url_input_ping .= "URL: " . $item->url . "\n";
                                $url_input_ping .= "=================================================================\n";
                                $url_input_ping .= "Input: " . $item->inputs . "\n";
                                $url_input_ping .= "=================================================================\n";
                                $url_input_ping .= "=================================================================\n";
                            }
                        }
                    @endphp
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_browser_name">CRM PING URL + Input</label>
                                <textarea class="form-control h-200" readonly>
                                    {{ $url_input_ping }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_browser_name">CRM PING Responses</label>
                                <textarea class="form-control h-200" readonly>
                                     {{ $response_ping }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @php
                        $response_post_arr = App\CrmResponse::where('campaigns_leads_users_id', $campaignLeads->lead_id)->get();
                        $response_post = "";
                        $url_input_post = "";
                        if( !empty($response_post_arr) ){
                            foreach ($response_post_arr as $item ){
                                if( !empty( $item->campaign_id ) ){
                                    $campaign = App\Campaign::where('campaign_id', $item->campaign_id)->first();
                                    $campaign_name = $campaign['campaign_name'];
                                    $response_post .= "Campaign $campaign_name: \n";
                                    $url_input_post .= "Campaign $campaign_name: \n";
                                }
                                $response_post .= $item->response . "\n";
                                $response_post .= "=================================================================\n";

                                $url_input_post .= "URL: " . $item->url . "\n";
                                $url_input_post .= "=================================================================\n";
                                $url_input_post .= "Input: " . $item->inputs . "\n";
                                $url_input_post .= "=================================================================\n";
                                $url_input_post .= "=================================================================\n";
                            }
                        }
                    @endphp
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_browser_name">CRM POST URL + Input</label>
                                <textarea class="form-control h-200" readonly>
                                    {{ $url_input_post }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="lead_browser_name">CRM POST Responses</label>
                                <textarea class="form-control h-200" readonly>
                                    {{ $response_post }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
@endsection
