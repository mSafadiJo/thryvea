@extends('layouts.NavBuyerHome')

@section('content')
    <!-- Page 5-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="header-title">Campaign Lead Details</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
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
                            @if( $campaignLeads->is_recorded == 1 )
                                @php $lead_phone_number = $campaignLeads->band_width_new_number; @endphp
                            @else
                                @php $lead_phone_number = $campaignLeads->lead_phone_number; @endphp
                            @endif
                            <input type="text" class="form-control" id="phone" value="{{ $lead_phone_number }}" readonly>
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
                @if (!(in_array($campaignLeads->lead_source, array(10,11,12))))
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
                                    <label for="nutureofpro">Type of the project? </label>
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
                    @endif
                @elseif( $campaignLeads->lead_source == 11 )
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
                            <label for="lead_browser_name">JORNAYA Id</label>
                            <input type="text" class="form-control" id="lead_browser_name" value="{{ $campaignLeads->universal_leadid }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
