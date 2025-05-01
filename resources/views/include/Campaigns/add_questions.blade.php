<div class="questions_div_section_campaign div_service1_section_camp">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="number_of_windows_c">Number Of Windows<span class="requiredFields">*</span></label>
                <select id="number_of_windows_c" name="number_of_windows_c[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" required="" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Number Of Windows">
                        <option value="1" @if(!empty(old('number_of_windows_c'))) @if(in_array(1, old('number_of_windows_c'))) selected @endif @endif> 1 </option>
                        <option value="2" @if(!empty(old('number_of_windows_c'))) @if(in_array(2, old('number_of_windows_c'))) selected @endif @endif> 2 </option>
                        <option value="3" @if(!empty(old('number_of_windows_c'))) @if(in_array(3, old('number_of_windows_c'))) selected @endif @endif> 3-5 </option>
                        <option value="4" @if(!empty(old('number_of_windows_c'))) @if(in_array(4, old('number_of_windows_c'))) selected @endif @endif> 6-9 </option>
                        <option value="5" @if(!empty(old('number_of_windows_c'))) @if(in_array(5, old('number_of_windows_c'))) selected @endif @endif> 10+ </option>
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
                <select id="solorBill" name="solorBill[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Customer's Average monthly electricity bill">
                        <option value="6" @if(!empty(old('solorBill'))) @if(in_array(6, old('solorBill'))) selected @endif @endif> $0 - $50 </option>
                        <option value="1" @if(!empty(old('solorBill'))) @if(in_array(1, old('solorBill'))) selected @endif @endif> $51 - $100 </option>
                        <option value="7" @if(!empty(old('solorBill'))) @if(in_array(7, old('solorBill'))) selected @endif @endif> $101 - $150 </option>
                        <option value="2" @if(!empty(old('solorBill'))) @if(in_array(2, old('solorBill'))) selected @endif @endif> $151 - $200 </option>
                        <option value="3" @if(!empty(old('solorBill'))) @if(in_array(3, old('solorBill'))) selected @endif @endif> $201 - $300 </option>
                        <option value="8" @if(!empty(old('solorBill'))) @if(in_array(8, old('solorBill'))) selected @endif @endif> $301 - $400 </option>
                        <option value="4" @if(!empty(old('solorBill'))) @if(in_array(4, old('solorBill'))) selected @endif @endif> $401 - $500 </option>
                        <option value="5" @if(!empty(old('solorBill'))) @if(in_array(5, old('solorBill'))) selected @endif @endif> $500+ </option>
                    </optgroup>
                </select>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="Solarpowersolution">Kind of Solar power solution<span class="requiredFields">*</span></label>
                <select id="Solarpowersolution" name="Solarpowersolution[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Kind of Solar power solution">
                        <option value="1" @if(!empty(old('Solarpowersolution'))) @if(in_array(1, old('Solarpowersolution'))) selected @endif @endif> Solar Electricity for my Home </option>
                        <option value="2" @if(!empty(old('Solarpowersolution'))) @if(in_array(2, old('Solarpowersolution'))) selected @endif @endif> Solar Water Heating for my Home </option>
                        <option value="3" @if(!empty(old('Solarpowersolution'))) @if(in_array(3, old('Solarpowersolution'))) selected @endif @endif> Solar Electricity & Water Heating for my Home </option>
                        <option value="4" @if(!empty(old('Solarpowersolution'))) @if(in_array(4, old('Solarpowersolution'))) selected @endif @endif> Solar for my Business </option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="RoofShade">Sun exposure, Roof Shade<span class="requiredFields">*</span></label>
                <select id="RoofShade" name="RoofShade[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Sun exposure, Roof Shade">
                        <option value="1" @if(!empty(old('RoofShade'))) @if(in_array(1, old('RoofShade'))) selected @endif @endif> Full Sun </option>
                        <option value="2" @if(!empty(old('RoofShade'))) @if(in_array(2, old('RoofShade'))) selected @endif @endif> Partial Sun </option>
                        <option value="3" @if(!empty(old('RoofShade'))) @if(in_array(3, old('RoofShade'))) selected @endif @endif> Mostly Shaded </option>
                        <option value="4" @if(!empty(old('RoofShade'))) @if(in_array(4, old('RoofShade'))) selected @endif @endif> Not Sure </option>
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
                        <input type="checkbox" name="is_utility_providers" id="is_utility_providers" value="1">
                        <span class="slider round coolToolsToggle "></span>
                    </label>
                </p>
            </div>
        </div>
        <div class="campaign_utility_providers col-sm-12" style="display: none">
            <div class="form-group">
                <label for="utility_providers">Utility Providers<span class="requiredFields">*</span></label>
                <select id="utility_providers_select" name="Utility_Providers[]" class="select2 form-control select2-multiple questions_div_section_select_campaign UtilityProvidersSelect" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Utility Providers">
                        @if(!empty($utility_providers))
                            @foreach($utility_providers as $item)
                                <option class="utility_provider_stateId{{ $item->state_id }}" value="{{ $item->lead_current_utility_provider_name }}">{{ $item->lead_current_utility_provider_name }}</option>
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
                <select id="securityInstalling" name="securityInstalling[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Installation Preferences">
                        <option value="1" @if(!empty(old('securityInstalling'))) @if(in_array(1, old('securityInstalling'))) selected @endif @endif> Professional installation </option>
                        <option value="2" @if(!empty(old('securityInstalling'))) @if(in_array(2, old('securityInstalling'))) selected @endif @endif> Self installation </option>
                        <option value="3" @if(!empty(old('securityInstalling'))) @if(in_array(3, old('securityInstalling'))) selected @endif @endif> No preference </option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="ExistingMonitoringSystem">Customer Has An Existing Alarm And/ Or Monitoring System<span class="requiredFields">*</span></label>
                <select id="ExistingMonitoringSystem" name="ExistingMonitoringSystem[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Customer Has An Existing Alarm And/ Or Monitoring System">
                        <option value="1" @if(!empty(old('ExistingMonitoringSystem'))) @if(in_array(1, old('ExistingMonitoringSystem'))) selected @endif @endif>Yes</option>
                        <option value="2" @if(!empty(old('ExistingMonitoringSystem'))) @if(in_array(2, old('ExistingMonitoringSystem'))) selected @endif @endif>No</option>
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
                <select id="flooringtype" name="flooringtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of flooring">
                        <option value="1" @if(!empty(old('flooringtype'))) @if(in_array(1, old('flooringtype'))) selected @endif @endif>Vinyl Linoleum Flooring</option>
                        <option value="2" @if(!empty(old('flooringtype'))) @if(in_array(2, old('flooringtype'))) selected @endif @endif>Tile Flooring</option>
                        <option value="3" @if(!empty(old('flooringtype'))) @if(in_array(3, old('flooringtype'))) selected @endif @endif>Hardwood Flooring</option>
                        <option value="4" @if(!empty(old('flooringtype'))) @if(in_array(4, old('flooringtype'))) selected @endif @endif>Laminate Flooring</option>
                        <option value="5" @if(!empty(old('flooringtype'))) @if(in_array(5, old('flooringtype'))) selected @endif @endif>Carpet</option>
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
                        <option value="1" @if(!empty(old('lead_walk_in_tub'))) @if(in_array(1, old('lead_walk_in_tub'))) selected @endif @endif>Safety</option>
                        <option value="2" @if(!empty(old('lead_walk_in_tub'))) @if(in_array(2, old('lead_walk_in_tub'))) selected @endif @endif>Therapeutic</option>
                        <option value="3" @if(!empty(old('lead_walk_in_tub'))) @if(in_array(3, old('lead_walk_in_tub'))) selected @endif @endif>Others</option>
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
                <select id="roofingtype" name="roofingtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of roofing">
                        <option value="1" @if(!empty(old('roofingtype'))) @if(in_array(1, old('roofingtype'))) selected @endif @endif>Asphalt Roofing</option>
                        <option value="2" @if(!empty(old('roofingtype'))) @if(in_array(2, old('roofingtype'))) selected @endif @endif>Wood Shake/Composite Roofing</option>
                        <option value="3" @if(!empty(old('roofingtype'))) @if(in_array(3, old('roofingtype'))) selected @endif @endif>Metal Roofing</option>
                        <option value="4" @if(!empty(old('roofingtype'))) @if(in_array(4, old('roofingtype'))) selected @endif @endif>Natural Slate Roofing</option>
                        <option value="5" @if(!empty(old('roofingtype'))) @if(in_array(5, old('roofingtype'))) selected @endif @endif>Tile Roofing</option>
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
                <label for="sidingtype">Type of siding<span class="requiredFields">*</span></label>
                <select id="sidingtype" name="sidingtype[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Type of siding">
                        <option value="1" @if(!empty(old('sidingtype'))) @if(in_array(1, old('sidingtype'))) selected @endif @endif>Vinyl Siding</option>
                        <option value="2" @if(!empty(old('sidingtype'))) @if(in_array(2, old('sidingtype'))) selected @endif @endif>Brickface Siding</option>
                        <option value="3" @if(!empty(old('sidingtype'))) @if(in_array(3, old('sidingtype'))) selected @endif @endif>Composite wood Siding</option>
                        <option value="4" @if(!empty(old('sidingtype'))) @if(in_array(4, old('sidingtype'))) selected @endif @endif>Aluminum Siding</option>
                        <option value="5" @if(!empty(old('sidingtype'))) @if(in_array(5, old('sidingtype'))) selected @endif @endif>Stoneface Siding</option>
                        <option value="6" @if(!empty(old('sidingtype'))) @if(in_array(6, old('sidingtype'))) selected @endif @endif>Fiber Cement Siding</option>
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
                        <option value="1" @if(!empty(old('kitchen_service'))) @if(in_array(1, old('kitchen_service'))) selected @endif @endif>Full Kitchen Remodeling</option>
                        <option value="2" @if(!empty(old('kitchen_service'))) @if(in_array(2, old('kitchen_service'))) selected @endif @endif>Cabinet Refacing</option>
                        <option value="3" @if(!empty(old('kitchen_service'))) @if(in_array(3, old('kitchen_service'))) selected @endif @endif>Cabinet Install</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="removing_adding_walls">Are you able to demolish/build walls?<span class="requiredFields">*</span></label>
                <select id="removing_adding_walls" name="removing_adding_walls[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Are you able to demolish/build walls?">
                        <option value="1" @if(!empty(old('removing_adding_walls'))) @if(in_array(1, old('removing_adding_walls'))) selected @endif @endif>Yes</option>
                        <option value="0" @if(!empty(old('removing_adding_walls'))) @if(in_array(0, old('removing_adding_walls'))) selected @endif @endif>No</option>
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
                        <option value="1" @if(!empty(old('bathroom_service'))) @if(in_array(1, old('bathroom_service'))) selected @endif @endif>Full Remodel</option>
                        <option value="2" @if(!empty(old('bathroom_service'))) @if(in_array(2, old('bathroom_service'))) selected @endif @endif>Cabinets / Vanity</option>
                        <option value="3" @if(!empty(old('bathroom_service'))) @if(in_array(3, old('bathroom_service'))) selected @endif @endif>Countertops</option>
                        <option value="4" @if(!empty(old('bathroom_service'))) @if(in_array(4, old('bathroom_service'))) selected @endif @endif>Flooring</option>
                        <option value="5" @if(!empty(old('bathroom_service'))) @if(in_array(5, old('bathroom_service'))) selected @endif @endif>Shower / Bath</option>
                        <option value="6" @if(!empty(old('bathroom_service'))) @if(in_array(6, old('bathroom_service'))) selected @endif @endif>Sinks</option>
                        <option value="7" @if(!empty(old('bathroom_service'))) @if(in_array(7, old('bathroom_service'))) selected @endif @endif>Toilet</option>
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
                    <optgroup label="Type of stairs ">
                        <option value="1" @if(!empty(old('stairlift_type'))) @if(in_array(1, old('stairlift_type'))) selected @endif @endif>Straight</option>
                        <option value="2" @if(!empty(old('stairlift_type'))) @if(in_array(2, old('stairlift_type'))) selected @endif @endif>Curved</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="stairlift_reason">Stairlift specification <span class="requiredFields">*</span></label>
                <select id="stairlift_reason" name="stairlift_reason[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Stairlift specification ">
                        <option value="1" @if(!empty(old('stairlift_reason'))) @if(in_array(1, old('stairlift_reason'))) selected @endif @endif>Mobility</option>
                        <option value="2" @if(!empty(old('stairlift_reason'))) @if(in_array(2, old('stairlift_reason'))) selected @endif @endif>Safety</option>
                        <option value="3" @if(!empty(old('stairlift_reason'))) @if(in_array(3, old('stairlift_reason'))) selected @endif @endif>Other</option>
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
                    <option value="1" @if(!empty(old('furnance_type'))) @if(in_array(1, old('furnance_type'))) selected @endif @endif>Do Not Know</option>
                    <option value="2" @if(!empty(old('furnance_type'))) @if(in_array(2, old('furnance_type'))) selected @endif @endif>Electric</option>
                    <option value="3" @if(!empty(old('furnance_type'))) @if(in_array(3, old('furnance_type'))) selected @endif @endif>Natural Gas</option>
                    <option value="4" @if(!empty(old('furnance_type'))) @if(in_array(4, old('furnance_type'))) selected @endif @endif>Oil</option>
                    <option value="5" @if(!empty(old('furnance_type'))) @if(in_array(5, old('furnance_type'))) selected @endif @endif>Propane Gas</option>
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
                        <option value="1" @if(!empty(old('plumbing_service'))) @if(in_array(1, old('plumbing_service'))) selected @endif @endif>Faucet/ Fixture Services</option>
                        <option value="2" @if(!empty(old('plumbing_service'))) @if(in_array(2, old('plumbing_service'))) selected @endif @endif>Pipe Services</option>
                        <option value="3" @if(!empty(old('plumbing_service'))) @if(in_array(3, old('plumbing_service'))) selected @endif @endif>Leak Repair</option>
                        <option value="4" @if(!empty(old('plumbing_service'))) @if(in_array(4, old('plumbing_service'))) selected @endif @endif>Remodeling/ Construction</option>
                        <option value="5" @if(!empty(old('plumbing_service'))) @if(in_array(5, old('plumbing_service'))) selected @endif @endif>Septic Systems</option>
                        <option value="6" @if(!empty(old('plumbing_service'))) @if(in_array(6, old('plumbing_service'))) selected @endif @endif>Drain/ Sewer Services</option>
                        <option value="7" @if(!empty(old('plumbing_service'))) @if(in_array(7, old('plumbing_service'))) selected @endif @endif>Shower Services</option>
                        <option value="8" @if(!empty(old('plumbing_service'))) @if(in_array(8, old('plumbing_service'))) selected @endif @endif>Sump Pump Services</option>
                        <option value="9" @if(!empty(old('plumbing_service'))) @if(in_array(9, old('plumbing_service'))) selected @endif @endif>Toilet Services</option>
                        <option value="10" @if(!empty(old('plumbing_service'))) @if(in_array(10, old('plumbing_service'))) selected @endif @endif>Water Heater Services</option>
                        <option value="11" @if(!empty(old('plumbing_service'))) @if(in_array(11, old('plumbing_service'))) selected @endif @endif>Water/ Fuel Tank</option>
                        <option value="12" @if(!empty(old('plumbing_service'))) @if(in_array(12, old('plumbing_service'))) selected @endif @endif>Water Treatment and Purification</option>
                        <option value="13" @if(!empty(old('plumbing_service'))) @if(in_array(13, old('plumbing_service'))) selected @endif @endif>Well Pump Services</option>
                        <option value="14" @if(!empty(old('plumbing_service'))) @if(in_array(14, old('plumbing_service'))) selected @endif @endif>Backflow Services</option>
                        <option value="15" @if(!empty(old('plumbing_service'))) @if(in_array(15, old('plumbing_service'))) selected @endif @endif>Bathroom Plumbing</option>
                        <option value="16" @if(!empty(old('plumbing_service'))) @if(in_array(16, old('plumbing_service'))) selected @endif @endif>Camera Line Inspection</option>
                        <option value="17" @if(!empty(old('plumbing_service'))) @if(in_array(17, old('plumbing_service'))) selected @endif @endif>Clogged Sink Repair</option>
                        <option value="18" @if(!empty(old('plumbing_service'))) @if(in_array(18, old('plumbing_service'))) selected @endif @endif>Disposal Services</option>
                        <option value="19" @if(!empty(old('plumbing_service'))) @if(in_array(19, old('plumbing_service'))) selected @endif @endif>Excavation</option>
                        <option value="20" @if(!empty(old('plumbing_service'))) @if(in_array(20, old('plumbing_service'))) selected @endif @endif>Grease Trap Services</option>
                        <option value="21" @if(!empty(old('plumbing_service'))) @if(in_array(21, old('plumbing_service'))) selected @endif @endif>Kitchen Plumbing</option>
                        <option value="22" @if(!empty(old('plumbing_service'))) @if(in_array(22, old('plumbing_service'))) selected @endif @endif>Storm Drain Cleaning</option>
                        <option value="23" @if(!empty(old('plumbing_service'))) @if(in_array(23, old('plumbing_service'))) selected @endif @endif>Trench less Repairs</option>
                        <option value="24" @if(!empty(old('plumbing_service'))) @if(in_array(24, old('plumbing_service'))) selected @endif @endif>Water Damage Restoration</option>
                        <option value="25" @if(!empty(old('plumbing_service'))) @if(in_array(25, old('plumbing_service'))) selected @endif @endif>Water Jetting</option>
                        <option value="26" @if(!empty(old('plumbing_service'))) @if(in_array(26, old('plumbing_service'))) selected @endif @endif>Water Leak Services</option>
                        <option value="27" @if(!empty(old('plumbing_service'))) @if(in_array(27, old('plumbing_service'))) selected @endif @endif>Basement Plumbing</option>
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
                        <option value="1" @if(!empty(old('sunroom_service'))) @if(in_array(1, old('sunroom_service'))) selected @endif @endif>Build a new sun room or patio enclosure</option>
                        <option value="2" @if(!empty(old('sunroom_service'))) @if(in_array(2, old('sunroom_service'))) selected @endif @endif>Enclose existing porch with roof, walls or windows</option>
                        <option value="3" @if(!empty(old('sunroom_service'))) @if(in_array(3, old('sunroom_service'))) selected @endif @endif>Screen in existing porch or patio</option>
                        <option value="4" @if(!empty(old('sunroom_service'))) @if(in_array(4, old('sunroom_service'))) selected @endif @endif>Add a metal awning or cover</option>
                        <option value="5" @if(!empty(old('sunroom_service'))) @if(in_array(5, old('sunroom_service'))) selected @endif @endif>Add a fabric awning or cover</option>
                        <option value="6" @if(!empty(old('sunroom_service'))) @if(in_array(6, old('sunroom_service'))) selected @endif @endif>Repair existing sun room, porch or patio</option>
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
                        <option value="1" @if(!empty(old('handyman_ammount'))) @if(in_array(1, old('handyman_ammount'))) selected @endif @endif>A variety of projects</option>
                        <option value="2" @if(!empty(old('handyman_ammount'))) @if(in_array(2, old('handyman_ammount'))) selected @endif @endif>A single project</option>
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
                        <option value="1" @if(!empty(old('countertops_service'))) @if(in_array(1, old('countertops_service'))) selected @endif @endif>Granite</option>
                        <option value="2" @if(!empty(old('countertops_service'))) @if(in_array(2, old('countertops_service'))) selected @endif @endif>Solid Surface (e.g corian)</option>
                        <option value="3" @if(!empty(old('countertops_service'))) @if(in_array(3, old('countertops_service'))) selected @endif @endif>Marble</option>
                        <option value="4" @if(!empty(old('countertops_service'))) @if(in_array(4, old('countertops_service'))) selected @endif @endif>Wood (e.g butcher block)</option>
                        <option value="5" @if(!empty(old('countertops_service'))) @if(in_array(5, old('countertops_service'))) selected @endif @endif>Stainless Steel</option>
                        <option value="6" @if(!empty(old('countertops_service'))) @if(in_array(6, old('countertops_service'))) selected @endif @endif>Laminate</option>
                        <option value="7" @if(!empty(old('countertops_service'))) @if(in_array(7, old('countertops_service'))) selected @endif @endif>Concrete</option>
                        <option value="8" @if(!empty(old('countertops_service'))) @if(in_array(8, old('countertops_service'))) selected @endif @endif>Other Solid Stone (e.g Quartz)</option>
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
                        <option value="1" @if(!empty(old('door_typeproject'))) @if(in_array(1, old('door_typeproject'))) selected @endif @endif>Exterior</option>
                        <option value="2" @if(!empty(old('door_typeproject'))) @if(in_array(2, old('door_typeproject'))) selected @endif @endif>Interior</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="number_of_door">Number of doors <span class="requiredFields">*</span></label>
                <select id="number_of_door" name="number_of_door[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Number of doors">
                        <option value="1" @if(!empty(old('number_of_door'))) @if(in_array(1, old('number_of_door'))) selected @endif @endif>1</option>
                        <option value="2" @if(!empty(old('number_of_door'))) @if(in_array(2, old('number_of_door'))) selected @endif @endif>2</option>
                        <option value="3" @if(!empty(old('number_of_door'))) @if(in_array(3, old('number_of_door'))) selected @endif @endif>3</option>
                        <option value="4" @if(!empty(old('number_of_door'))) @if(in_array(4, old('number_of_door'))) selected @endif @endif>4+</option>
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
                        <option value="1" @if(!empty(old('gutters_meterial'))) @if(in_array(1, old('gutters_meterial'))) selected @endif @endif>Copper</option>
                        <option value="2" @if(!empty(old('gutters_meterial'))) @if(in_array(2, old('gutters_meterial'))) selected @endif @endif>Galvanized Steel</option>
                        <option value="3" @if(!empty(old('gutters_meterial'))) @if(in_array(3, old('gutters_meterial'))) selected @endif @endif>PVC</option>
                        <option value="4" @if(!empty(old('gutters_meterial'))) @if(in_array(4, old('gutters_meterial'))) selected @endif @endif>Seamless Aluminum</option>
                        <option value="5" @if(!empty(old('gutters_meterial'))) @if(in_array(5, old('gutters_meterial'))) selected @endif @endif>Wood</option>
                        <option value="6" @if(!empty(old('gutters_meterial'))) @if(in_array(6, old('gutters_meterial'))) selected @endif @endif>not sure</option>
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
                           name="paving_service[]" value="1" class="paving_service" @if(!empty(old('paving_service'))) @if( in_array(1, old('paving_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Asphalt Sealing
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps2")'
                           name="paving_service[]" value="2" class="paving_service" @if(!empty(old('paving_service'))) @if( in_array(2, old('paving_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Gravel or Loose Fill Paving - Install, Spread or Scrape
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps3")'
                           name="paving_service[]" value="3" class="paving_service" @if(!empty(old('paving_service'))) @if( in_array(3, old('paving_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Asphalt Paving - Repair or Patch
                    <input type="checkbox" onchange='multiServicesCheckboxPaving("ps4")'
                           name="paving_service[]" value="4" class="paving_service" @if(!empty(old('paving_service'))) @if( in_array(4, old('paving_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>
            </div>

            <div id="ps1" class="service-block"
                 @if(!empty(old('paving_service'))) @if( !(in_array(1, old('paving_service'))) ) style="display:none;" @endif @else style="display:none;" @endif>
                <h6>Asphalt Paving - Install</h6>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="paving_asphalt_type">Areas that you can make the work in? <span class="requiredFields">*</span></label>
                            <select id="paving_asphalt_type" name="paving_asphalt_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                <optgroup label="Areas that you can make the work in?">
                                    <option value="1" @if(!empty(old('paving_asphalt_type'))) @if(in_array(1, old('paving_asphalt_type'))) selected @endif @endif>Driveway</option>
                                    <option value="2" @if(!empty(old('paving_asphalt_type'))) @if(in_array(2, old('paving_asphalt_type'))) selected @endif @endif>Road</option>
                                    <option value="3" @if(!empty(old('paving_asphalt_type'))) @if(in_array(3, old('paving_asphalt_type'))) selected @endif @endif>Walkway or sidewalk</option>
                                    <option value="4" @if(!empty(old('paving_asphalt_type'))) @if(in_array(4, old('paving_asphalt_type'))) selected @endif @endif>Patio</option>
                                    <option value="5" @if(!empty(old('paving_asphalt_type'))) @if(in_array(5, old('paving_asphalt_type'))) selected @endif @endif>Sports court (tennis, basketball, etc.)</option>
                                    <option value="6" @if(!empty(old('paving_asphalt_type'))) @if(in_array(6, old('paving_asphalt_type'))) selected @endif @endif>Parking Lot</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="ps3" class="service-block"
                 @if(!empty(old('paving_service'))) @if( !(in_array(3, old('paving_service'))) ) style="display:none;" @endif @else style="display:none;" @endif>
                <h6>Gravel or Loose Fill Paving - Install, Spread or Scrape</h6>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="paving_loose_fill_type">Type of loose fill do you work with<span class="requiredFields">*</span></label>
                            <select id="paving_loose_fill_type" name="paving_loose_fill_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                                <optgroup label="Type of loose fill do you work with">
                                    <option value="1" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(1, old('paving_loose_fill_type'))) selected @endif @endif>Cobblestone</option>
                                    <option value="2" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(2, old('paving_loose_fill_type'))) selected @endif @endif>Crushed rock</option>
                                    <option value="3" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(3, old('paving_loose_fill_type'))) selected @endif @endif>Gravel</option>
                                    <option value="4" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(4, old('paving_loose_fill_type'))) selected @endif @endif>Pebbles</option>
                                    <option value="5" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(5, old('paving_loose_fill_type'))) selected @endif @endif>Road base</option>
                                    <option value="6" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(6, old('paving_loose_fill_type'))) selected @endif @endif>Rock</option>
                                    <option value="7" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(7, old('paving_loose_fill_type'))) selected @endif @endif>Sand</option>
                                    <option value="8" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(8, old('paving_loose_fill_type'))) selected @endif @endif>Wood chips</option>
                                    <option value="9" @if(!empty(old('paving_loose_fill_type'))) @if(in_array(9, old('paving_loose_fill_type'))) selected @endif @endif>Want Recommendation</option>
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
                                <option value="1" @if(!empty(old('paving_best_describes_priject'))) @if(in_array(1, old('paving_best_describes_priject'))) selected @endif @endif>New Layout</option>
                                <option value="2" @if(!empty(old('paving_best_describes_priject'))) @if(in_array(2, old('paving_best_describes_priject'))) selected @endif @endif>Restripe</option>
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
                           name="painting_service[]" value="1" @if(!empty(old('painting_service'))) @if( in_array(1, old('painting_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Interior Home or Surfaces - Paint or Stain
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service2")' class="painting_service"
                           name="painting_service[]" value="2" @if(!empty(old('painting_service'))) @if( in_array(2, old('painting_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Painting or Staining - Small Projects
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service3")' class="painting_service"
                           name="painting_service[]" value="3" @if(!empty(old('painting_service'))) @if( in_array(3, old('painting_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Metal Roofing - Paint
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service4")' class="painting_service"
                           name="painting_service[]" value="4" @if(!empty(old('painting_service'))) @if( in_array(4, old('painting_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>

                <label class="container-services">Specialty Painting - Textures
                    <input type="checkbox" onchange='multiServicesCheckboxPainting("painting_service5")' class="painting_service"
                           name="painting_service[]" value="5" @if(!empty(old('painting_service'))) @if( in_array(5, old('painting_service')) ) checked @endif @endif>
                    <span class="checkmark-services"></span>
                </label>
            </div>
        </div>
    </div>

    <div id="painting_service1" class="service-block"
         @if(!empty(old('painting_service'))) @if( !(in_array(1, old('painting_service'))) ) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Exterior Home or Structure - Paint or Stain</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting1_typeof_project">Type of project<span class="requiredFields">*</span></label>
                    <select id="painting1_typeof_project" name="painting1_typeof_project[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type of project">
                            <option value="1" @if(!empty(old('painting1_typeof_project'))) @if(in_array(1, old('painting1_typeof_project'))) selected @endif @endif>New Construction</option>
                            <option value="2" @if(!empty(old('painting1_typeof_project'))) @if(in_array(2, old('painting1_typeof_project'))) selected @endif @endif>Repaint</option>
                            <option value="3" @if(!empty(old('painting1_typeof_project'))) @if(in_array(3, old('painting1_typeof_project'))) selected @endif @endif>Restain</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting1_kindsof_surfaces">Areas that you can paint and/or staine?<span class="requiredFields">*</span></label>
                    <select id="painting1_kindsof_surfaces" name="painting1_kindsof_surfaces[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you can paint and/or staine?">
                            <option value="1" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(1, old('painting1_kindsof_surfaces'))) selected @endif @endif>New layout</option>
                            <option value="2" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(2, old('painting1_kindsof_surfaces'))) selected @endif @endif>Siding</option>
                            <option value="3" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(3, old('painting1_kindsof_surfaces'))) selected @endif @endif>Trim</option>
                            <option value="4" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(4, old('painting1_kindsof_surfaces'))) selected @endif @endif>Doors</option>
                            <option value="5" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(5, old('painting1_kindsof_surfaces'))) selected @endif @endif>Stucco</option>
                            <option value="6" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(6, old('painting1_kindsof_surfaces'))) selected @endif @endif>Shutters</option>
                            <option value="7" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(7, old('painting1_kindsof_surfaces'))) selected @endif @endif>Fence</option>
                            <option value="8" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(8, old('painting1_kindsof_surfaces'))) selected @endif @endif>Masonry (brick/stone)</option>
                            <option value="9" @if(!empty(old('painting1_kindsof_surfaces'))) @if(in_array(9, old('painting1_kindsof_surfaces'))) selected @endif @endif>Other</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service2" class="service-block"
         @if(!empty(old('painting_service'))) @if(!(in_array(2, old('painting_service')))) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Interior Home or Surfaces - Paint or Stain</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting2_rooms_number">Number of Rooms<span class="requiredFields">*</span></label>
                    <select id="painting2_rooms_number" name="painting2_rooms_number[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Number of Rooms">
                            <option value="1" @if(!empty(old('painting2_rooms_number'))) @if(in_array(1, old('painting2_rooms_number'))) selected @endif @endif>1-2</option>
                            <option value="2" @if(!empty(old('painting2_rooms_number'))) @if(in_array(2, old('painting2_rooms_number'))) selected @endif @endif>3-4</option>
                            <option value="3" @if(!empty(old('painting2_rooms_number'))) @if(in_array(3, old('painting2_rooms_number'))) selected @endif @endif>5-6</option>
                            <option value="4" @if(!empty(old('painting2_rooms_number'))) @if(in_array(4, old('painting2_rooms_number'))) selected @endif @endif>7-8</option>
                            <option value="5" @if(!empty(old('painting2_rooms_number'))) @if(in_array(5, old('painting2_rooms_number'))) selected @endif @endif>9 or more</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting2_typeof_paint">Areas that you paint?<span class="requiredFields">*</span></label>
                    <select id="painting2_typeof_paint" name="painting2_typeof_paint[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you paint?">
                            <option value="1" @if(!empty(old('painting2_typeof_paint'))) @if(in_array(1, old('painting2_typeof_paint'))) selected @endif @endif>Walls</option>
                            <option value="2" @if(!empty(old('painting2_typeof_paint'))) @if(in_array(2, old('painting2_typeof_paint'))) selected @endif @endif>Walls And Ceilings</option>
                            <option value="3" @if(!empty(old('painting2_typeof_paint'))) @if(in_array(3, old('painting2_typeof_paint'))) selected @endif @endif>Ceilings</option>
                            <option value="4" @if(!empty(old('painting2_typeof_paint'))) @if(in_array(4, old('painting2_typeof_paint'))) selected @endif @endif>Others</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service3" class="service-block"
         @if(!empty(old('painting_service'))) @if(!(in_array(3, old('painting_service')))) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Painting or Staining - Small Projects</h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="painting3_each_feature">Areas that you can paint and/or staine? <span class="requiredFields">*</span></label>
                    <select id="painting3_each_feature" name="painting3_each_feature[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you can paint and/or staine?">
                            <option value="1" @if(!empty(old('painting3_each_feature'))) @if(in_array(1, old('painting3_each_feature'))) selected @endif @endif>Exterior Door(s)</option>
                            <option value="2" @if(!empty(old('painting3_each_feature'))) @if(in_array(2, old('painting3_each_feature'))) selected @endif @endif>Exterior Siding</option>
                            <option value="3" @if(!empty(old('painting3_each_feature'))) @if(in_array(3, old('painting3_each_feature'))) selected @endif @endif>Exterior Wood Trim</option>
                            <option value="4" @if(!empty(old('painting3_each_feature'))) @if(in_array(4, old('painting3_each_feature'))) selected @endif @endif>Fencing / Gates</option>
                            <option value="5" @if(!empty(old('painting3_each_feature'))) @if(in_array(5, old('painting3_each_feature'))) selected @endif @endif>Interior Door(s)</option>
                            <option value="6" @if(!empty(old('painting3_each_feature'))) @if(in_array(6, old('painting3_each_feature'))) selected @endif @endif>Interior Walls</option>
                            <option value="7" @if(!empty(old('painting3_each_feature'))) @if(in_array(7, old('painting3_each_feature'))) selected @endif @endif>Interior Wood Trim</option>
                            <option value="8" @if(!empty(old('painting3_each_feature'))) @if(in_array(8, old('painting3_each_feature'))) selected @endif @endif>Ceiling</option>
                            <option value="9" @if(!empty(old('painting3_each_feature'))) @if(in_array(9, old('painting3_each_feature'))) selected @endif @endif>Cabinetry</option>
                            <option value="10" @if(!empty(old('painting3_each_feature'))) @if(in_array(10, old('painting3_each_feature'))) selected @endif @endif>Fireplace</option>
                            <option value="11" @if(!empty(old('painting3_each_feature'))) @if(in_array(11, old('painting3_each_feature'))) selected @endif @endif>Paneling</option>
                            <option value="12" @if(!empty(old('painting3_each_feature'))) @if(in_array(12, old('painting3_each_feature'))) selected @endif @endif>Others</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service4" class="service-block"
         @if(!empty(old('painting_service'))) @if(!(in_array(4, old('painting_service')))) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Metal Roofing - Paint</h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="painting4_existing_roof">Type/condition of the roof you wish to work with<span class="requiredFields">*</span></label>
                    <select id="painting4_existing_roof" name="painting4_existing_roof[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type/condition of the roof you wish to work with">
                            <option value="1" @if(!empty(old('painting4_existing_roof'))) @if(in_array(1, old('painting4_existing_roof'))) selected @endif @endif>Peeling or Blistering</option>
                            <option value="2" @if(!empty(old('painting4_existing_roof'))) @if(in_array(2, old('painting4_existing_roof'))) selected @endif @endif>Bleeding</option>
                            <option value="3" @if(!empty(old('painting4_existing_roof'))) @if(in_array(3, old('painting4_existing_roof'))) selected @endif @endif>Nail Stains</option>
                            <option value="4" @if(!empty(old('painting4_existing_roof'))) @if(in_array(4, old('painting4_existing_roof'))) selected @endif @endif>Mildew</option>
                            <option value="5" @if(!empty(old('painting4_existing_roof'))) @if(in_array(5, old('painting4_existing_roof'))) selected @endif @endif>Chalking</option>
                            <option value="6" @if(!empty(old('painting4_existing_roof'))) @if(in_array(6, old('painting4_existing_roof'))) selected @endif @endif>No Known Problems</option>
                            <option value="7" @if(!empty(old('painting4_existing_roof'))) @if(in_array(7, old('painting4_existing_roof'))) selected @endif @endif>Fair Condition</option>
                            <option value="8" @if(!empty(old('painting4_existing_roof'))) @if(in_array(8, old('painting4_existing_roof'))) selected @endif @endif>Never Been Painted Before</option>
                            <option value="9" @if(!empty(old('painting4_existing_roof'))) @if(in_array(9, old('painting4_existing_roof'))) selected @endif @endif>Others</option>
                            <option value="10" @if(!empty(old('painting4_existing_roof'))) @if(in_array(10, old('painting4_existing_roof'))) selected @endif @endif>Don't Know</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="painting_service5" class="service-block"
         @if(!empty(old('painting_service'))) @if(!(in_array(5, old('painting_service')))) style="display:none;" @endif @else style="display:none;" @endif>
        <h6>Specialty Painting - Textures</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting5_kindof_texturing">Type of project<span class="requiredFields">*</span></label>
                    <select id="painting5_kindof_texturing" name="painting5_kindof_texturing[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Type of project">
                            <option value="1" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(1, old('painting5_kindof_texturing'))) selected @endif @endif>Apply Texture To Unfinished Drywall for Paint</option>
                            <option value="2" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(2, old('painting5_kindof_texturing'))) selected @endif @endif>Match New Drywall To Exisiting Walls/Ceilings</option>
                            <option value="3" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(3, old('painting5_kindof_texturing'))) selected @endif @endif>Repair / Patch Drywall</option>
                            <option value="4" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(4, old('painting5_kindof_texturing'))) selected @endif @endif>Prepare For Wallpaper / Special Finish</option>
                            <option value="5" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(5, old('painting5_kindof_texturing'))) selected @endif @endif>Remove Popcorn Acoustic Ceiling Spray</option>
                            <option value="6" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(6, old('painting5_kindof_texturing'))) selected @endif @endif>Create Faux Effects</option>
                            <option value="7" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(7, old('painting5_kindof_texturing'))) selected @endif @endif>Paint Also Needed</option>
                            <option value="8" @if(!empty(old('painting5_kindof_texturing'))) @if(in_array(8, old('painting5_kindof_texturing'))) selected @endif @endif>Other</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="painting5_surfaces_textured">Areas that you surface<span class="requiredFields">*</span></label>
                    <select id="painting5_surfaces_textured" name="painting5_surfaces_textured[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Areas that you surface">
                            <option value="1" @if(!empty(old('painting5_surfaces_textured'))) @if(in_array(1, old('painting5_surfaces_textured'))) selected @endif @endif>Walls</option>
                            <option value="2" @if(!empty(old('painting5_surfaces_textured'))) @if(in_array(2, old('painting5_surfaces_textured'))) selected @endif @endif>Ceilings</option>
                            <option value="3" @if(!empty(old('painting5_surfaces_textured'))) @if(in_array(3, old('painting5_surfaces_textured'))) selected @endif @endif>Others</option>
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
                        <option value="1" @if(!empty(old('painting1_stories_number'))) @if(in_array(1, old('painting1_stories_number'))) selected @endif @endif>One Story</option>
                        <option value="2" @if(!empty(old('painting1_stories_number'))) @if(in_array(2, old('painting1_stories_number'))) selected @endif @endif>Two Stories</option>
                        <option value="3" @if(!empty(old('painting1_stories_number'))) @if(in_array(3, old('painting1_stories_number'))) selected @endif @endif>Three Stories or more</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="historical_structure">Do you wish to receive historical structure painting project?<span class="requiredFields">*</span></label>
                <select id="historical_structure" name="historical_structure[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Do you wish to receive historical structure painting project?">
                        <option value="1" @if(!empty(old('historical_structure'))) @if(in_array(1, old('historical_structure'))) selected @endif @endif>Yes</option>
                        <option value="2" @if(!empty(old('historical_structure'))) @if(in_array(2, old('historical_structure'))) selected @endif @endif>No</option>
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
                        <option value="Yes" @if(!empty(old('auto_insurance_license'))) @if(in_array("Yes", old('auto_insurance_license'))) selected @endif @endif>Yes</option>
                        <option value="No" @if(!empty(old('auto_insurance_license'))) @if(in_array("No", old('auto_insurance_license'))) selected @endif @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="driver_experience">Are you a good driver?<span class="requiredFields">*</span></label>
                <select id="driver_experience" name="driver_experience[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Are you a good driver?">
                        <option value="Yes" @if(!empty(old('driver_experience'))) @if(in_array("Yes", old('driver_experience'))) selected @endif @endif>Yes</option>
                        <option value="No" @if(!empty(old('driver_experience'))) @if(in_array("No", old('driver_experience'))) selected @endif @endif>No</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="coverage_type">Insurance Coverage Type:<span class="requiredFields">*</span></label>
                <select id="coverage_type" name="coverage_type[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Insurance Coverage Type:">
                        <option value="Preferred" @if(!empty(old('coverage_type'))) @if(in_array("Preferred", old('coverage_type'))) selected @endif @endif>Preferred</option>
                        <option value="Premium" @if(!empty(old('coverage_type'))) @if(in_array("Premium", old('coverage_type'))) selected @endif @endif>Premium</option>
                        <option value="Standard" @if(!empty(old('coverage_type'))) @if(in_array("Standard", old('coverage_type'))) selected @endif @endif>Standard</option>
                        <option value="State Minimum" @if(!empty(old('coverage_type'))) @if(in_array("State Minimum", old('coverage_type'))) selected @endif @endif>State Minimum</option>
                        <option value="Interested" @if(!empty(old('coverage_type'))) @if(in_array("Interested", old('coverage_type'))) selected @endif @endif>Interested</option>
                        <option value="Not Interested" @if(!empty(old('coverage_type'))) @if(in_array("Not Interested", old('coverage_type'))) selected @endif @endif>Not Interested</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="license_status">License Status:<span class="requiredFields">*</span></label>
                <select id="license_status" name="license_status[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="License Status:">
                        <option value="Active" @if(!empty(old('license_status'))) @if(in_array("Active", old('license_status'))) selected @endif @endif>Active</option>
                        <option value="International" @if(!empty(old('license_status'))) @if(in_array("International", old('license_status'))) selected @endif @endif>International</option>
                        <option value="Learner" @if(!empty(old('license_status'))) @if(in_array("Learner", old('license_status'))) selected @endif @endif>Learner</option>
                        <option value="Probation" @if(!empty(old('license_status'))) @if(in_array("Probation", old('license_status'))) selected @endif @endif>Probation</option>
                        <option value="Restricted" @if(!empty(old('license_status'))) @if(in_array("Restricted", old('license_status'))) selected @endif @endif>Restricted</option>
                        <option value="Suspended" @if(!empty(old('license_status'))) @if(in_array("Suspended", old('license_status'))) selected @endif @endif>Suspended</option>
                        <option value="Temporary" @if(!empty(old('license_status'))) @if(in_array("No", old('license_status'))) selected @endif @endif>Temporary</option>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="submodel">Car Submodel: <span class="requiredFields">*</span></label>
                <select id="submodel" name="submodel[]" class="select2 form-control select2-multiple questions_div_section_select_campaign" multiple="multiple" data-placeholder="Choose ...">
                    <optgroup label="Car Submodel:">
                        <option value="Micros" @if(!empty(old('submodel'))) @if(in_array("Micros", old('submodel'))) selected @endif @endif>Micros</option>
                        <option value="Hatchback" @if(!empty(old('submodel'))) @if(in_array("Hatchback", old('submodel'))) selected @endif @endif>Hatchback</option>
                        <option value="Fastback" @if(!empty(old('submodel'))) @if(in_array("Fastback", old('submodel'))) selected @endif @endif>Fastback</option>
                        <option value="Sedan" @if(!empty(old('submodel'))) @if(in_array("Sedan", old('submodel'))) selected @endif @endif>Sedan</option>
                        <option value="Crossover" @if(!empty(old('submodel'))) @if(in_array("Crossover", old('submodel'))) selected @endif @endif>Crossover</option>
                        <option value="SUV" @if(!empty(old('submodel'))) @if(in_array("SUV", old('submodel'))) selected @endif @endif>SUV</option>
                        <option value="MPV" @if(!empty(old('submodel'))) @if(in_array("MPV", old('submodel'))) selected @endif @endif>MPV</option>
                        <option value="Convertible" @if(!empty(old('submodel'))) @if(in_array("Convertible", old('submodel'))) selected @endif @endif>Convertible</option>
                        <option value="Wagon" @if(!empty(old('submodel'))) @if(in_array("Wagon", old('submodel'))) selected @endif @endif>Wagon</option>
                        <option value="Luxury" @if(!empty(old('submodel'))) @if(in_array("Luxury", old('submodel'))) selected @endif @endif>Luxury</option>
                        <option value="Antique" @if(!empty(old('submodel'))) @if(in_array("Antique", old('submodel'))) selected @endif @endif>Antique</option>
                        <option value="Coupe" @if(!empty(old('submodel'))) @if(in_array("Coupe", old('submodel'))) selected @endif @endif>Coupe</option>
                        <option value="Sports Car" @if(!empty(old('submodel'))) @if(in_array("Sports Car", old('submodel'))) selected @endif @endif>Sports Car</option>
                        <option value="Supercar" @if(!empty(old('submodel'))) @if(in_array("Supercar", old('submodel'))) selected @endif @endif>Supercar</option>
                        <option value="Muscle Car" @if(!empty(old('submodel'))) @if(in_array("Muscle Car", old('submodel'))) selected @endif @endif>Muscle Car</option>
                        <option value="Limousine" @if(!empty(old('submodel'))) @if(in_array("Limousine", old('submodel'))) selected @endif @endif>Limousine</option>
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
                                <option value="{{ $state->state_code }}">{{ $state->state_code }}</option>
                            @endforeach
                        @endif
                    </optgroup>
                </select>
            </div>
        </div>
    </div>
</div>
