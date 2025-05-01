<div class="questions_div_section_campaign div_service1_section_camp">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="number_of_windows_c">Number Of Windows<span class="requiredFields">*</span></label>
                <select id="number_of_windows_c" name="number_of_windows_c[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Number Of Windows">
                        <option value="1" @if( in_array("1", json_decode($campaign->number_of_window,true)) ) selected @endif> 1 </option>
                        <option value="2" @if( in_array("2", json_decode($campaign->number_of_window,true)) ) selected @endif> 2 </option>
                        <option value="3" @if( in_array("3", json_decode($campaign->number_of_window,true)) ) selected @endif> 3-5 </option>
                        <option value="4" @if( in_array("4", json_decode($campaign->number_of_window,true)) ) selected @endif> 6-9 </option>
                        <option value="5" @if( in_array("5", json_decode($campaign->number_of_window,true)) ) selected @endif> 10+ </option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service2_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label for="solorBill">Customer's Average monthly electricity bill<span class="requiredFields">*</span></label>
                <select id="solorBill" name="solorBill[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Customer's Average monthly electricity bill">
                        <option value="6" @if( in_array("6", json_decode($campaign->solar_bill,true)) ) selected @endif> $0 - $50 </option>
                        <option value="1" @if( in_array("1", json_decode($campaign->solar_bill,true)) ) selected @endif> $51 - $100 </option>
                        <option value="7" @if( in_array("7", json_decode($campaign->solar_bill,true)) ) selected @endif> $101 - $150 </option>
                        <option value="2" @if( in_array("2", json_decode($campaign->solar_bill,true)) ) selected @endif> $151 - $200 </option>
                        <option value="3" @if( in_array("3", json_decode($campaign->solar_bill,true)) ) selected @endif> $201 - $300 </option>
                        <option value="8" @if( in_array("8", json_decode($campaign->solar_bill,true)) ) selected @endif> $301 - $400 </option>
                        <option value="4" @if( in_array("4", json_decode($campaign->solar_bill,true)) ) selected @endif> $401 - $500 </option>
                        <option value="5" @if( in_array("5", json_decode($campaign->solar_bill,true)) ) selected @endif> $500+ </option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="Solarpowersolution">Kind of Solar power solution<span class="requiredFields">*</span></label>
                <select id="Solarpowersolution" name="Solarpowersolution[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Kind of Solar power solution">
                        <option value="1" @if( in_array("1", json_decode($campaign->solar_power_solution,true)) ) selected @endif> Solar Electricity for my Home </option>
                        <option value="2" @if( in_array("2", json_decode($campaign->solar_power_solution,true)) ) selected @endif> Solar Water Heating for my Home </option>
                        <option value="3" @if( in_array("3", json_decode($campaign->solar_power_solution,true)) ) selected @endif> Solar Electricity & Water Heating for my Home </option>
                        <option value="4" @if( in_array("4", json_decode($campaign->solar_power_solution,true)) ) selected @endif> Solar for my Business </option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="RoofShade">Sun exposure, Roof Shade<span class="requiredFields">*</span></label>
                <select id="RoofShade" name="RoofShade[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Sun exposure, Roof Shade">
                        <option value="1" @if( in_array("1", json_decode($campaign->roof_shade,true)) ) selected @endif> Full Sun </option>
                        <option value="2" @if( in_array("2", json_decode($campaign->roof_shade,true)) ) selected @endif> Partial Sun </option>
                        <option value="3" @if( in_array("3", json_decode($campaign->roof_shade,true)) ) selected @endif> Mostly Shaded </option>
                        <option value="4" @if( in_array("4", json_decode($campaign->roof_shade,true)) ) selected @endif> Not Sure </option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <p class="is_ping_account_campaign">
                    If you want to filter by Utility Providers, turn the toggle on
                    <label class="switch crmSwitch">
                        <input type="checkbox" name="is_utility_providers" id="is_utility_providers" value="1" @if($campaign->is_utility_solar_filter == 1) checked @endif>
                        <span class="slider round coolToolsToggle "></span>
                    </label>
                </p>
            </div>
        </div>
        <div class="campaign_utility_providers col-sm-12" @if($campaign->is_utility_solar_filter != 1) style="display: none" @endif>
            <div class="form-group">
                <label for="utility_providers">Utility Providers<span class="requiredFields">*</span></label>
                <select id="utility_providers_select" name="Utility_Providers[]" class="select2 form-control select2-multiple questions_div_section_select_campaign UtilityProvidersSelect" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Utility Providers">
                        @if(!empty($utility_providers))
                            @foreach($utility_providers as $item)
                                @if(empty(json_decode($campaign->utility_providers, true)))
                                    <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_name }}">{{ $item->lead_current_utility_provider_name }}</option>
                                @else
                                    <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_name }}"
                                            @if(in_array(strtolower($item->lead_current_utility_provider_name), json_decode($campaign->utility_providers,true))) selected @endif
                                    >{{ $item->lead_current_utility_provider_name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </optgroup>
                </select>
                <input type="button" class="select_all select_all_select" data-classes="UtilityProvidersSelect" value="Select All">
                <input type="button" class="clear_all_state clear_all_select" data-classes="UtilityProvidersSelect" value="Clear All">
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service3_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="securityInstalling">Installation Preferences<span class="requiredFields">*</span></label>
                <select id="securityInstalling" name="securityInstalling[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Installation Preferences">
                        <option value="1" @if( in_array("1", json_decode($campaign->security_installing,true)) ) selected @endif> Professional installation </option>
                        <option value="2" @if( in_array("2", json_decode($campaign->security_installing,true)) ) selected @endif> Self installation </option>
                        <option value="3" @if( in_array("3", json_decode($campaign->security_installing,true)) ) selected @endif> No preference </option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="ExistingMonitoringSystem">Customer Has An Existing Alarm And/ Or Monitoring System<span class="requiredFields">*</span></label>
                <select id="ExistingMonitoringSystem" name="ExistingMonitoringSystem[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Customer Has An Existing Alarm And/ Or Monitoring System">
                        <option value="1" @if( in_array("1", json_decode($campaign->existing_monitoring_system,true)) ) selected @endif>Yes</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->existing_monitoring_system,true)) ) selected @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service4_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="flooringtype">Type of flooring<span class="requiredFields">*</span></label>
                <select id="flooringtype" name="flooringtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of flooring">
                        <option value="1" @if( in_array("1", json_decode($campaign->flooring_type,true)) ) selected @endif>Vinyl Linoleum Flooring</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->flooring_type,true)) ) selected @endif>Tile Flooring</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->flooring_type,true)) ) selected @endif>Hardwood Flooring</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->flooring_type,true)) ) selected @endif>Laminate Flooring</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->flooring_type ,true)) ) selected @endif>Carpet</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service5_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="lead_walk_in_tub">What kind of Walk-In Tubs?<span class="requiredFields">*</span></label>
                <select id="lead_walk_in_tub" name="lead_walk_in_tub[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="What kind of Walk-In Tubs?">
                        <option value="1" @if( in_array("1", json_decode($campaign->walk_in_tup_filter,true)) ) selected @endif>Safety</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->walk_in_tup_filter,true)) ) selected @endif>Therapeutic</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->walk_in_tup_filter,true)) ) selected @endif>Others</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service6_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="roofingtype">Type of roofing<span class="requiredFields">*</span></label>
                <select id="roofingtype" name="roofingtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of roofing">
                        <option value="1" @if( in_array("1", json_decode($campaign->roof_type,true)) ) selected @endif>Asphalt Roofing</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->roof_type,true)) ) selected @endif>Wood Shake/Composite Roofing</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->roof_type,true)) ) selected @endif>Metal Roofing</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->roof_type,true)) ) selected @endif>Natural Slate Roofing</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->roof_type,true)) ) selected @endif>Tile Roofing</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service7_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="roofingtype">Type of siding<span class="requiredFields">*</span></label>
                <select id="sidingtype" name="sidingtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of siding">
                        <option value="1" @if( in_array("1", json_decode($campaign->type_of_siding,true)) ) selected @endif>Vinyl Siding</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->type_of_siding,true)) ) selected @endif>Brickface Siding</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->type_of_siding,true)) ) selected @endif>Composite wood Siding</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->type_of_siding,true)) ) selected @endif>Aluminum Siding</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->type_of_siding,true)) ) selected @endif>Stoneface Siding</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->type_of_siding,true)) ) selected @endif>Fiber Cement Siding</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service8_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="kitchen_service">What services of kitchen remodeling you do?<span class="requiredFields">*</span></label>
                <select id="kitchen_service" name="kitchen_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="What services of kitchen remodeling you do?">
                        <option value="1" @if( in_array("1", json_decode($campaign->kitchen_service,true)) ) selected @endif>Full Kitchen Remodeling</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->kitchen_service,true)) ) selected @endif>Cabinet Refacing</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->kitchen_service,true)) ) selected @endif>Cabinet Install</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="removing_adding_walls">Are you able to demolish/build walls?<span class="requiredFields">*</span></label>
                <select id="removing_adding_walls" name="removing_adding_walls[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Are you able to demolish/build walls?">
                        <option value="1" @if( in_array("1", json_decode($campaign->kitchen_walls,true)) ) selected @endif>Yes</option>
                        <option value="0" @if( in_array("0", json_decode($campaign->kitchen_walls ,true)) ) selected @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service9_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="bathroom_service">What services of Bathroom remodeling you do?<span class="requiredFields">*</span></label>
                <select id="bathroom_service" name="bathroom_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="What services of Bathroom remodeling you do?">
                        <option value="1" @if( in_array("1", json_decode($campaign->bathroom_type,true)) ) selected @endif>Full Remodel</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->bathroom_type,true)) ) selected @endif>Cabinets / Vanity</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->bathroom_type,true)) ) selected @endif>Countertops</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->bathroom_type,true)) ) selected @endif>Flooring</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->bathroom_type,true)) ) selected @endif>Shower / Bath</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->bathroom_type,true)) ) selected @endif>Sinks</option>
                        <option value="7" @if( in_array("7", json_decode($campaign->bathroom_type,true)) ) selected @endif>Toilet</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service10_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="stairlift_type">Type of stairs <span class="requiredFields">*</span></label>
                <select id="stairlift_type" name="stairlift_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of stairs">
                        <option value="1" @if( in_array("1", json_decode($campaign->stairs_type,true)) ) selected @endif>Straight</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->stairs_type ,true)) ) selected @endif>Curved</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="stairlift_reason">Stair lift specification <span class="requiredFields">*</span></label>
                <select id="stairlift_reason" name="stairlift_reason[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Stairlift specification">
                        <option value="1" @if( in_array("1", json_decode($campaign->stairs_reason,true)) ) selected @endif>Mobility</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->stairs_reason ,true)) ) selected @endif>Safety</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->stairs_reason ,true)) ) selected @endif>Other</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service11_section_camp div_service12_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="furnance_type">Type of <span id="furnance_type_label"></span> <span class="requiredFields">*</span></label>
                <select id="furnance_type" name="furnance_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <option value="1" @if( in_array("1", json_decode($campaign->furnace_type,true)) ) selected @endif>Do Not Know</option>
                    <option value="2" @if( in_array("2", json_decode($campaign->furnace_type ,true)) ) selected @endif>Electric</option>
                    <option value="3" @if( in_array("3", json_decode($campaign->furnace_type ,true)) ) selected @endif>Natural Gas</option>
                    <option value="4" @if( in_array("4", json_decode($campaign->furnace_type ,true)) ) selected @endif>Oil</option>
                    <option value="5" @if( in_array("5", json_decode($campaign->furnace_type ,true)) ) selected @endif>Propane Gas</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service15_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="plumbing_service">Type of Plumbing <span class="requiredFields">*</span></label>
                <select id="plumbing_service" name="plumbing_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of Plumbing">
                        <option value="1" @if( in_array("1", json_decode($campaign->plumbing_service,true)) ) selected @endif>Faucet/ Fixture Services</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Pipe Services</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Leak Repair</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Remodeling/ Construction</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Septic Systems</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->plumbing_service,true)) ) selected @endif>Drain/ Sewer Services</option>
                        <option value="7" @if( in_array("7", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Shower Services</option>
                        <option value="8" @if( in_array("8", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Sump Pump Services</option>
                        <option value="9" @if( in_array("9", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Toilet Services</option>
                        <option value="10" @if( in_array("10", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Water Heater Services</option>
                        <option value="11" @if( in_array("11", json_decode($campaign->plumbing_service,true)) ) selected @endif>Water/ Fuel Tank</option>
                        <option value="12" @if( in_array("12", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Water Treatment and Purification</option>
                        <option value="13" @if( in_array("13", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Well Pump Services</option>
                        <option value="14" @if( in_array("14", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Backflow Services</option>
                        <option value="15" @if( in_array("15", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Bathroom Plumbing</option>
                        <option value="16" @if( in_array("16", json_decode($campaign->plumbing_service,true)) ) selected @endif>Camera Line Inspection</option>
                        <option value="17" @if( in_array("17", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Clogged Sink Repair</option>
                        <option value="18" @if( in_array("18", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Disposal Services</option>
                        <option value="19" @if( in_array("19", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Excavation</option>
                        <option value="20" @if( in_array("20", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Grease Trap Services</option>
                        <option value="21" @if( in_array("21", json_decode($campaign->plumbing_service,true)) ) selected @endif>Kitchen Plumbing</option>
                        <option value="22" @if( in_array("22", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Storm Drain Cleaning</option>
                        <option value="23" @if( in_array("23", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Trench less Repairs</option>
                        <option value="24" @if( in_array("24", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Water Damage Restoration</option>
                        <option value="25" @if( in_array("25", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Water Jetting</option>
                        <option value="26" @if( in_array("26", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Water Leak Services</option>
                        <option value="27" @if( in_array("27", json_decode($campaign->plumbing_service ,true)) ) selected @endif>Basement Plumbing</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service17_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="sunroom_service">What kind of services you do? <span class="requiredFields">*</span></label>
                <select id="sunroom_service" name="sunroom_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="What kind of services you do?">
                        <option value="1" @if( in_array("1", json_decode($campaign->sunroom_service,true)) ) selected @endif>Build a new sun room or patio enclosure</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->sunroom_service ,true)) ) selected @endif>Enclose existing porch with roof, walls or windows</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->sunroom_service ,true)) ) selected @endif>Screen in existing porch or patio</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->sunroom_service ,true)) ) selected @endif>Add a metal awning or cover</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->sunroom_service ,true)) ) selected @endif>Add a fabric awning or cover</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->sunroom_service,true)) ) selected @endif>Repair existing sun room, porch or patio</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service18_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="handyman_ammount">What kind of services you do? <span class="requiredFields">*</span></label>
                <select id="handyman_ammount" name="handyman_ammount[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="What kind of services you do?">
                        <option value="1" @if( in_array("1", json_decode($campaign->handyman_amount_work,true)) ) selected @endif>A variety of projects</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->handyman_amount_work ,true)) ) selected @endif>A single project</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service19_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="countertops_service">Type of countertop material you work with <span class="requiredFields">*</span></label>
                <select id="countertops_service" name="countertops_service[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of countertop material you work with">
                        <option value="1" @if( in_array("1", json_decode($campaign->counter_tops_service,true)) ) selected @endif>Granite</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Solid Surface (e.g corian)</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Marble</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Wood (e.g butcher block)</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Stainless Steel</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->counter_tops_service,true)) ) selected @endif>Laminate</option>
                        <option value="7" @if( in_array("7", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Concrete</option>
                        <option value="8" @if( in_array("8", json_decode($campaign->counter_tops_service ,true)) ) selected @endif>Other Solid Stone (e.g Quartz)</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service20_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="door_typeproject">Exterior doors/Interior doors <span class="requiredFields">*</span></label>
                <select id="door_typeproject" name="door_typeproject[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Exterior doors/Interior doors">
                        <option value="1" @if( in_array("1", json_decode($campaign->door_type,true)) ) selected @endif>Exterior</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->door_type ,true)) ) selected @endif>Interior</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="number_of_door">Number of doors <span class="requiredFields">*</span></label>
                <select id="number_of_door" name="number_of_door[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Number of doors">
                        <option value="1" @if( in_array("1", json_decode($campaign->number_of_door,true)) ) selected @endif>1</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->number_of_door ,true)) ) selected @endif>2</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->number_of_door ,true)) ) selected @endif>3</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->number_of_door ,true)) ) selected @endif>4+</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service21_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="gutters_meterial">Type of Gutter material you work with <span class="requiredFields">*</span></label>
                <select id="gutters_meterial" name="gutters_meterial[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of Gutter material you work with">
                        <option value="1" @if( in_array("1", json_decode($campaign->gutters_material,true)) ) selected @endif>Copper</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->gutters_material ,true)) ) selected @endif>Galvanized Steel</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->gutters_material ,true)) ) selected @endif>PVC</option>
                        <option value="4" @if( in_array("4", json_decode($campaign->gutters_material ,true)) ) selected @endif>Seamless Aluminum</option>
                        <option value="5" @if( in_array("5", json_decode($campaign->gutters_material ,true)) ) selected @endif>Wood</option>
                        <option value="6" @if( in_array("6", json_decode($campaign->gutters_material,true)) ) selected @endif>not sure</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service22_section_camp" style="display: none;">
    <div class="row">
        <div class="col-12">
            <h6>What kind of services you do? <span class="requiredFields">*</span></h6>

            <div class="checkbox-group required">
                <label class="container-services">Asphalt Paving - Install
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps1")'
                           name="paving_service[]" value="1" @if( in_array("1", json_decode($campaign->paving_service,true)) ) checked @endif class="paving_service">
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Asphalt Sealing
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps2")'
                           name="paving_service[]" value="2" @if( in_array("2", json_decode($campaign->paving_service,true)) ) checked @endif class="paving_service">
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Gravel or Loose Fill Paving - Install, Spread or Scrape
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps3")'
                           name="paving_service[]" value="3" @if( in_array("3", json_decode($campaign->paving_service,true)) ) checked @endif class="paving_service">
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Asphalt Paving - Repair or Patch
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps4")'
                           name="paving_service[]" value="4" @if( in_array("4", json_decode($campaign->paving_service,true)) ) checked @endif class="paving_service">
                    <span class="checkmark-services"></span>
                </label>
            </div>

            <div id="ps1" class="service-block"
                 @if(!empty(json_decode($campaign->paving_service ,true))) @if( !(in_array( 1, json_decode($campaign->paving_service ,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
                <h6>Asphalt Paving - Install</h6>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="paving_asphalt_type">Areas that you can make the work in? <span class="requiredFields">*</span></label>
                            <select id="paving_asphalt_type" name="paving_asphalt_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                <optgroup label="Areas that you can make the work in?">
                                    <option value="1" @if( in_array("1", json_decode($campaign->paving_asphalt,true)) ) selected @endif>Driveway</option>
                                    <option value="2" @if( in_array("2", json_decode($campaign->paving_asphalt ,true)) ) selected @endif>Road</option>
                                    <option value="3" @if( in_array("3", json_decode($campaign->paving_asphalt ,true)) ) selected @endif>Walkway or sidewalk</option>
                                    <option value="4" @if( in_array("4", json_decode($campaign->paving_asphalt ,true)) ) selected @endif>Patio</option>
                                    <option value="5" @if( in_array("5", json_decode($campaign->paving_asphalt ,true)) ) selected @endif>Sports court (tennis, basketball, etc.)</option>
                                    <option value="6" @if( in_array("6", json_decode($campaign->paving_asphalt ,true)) ) selected @endif>Parking Lot</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ps3" class="service-block"
                 @if(!empty(json_decode($campaign->paving_service ,true))) @if( !(in_array( 3, json_decode($campaign->paving_service ,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
                <h6>Gravel or Loose Fill Paving - Install, Spread or Scrape</h6>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="paving_loose_fill_type">Type of loose fill do you work with<span class="requiredFields">*</span></label>
                            <select id="paving_loose_fill_type" name="paving_loose_fill_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                <optgroup label="Type of loose fill do you work with">
                                    <option value="1" @if( in_array("1", json_decode($campaign->paving_loose_fill,true)) ) selected @endif>Cobblestone</option>
                                    <option value="2" @if( in_array("2", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Crushed rock</option>
                                    <option value="3" @if( in_array("3", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Gravel</option>
                                    <option value="4" @if( in_array("4", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Pebbles</option>
                                    <option value="5" @if( in_array("5", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Road base</option>
                                    <option value="6" @if( in_array("6", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Rock</option>
                                    <option value="7" @if( in_array("7", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Sand</option>
                                    <option value="8" @if( in_array("8", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Wood chips</option>
                                    <option value="9" @if( in_array("9", json_decode($campaign->paving_loose_fill ,true)) ) selected @endif>Want Recommendation</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="paving_best_describes_priject">Type of project you wish to work with<span class="requiredFields">*</span></label>
                        <select id="paving_best_describes_priject" name="paving_best_describes_priject[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                            <optgroup label="Type of project you wish to work with">
                                <option value="1" @if( in_array("1", json_decode($campaign->paving_best_desc,true)) ) selected @endif>New Layout</option>
                                <option value="2" @if( in_array("2", json_decode($campaign->paving_best_desc ,true)) ) selected @endif>Restripe</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service23_section_camp" style="display: none;">
    <div class="row">
        <div class="col-12">
            <h6>What kind of services you do? <span class="requiredFields">*</span></h6>

            <div class="checkbox-group required">
                <label class="container-services">Exterior Home or Structure - Paint or Stain
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service1")' class="painting_service"
                           name="painting_service[]" value="1" @if( in_array("1", json_decode($campaign->painting_service,true)) ) checked @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Interior Home or Surfaces - Paint or Stain
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service2")' class="painting_service"
                           name="painting_service[]" value="2" @if( in_array("2", json_decode($campaign->painting_service,true)) ) checked @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Painting or Staining - Small Projects
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service3")' class="painting_service"
                           name="painting_service[]" value="3" @if( in_array("3", json_decode($campaign->painting_service,true)) ) checked @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Metal Roofing - Paint
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service4")' class="painting_service"
                           name="painting_service[]" value="4" @if( in_array("4", json_decode($campaign->painting_service,true)) ) checked @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Specialty Painting - Textures
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service5")' class="painting_service"
                           name="painting_service[]" value="5" @if( in_array("5", json_decode($campaign->painting_service,true)) ) checked @endif>
                    <span class="checkmark-services"></span>
                </label>
            </div>
        </div>
    </div>

    <div id="painting_service1" class="service-block"
         @if(!empty(json_decode($campaign->painting_service,true))) @if( !(in_array( 1, json_decode($campaign->painting_service,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Exterior Home or Structure - Paint or Stain</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting1_typeof_project">Type of project<span class="requiredFields">*</span></label>
                    <select id="painting1_typeof_project" name="painting1_typeof_project[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type of project">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_project_type,true)) ) selected @endif>New Construction</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_project_type ,true)) ) selected @endif>Repaint</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_project_type ,true)) ) selected @endif>Restain</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting1_kindsof_surfaces">Areas that you can paint and/or staine?<span class="requiredFields">*</span></label>
                    <select id="painting1_kindsof_surfaces" name="painting1_kindsof_surfaces[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you can paint and/or staine?">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_kinds_of_surfaces,true)) ) selected @endif>New layout</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Siding</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Trim</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Doors</option>
                            <option value="5" @if( in_array("5", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Stucco</option>
                            <option value="6" @if( in_array("6", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Shutters</option>
                            <option value="7" @if( in_array("7", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Fence</option>
                            <option value="8" @if( in_array("8", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Masonry (brick/stone)</option>
                            <option value="9" @if( in_array("9", json_decode($campaign->painting_kinds_of_surfaces ,true)) ) selected @endif>Other</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service2" class="service-block"
         @if(!empty(json_decode($campaign->painting_service,true))) @if( !(in_array( 2, json_decode($campaign->painting_service,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Interior Home or Surfaces - Paint or Stain</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting2_rooms_number">Number of Rooms<span class="requiredFields">*</span></label>
                    <select id="painting2_rooms_number" name="painting2_rooms_number[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Number of Rooms">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_rooms_number,true)) ) selected @endif>1-2</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_rooms_number ,true)) ) selected @endif>3-4</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_rooms_number ,true)) ) selected @endif>5-6</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_rooms_number ,true)) ) selected @endif>7-8</option>
                            <option value="5" @if( in_array("5", json_decode($campaign->painting_rooms_number ,true)) ) selected @endif>9 or more</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting2_typeof_paint">Areas that you paint?<span class="requiredFields">*</span></label>
                    <select id="painting2_typeof_paint" name="painting2_typeof_paint[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you paint?">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_type_of_paint,true)) ) selected @endif>Walls</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_type_of_paint ,true)) ) selected @endif>Walls And Ceilings</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_type_of_paint ,true)) ) selected @endif>Ceilings</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_type_of_paint ,true)) ) selected @endif>Others</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service3" class="service-block"
         @if(!empty(json_decode($campaign->painting_service,true))) @if( !(in_array( 3, json_decode($campaign->painting_service,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Painting or Staining - Small Projects</h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="painting3_each_feature">Areas that you can paint and/or staine? <span class="requiredFields">*</span></label>
                    <select id="painting3_each_feature" name="painting3_each_feature[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you can paint and/or staine?">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_each_feature,true)) ) selected @endif>Exterior Door(s)</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Exterior Siding</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Exterior Wood Trim</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Fencing / Gates</option>
                            <option value="5" @if( in_array("5", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Interior Door(s)</option>
                            <option value="6" @if( in_array("6", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Interior Walls</option>
                            <option value="7" @if( in_array("7", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Interior Wood Trim</option>
                            <option value="8" @if( in_array("8", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Ceiling</option>
                            <option value="9" @if( in_array("9", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Cabinetry</option>
                            <option value="10" @if( in_array("10", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Fireplace</option>
                            <option value="11" @if( in_array("11", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Paneling</option>
                            <option value="12" @if( in_array("12", json_decode($campaign->painting_each_feature ,true)) ) selected @endif>Others</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service4" class="service-block"
         @if(!empty(json_decode($campaign->painting_service,true))) @if( !(in_array( 4, json_decode($campaign->painting_service,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Metal Roofing - Paint</h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="painting4_existing_roof">Type/condition of the roof you wish to work with<span class="requiredFields">*</span></label>
                    <select id="painting4_existing_roof" name="painting4_existing_roof[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type/condition of the roof you wish to work with">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_existing_roof,true)) ) selected @endif>Peeling or Blistering</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Bleeding</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Nail Stains</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Mildew</option>
                            <option value="5" @if( in_array("5", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Chalking</option>
                            <option value="6" @if( in_array("6", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>No Known Problems</option>
                            <option value="7" @if( in_array("7", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Fair Condition</option>
                            <option value="8" @if( in_array("8", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Never Been Painted Before</option>
                            <option value="9" @if( in_array("9", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Others</option>
                            <option value="10" @if(in_array("10", json_decode($campaign->painting_existing_roof ,true)) ) selected @endif>Don't Know</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service5" class="service-block"
         @if(!empty(json_decode($campaign->painting_service,true))) @if( !(in_array( 5, json_decode($campaign->painting_service,true))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Specialty Painting - Textures</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting5_kindof_texturing">Type of project<span class="requiredFields">*</span></label>
                    <select id="painting5_kindof_texturing" name="painting5_kindof_texturing[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type of project">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_kind_of_texturing,true)) ) selected @endif>Apply Texture To Unfinished Drywall for Paint</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Match New Drywall To Exisiting Walls/Ceilings</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Repair / Patch Drywall</option>
                            <option value="4" @if( in_array("4", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Prepare For Wallpaper / Special Finish</option>
                            <option value="5" @if( in_array("5", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Remove Popcorn Acoustic Ceiling Spray</option>
                            <option value="6" @if( in_array("6", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Create Faux Effects</option>
                            <option value="7" @if( in_array("7", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Paint Also Needed</option>
                            <option value="8" @if( in_array("8", json_decode($campaign->painting_kind_of_texturing ,true)) ) selected @endif>Other</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting5_surfaces_textured">Areas that you surface<span class="requiredFields">*</span></label>
                    <select id="painting5_surfaces_textured" name="painting5_surfaces_textured[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you surface">
                            <option value="1" @if( in_array("1", json_decode($campaign->painting_surfaces_textured,true)) ) selected @endif>Walls</option>
                            <option value="2" @if( in_array("2", json_decode($campaign->painting_surfaces_textured ,true)) ) selected @endif>Ceilings</option>
                            <option value="3" @if( in_array("3", json_decode($campaign->painting_surfaces_textured ,true)) ) selected @endif>Others</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="painting1_stories_number">Number of stories of the building you wish to work with<span class="requiredFields">*</span></label>
                <select id="painting1_stories_number" name="painting1_stories_number[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Number of stories of the building you wish to work with">
                        <option value="1" @if( in_array("1", json_decode($campaign->painting_stories_number,true)) ) selected @endif>One Story</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->painting_stories_number ,true)) ) selected @endif>Two Stories</option>
                        <option value="3" @if( in_array("3", json_decode($campaign->painting_stories_number ,true)) ) selected @endif>Three Stories or more</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="historical_structure">Do you wish to receive historical structure painting project?<span class="requiredFields">*</span></label>
                <select id="historical_structure" name="historical_structure[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Do you wish to receive historical structure painting project?">
                        <option value="1" @if( in_array("1", json_decode($campaign->painting_historical_structure,true)) ) selected @endif>Yes</option>
                        <option value="2" @if( in_array("2", json_decode($campaign->painting_historical_structure,true)) ) selected @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="questions_div_section_campaign div_service24_section_camp" style="display: none;">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="plumbing_service">Owner of a valid driving license:<span class="requiredFields">*</span></label>
                <select id="auto_insurance_license" name="auto_insurance_license[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Owner of a valid driving license:">
                        <option value="Yes" @if(!empty(json_decode($campaign->auto_insurance_license,true))) @if(in_array("Yes", json_decode($campaign->auto_insurance_license,true))) selected @endif @endif>Yes</option>
                        <option value="No" @if(!empty(json_decode($campaign->auto_insurance_license ,true))) @if(in_array("No", json_decode($campaign->auto_insurance_license,true))) selected @endif @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="driver_experience">Are you a good driver?<span class="requiredFields">*</span></label>
                <select id="driver_experience" name="driver_experience[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Are you a good driver?">
                        <option value="Yes" @if(!empty(json_decode($campaign->driver_experience,true))) @if(in_array("Yes", json_decode($campaign->driver_experience,true))) selected @endif @endif>Yes</option>
                        <option value="No" @if(!empty(json_decode($campaign->driver_experience,true))) @if(in_array("No", json_decode($campaign->driver_experience,true))) selected @endif @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="coverage_type">Insurance Coverage Type:<span class="requiredFields">*</span></label>
                <select id="coverage_type" name="coverage_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Insurance Coverage Type:">
                        <option value="Preferred" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("Preferred", json_decode($campaign->coverage_type,true))) selected @endif @endif>Preferred</option>
                        <option value="Premium" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("Premium", json_decode($campaign->coverage_type,true))) selected @endif @endif>Premium</option>
                        <option value="Standard" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("Standard", json_decode($campaign->coverage_type,true))) selected @endif @endif>Standard</option>
                        <option value="State Minimum" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("State Minimum", json_decode($campaign->coverage_type,true))) selected @endif @endif>State Minimum</option>
                        <option value="Interested" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("Interested", json_decode($campaign->coverage_type,true))) selected @endif @endif>Interested</option>
                        <option value="Not Interested" @if(!empty(json_decode($campaign->coverage_type,true))) @if(in_array("Not Interested", json_decode($campaign->coverage_type,true))) selected @endif @endif>Not Interested</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="license_status">License Status:<span class="requiredFields">*</span></label>
                <select id="license_status" name="license_status[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="License Status:">
                        <option value="Active" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Active", json_decode($campaign->license_status,true))) selected @endif @endif>Active</option>
                        <option value="International" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("International", json_decode($campaign->license_status,true))) selected @endif @endif>International</option>
                        <option value="Learner" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Learner", json_decode($campaign->license_status,true))) selected @endif @endif>Learner</option>
                        <option value="Probation" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Probation", json_decode($campaign->license_status,true))) selected @endif @endif>Probation</option>
                        <option value="Restricted" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Restricted", json_decode($campaign->license_status,true))) selected @endif @endif>Restricted</option>
                        <option value="Suspended" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Suspended", json_decode($campaign->license_status,true))) selected @endif @endif>Suspended</option>
                        <option value="Temporary" @if(!empty(json_decode($campaign->license_status,true))) @if(in_array("Temporary", json_decode($campaign->license_status,true))) selected @endif @endif>Temporary</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="submodel">Car Submodel:<span class="requiredFields">*</span></label>
                <select id="submodel" name="submodel[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Car Submodel:">
                        <option value="Micros" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Micros", json_decode($campaign->submodel,true))) selected @endif @endif>Micros</option>
                        <option value="Hatchback" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Hatchback", json_decode($campaign->submodel,true))) selected @endif @endif>Hatchback</option>
                        <option value="Fastback" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Fastback", json_decode($campaign->submodel,true))) selected @endif @endif>Fastback</option>
                        <option value="Sedan" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Sedan", json_decode($campaign->submodel,true))) selected @endif @endif>Sedan</option>
                        <option value="Crossover" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Crossover", json_decode($campaign->submodel,true))) selected @endif @endif>Crossover</option>
                        <option value="SUV" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("SUV", json_decode($campaign->submodel,true))) selected @endif @endif>SUV</option>
                        <option value="MPV" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("MPV", json_decode($campaign->submodel,true))) selected @endif @endif>MPV</option>
                        <option value="Convertible" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Convertible", json_decode($campaign->submodel,true))) selected @endif @endif>Convertible</option>
                        <option value="Wagon" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Wagon", json_decode($campaign->submodel,true))) selected @endif @endif>Wagon</option>
                        <option value="Luxury" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Luxury", json_decode($campaign->submodel,true))) selected @endif @endif>Luxury</option>
                        <option value="Antique" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Antique", json_decode($campaign->submodel,true))) selected @endif @endif>Antique</option>
                        <option value="Coupe" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Coupe", json_decode($campaign->submodel,true))) selected @endif @endif>Coupe</option>
                        <option value="Sports Car" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Sports Car", json_decode($campaign->submodel,true))) selected @endif @endif>Sports Car</option>
                        <option value="Supercar" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Supercar", json_decode($campaign->submodel,true))) selected @endif @endif>Supercar</option>
                        <option value="Muscle Car" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Muscle Car", json_decode($campaign->submodel,true))) selected @endif @endif>Muscle Car</option>
                        <option value="Limousine" @if(!empty(json_decode($campaign->submodel,true))) @if(in_array("Limousine", json_decode($campaign->submodel,true))) selected @endif @endif>Limousine</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group stateCampName">
                <label for="license_state">License State:</label>
                <select class="select2 form-control select2-multiple license_state" name="license_state[]" id="stateCampName" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="License State:">
                        @if(!empty($address['states']))
                            @foreach($address['states'] as $state)
                                @if(!empty($campaign->license_state))
                                    @if(in_array($state->state_code, json_decode($campaign->license_state, true)))
                                        <option value="{{ $state->state_code }}" selected>{{ $state->state_code }}</option>
                                    @else
                                        <option value="{{ $state->state_code }}">{{ $state->state_code }}</option>
                                    @endif
                                @else
                                    <option value="{{ $state->state_code }}">{{ $state->state_code }}</option>
                                @endif
                            @endforeach
                        @endif
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
