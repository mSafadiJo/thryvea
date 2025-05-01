<legend>Delivery Method</legend>

<div class="row m-t-20">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="deliveryMethod">Delivery Method<span class="requiredFields">*</span></label>
                    <select id="deliveryMethod" name="deliveryMethod[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                        <optgroup label="Delivery Method">
                            <option value="1" @if(!empty(old('deliveryMethod'))) @if(in_array(1, old('deliveryMethod'))) selected @endif @endif>SMS</option>
                            <option value="2" @if(!empty(old('deliveryMethod'))) @if(in_array(2, old('deliveryMethod'))) selected @endif @endif>Email</option>
                            <option value="3" @if(!empty(old('deliveryMethod'))) @if(in_array(3, old('deliveryMethod'))) selected @endif @endif>CRM</option>
                        </optgroup>
                    </select>
                </div>
            </div>
        </div>
        {{--email design--}}

        <div class="row email-phone-Dynamic">
            <div class="col-md-6">
                <div class="card h-100 crm email-card">
                    <h2>Emails</h2>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="FirstEmail">First Email</label>
                            <input type="email" name="FirstEmail" class="form-control" id="FirstEmail" aria-describedby="FirstEmail">
                        </div>
                        <div class="form-group">
                            <label for="SecondEmail">Email CC1</label>
                            <input type="email" name="SecondEmail" class="form-control" id="SecondEmail" aria-describedby="SecondEmail">
                        </div>
                        <div class="form-group">
                            <label for="ThirdEmail">Email CC2</label>
                            <input type="email" name="ThirdEmail" class="form-control" id="ThirdEmail" aria-describedby="ThirdEmail">
                        </div>
                        <div class="form-group">
                            <label for="FourthEmail">Email CC3</label>
                            <input type="email" name="FourthEmail" class="form-control" id="FourthEmail" aria-describedby="FourthEmail">
                        </div>
                        <div class="form-group">
                            <label for="FifthEmail">Email CC4</label>
                            <input type="email" name="FifthEmail" class="form-control" id="FifthEmail" aria-describedby="FifthEmail">
                        </div>
                        <div class="form-group">
                            <label for="SixthEmail">Email CC5</label>
                            <input type="email" name="SixthEmail" class="form-control" id="SixthEmail" aria-describedby="SixthEmail">
                        </div>
                        <div class="form-group">
                            <label for="SubjectEmail">Subject Email</label>
                            <input type="text" name="SubjectEmail" class="form-control" id="SubjectEmail" aria-describedby="SubjectEmail">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 crm phone-card">
                    <h2>SMS</h2>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="First Phone">First Phone Number</label>
                            <input type="text" name="FirstPhone" class="form-control" id="FirstPhone" aria-describedby="FirstPhone">
                        </div>
                        <div class="form-group">
                            <label for="Second Phone">Second Phone Number</label>
                            <input type="text" name="SecondPhone" class="form-control" id="SecondPhone" aria-describedby="SecondPhone">
                        </div>
                        <div class="form-group">
                            <label for="Third Phone">Third Phone Number</label>
                            <input type="text" name="ThirdPhone" class="form-control" id="ThirdPhone" aria-describedby="ThirdPhone">
                        </div>
                    </div>
                </div>
            </div>


        </div>

        {{--crm design--}}
        <div class="crmDynamic">
            <h5>  If You Have a Custom CRM, Leave All The Fields Empty  </h5>
            <p class="is_ping_account_campaign">
                If the delivery method is PING & POST CRM, turn the toggle on
                <label class="switch crmSwitch">
                    <input type="checkbox" name="is_ping_account" id="is_ping_account_toggle" value="1" @if(old('is_ping_account') == 1) checked @endif>
                    <span class="slider round coolToolsToggle "></span>
                </label>
            </p>
            <p class="is_ping_account_campaign">
                To connect multi-CRM's with direct POST, this is only available in direct POST.
                <label class="switch is_multi_crms">
                    <input type="checkbox" name="is_multi_crms" value="1" @if(old('is_multi_crms')) checked @endif @if(old('is_ping_account') == 1) checked @endif>
                    <span class="slider round is_multi_crms "></span>
                </label>
            </p>
            <div class="row">
                <div class="col-md-12">
                    @php
                        $crm_type_arr = array();
                        if(!empty(old('crm_type'))){
                            $crm_type_arr = (is_array(old('crm_type')) ? old('crm_type') : array(old('crm_type')));
                        }
                    @endphp
                    @if(old('is_multi_crms') == 1)
                        <select class="select2 form-control select2Multiple" multiple name="crm_type[]" id="input-campaign-crm-select" data-placeholder="Choose ...">
                            @else
                                <select class="select2 form-control" name="crm_type" id="input-campaign-crm-select" data-placeholder="Choose ...">
                                    @endif
                                    <optgroup label="CRM's">
                                        <option value="0" @if(in_array(0, $crm_type_arr)) selected @endif>Custom</option>
                                        <option value="1" @if(in_array(1, $crm_type_arr)) selected @endif>CallTools</option>
                                        <option value="2" @if(in_array(2, $crm_type_arr)) selected @endif>Five9</option>
                                        <option value="3" @if(in_array(3, $crm_type_arr)) selected @endif>Leads Pedia</option>
                                        <option value="4" @if(in_array(4, $crm_type_arr)) selected @endif>Hubspot</option>
                                        <option value="5" @if(in_array(5, $crm_type_arr)) selected @endif>BuilderTrend</option>
                                        <option value="6" @if(in_array(6, $crm_type_arr)) selected @endif>Pipe Drive</option>
                                        <option value="7" @if(in_array(7, $crm_type_arr)) selected @endif>jangle</option>
                                        <option value="8" @if(in_array(8, $crm_type_arr)) selected @endif>LEAD PERFECTION</option>
                                        <option value="9" @if(in_array(9, $crm_type_arr)) selected @endif>Improveit360</option>
                                        <option value="10" @if(in_array(10, $crm_type_arr)) selected @endif>Lead Conduit</option>
                                        <option value="11" @if(in_array(11, $crm_type_arr)) selected @endif>Marketsharpm</option>
                                        <option value="12" @if(in_array(12, $crm_type_arr)) selected @endif>Lead Portal</option>
                                        <option value="13" @if(in_array(13, $crm_type_arr)) selected @endif>leads Pedia Track</option>
                                        <option value="14" @if(in_array(14, $crm_type_arr)) selected @endif>AccuLynx API</option>
                                        <option value="15" @if(in_array(15, $crm_type_arr)) selected @endif>Zoho</option>
                                        <option value="16" @if(in_array(16, $crm_type_arr)) selected @endif>Hatch {}</option>
                                        <option value="17" @if(in_array(17, $crm_type_arr)) selected @endif>SalesForce</option>
                                        <option value="18" @if(in_array(18, $crm_type_arr)) selected @endif>Builder Prime</option>
                                        <option value="19" @if(in_array(19, $crm_type_arr)) selected @endif>Zapier</option>
                                        <option value="20" @if(in_array(20, $crm_type_arr)) selected @endif>SetShape</option>
                                        <option value="21" @if(in_array(21, $crm_type_arr)) selected @endif>Job Nimbus</option>
                                        <option value="22" @if(in_array(22, $crm_type_arr)) selected @endif>Sunbase</option>
                                    </optgroup>
                                </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 campaign-crm-div campaign-crm-div-1">
                    <div class="card h-100 crm">
                        <h2>CallTools</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Api Key">Api Key</label>
                                <input type="text" name="ApiKey" class="form-control input-campaign-crm" id="ApiKey" aria-describedby="ApiKey"
                                       @if(empty(old('ApiKey'))) value="" @else value="{{old('ApiKey')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="Api Key">File ID</label>
                                <input type="text" name="FileId" class="form-control input-campaign-crm" id="FileId" aria-describedby="FileId"
                                       @if(empty(old('FileId'))) value="" @else value="{{old('FileId')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-2">
                    <div class="card h-100 crm">
                        <h2>Five9</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Five9 Domian">Five9 Domian</label>
                                <input type="text" class="form-control input-campaign-crm" name="Five9Domian" id="Five9Domian" aria-describedby="Five9Domian"
                                       @if(empty(old('Five9Domian'))) value="" @else value="{{old('Five9Domian')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="Five9 List">Five9 List</label>
                                <input type="text" class="form-control input-campaign-crm" name="Five9List" id="Five9List" aria-describedby="Five9List"
                                       @if(empty(old('Five9List'))) value="" @else value="{{old('Five9List')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-3">
                    <div class="card h-100 crm">
                        <h2>Leads Pedia</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ping url">PING url</label>
                                <input type="text" class="form-control input-campaign-crm" name="leads_pedia_url_ping" id="leadsPediaUrlPing" aria-describedby="CampineKey"
                                       @if(empty(old('leads_pedia_url_ping'))) value="" @else value="{{ old('leads_pedia_url_ping') }}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Leads Pedia Url">POST Url</label>
                                <input type="text" class="form-control input-campaign-crm" name="LeadsPediaUrl" id="LeadsPediaUrl" aria-describedby="LeadsPediaUrl"
                                       @if(empty(old('LeadsPediaUrl'))) value="" @else value="{{old('LeadsPediaUrl')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Campaign ID">Campaign ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="IP_Campaign_ID" id="IPCampaignID" aria-describedby="IPCampaignID"
                                       @if(empty(old('IP_Campaign_ID'))) value="" @else value="{{old('IP_Campaign_ID')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Campine Key">Campaign Key</label>
                                <input type="text" class="form-control input-campaign-crm" name="CampineKey" id="CampineKey" aria-describedby="CampineKey"
                                       @if(empty(old('CampineKey'))) value="" @else value="{{old('CampineKey')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-4">
                    <div class="card h-100 crm">
                        <h2>Hubspot</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <h5>Switch On To Use Hubspot's Access Key Instead Of API Key</h5>
                                <label class="switch hubspot_key_type">
                                    <input type="checkbox" class="check-campaign-crm" name="hubspot_key_type" id="hubspot_key_type" value="1"
                                           @if(old('hubspot_key_type') == 1) checked @endif>
                                    <span class="slider round hubspot_key_type"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="hubspotKey">Hubspot Key</label>
                                <input type="text" class="form-control input-campaign-crm" name="hubspotKey" id="hubspotKey" aria-describedby="HubspotKey" value="{{old('hubspotKey')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-5">
                    <div class="card h-100 crm">
                        <h2>BuilderTrend</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Builder ID">Builder ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="builderId" id="builderId" aria-describedby="BuilderID"
                                       @if(empty(old('builderId'))) value="" @else value="{{old('builderId')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-6">
                    <div class="card h-100 crm">
                        <h2>Pipe Drive</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Builder ID">Api Token</label>
                                <input type="text" class="form-control input-campaign-crm" name="api_token" id="api_token" aria-describedby="ApiToken"
                                       @if(empty(old('api_token'))) value="" @else value="{{old('api_token')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Builder ID">Domain</label>
                                <input type="text" class="form-control input-campaign-crm" name="persons_domain" id="persons_domain" aria-describedby="PersonsDomain"
                                       @if(empty(old('persons_domain'))) value="" @else value="{{old('persons_domain')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="pipedrive_person" style="vertical-align: sub;">Persons</label>
                                <label class="switch">
                                    <input type="checkbox" id="pipedrive_person" class="check-campaign-crm" name="pipedrive_person" value="1"
                                           @if(old('pipedrive_person') == 1 ) checked @endif>
                                    <span class="slider round deals_leadsToggle "></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="pipedrive_deals_leads" style="vertical-align: sub;">Deals/Leads</label>
                                <label class="switch">
                                    <input type="checkbox" id="pipedrive_deals_leads" class="check-campaign-crm" name="pipedrive_deals_leads" value="1"
                                           @if(old('pipedrive_deals_leads') == 1 ) checked @endif>
                                    <span class="slider round deals_leadsToggle "></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-7">
                    <div class="card h-100 crm">
                        <h2>jangle</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Authorization">Authorization</label>
                                <input type="text" class="form-control input-campaign-crm" name="Authorization" id="AuthorizationJangle" aria-describedby="AuthorizationJangle"
                                       @if(empty(old('Authorization'))) value="" @else value="{{old('Authorization')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="PING URL">PING URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="PingUrl" id="PingUrl" aria-describedby="PingUrl"
                                       @if(empty(old('PingUrl'))) value="" @else value="{{old('PingUrl')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="POST URL">POST URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="PostUrl" id="PostUrl" aria-describedby="PostUrl"
                                       @if(empty(old('PostUrl'))) value="" @else value="{{old('PostUrl')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-8">
                    <div class="card h-100 crm">
                        <h2>LEAD PERFECTION</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ping url">URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="lead_perfection_url" id="lead_perfection_url" aria-describedby="CampineKey"
                                       @if(empty(old("lead_perfection_url"))) value="" @else value="{{old("lead_perfection_url")}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Campaign ID">Source ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="lead_perfection_srs_id" id="lead_perfection_srs_id" aria-describedby="IPCampaignID"
                                       @if(empty(old("lead_perfection_srs_id"))) value="" @else value="{{old("lead_perfection_srs_id")}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Campine Key">Product ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="lead_perfection_pro_id" id="lead_perfection_pro_id" aria-describedby="CampineKey"
                                       @if(empty(old("lead_perfection_pro_id"))) value="" @else value="{{old("lead_perfection_pro_id")}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="lead_perfection_pro_desc<">Product Desc</label>
                                <input type="text" class="form-control input-campaign-crm" name="lead_perfection_pro_desc" id="lead_perfection_pro_desc" aria-describedby="Product Desc"
                                       @if(empty(old("lead_perfection_pro_desc"))) value="" @else value="{{old("lead_perfection_pro_desc")}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="lead_perfection_sender">Sender</label>
                                <input type="text" class="form-control input-campaign-crm" name="lead_perfection_sender" id="lead_perfection_sender" aria-describedby="Sender"
                                       @if(empty(old("lead_perfection_sender"))) value="" @else value="{{old("lead_perfection_sender")}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-9">
                    <div class="card h-100 crm">
                        <h2>Improveit360</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="Builder ID">Improveit360 URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="improveit360_url" id="improveit360_url" aria-describedby="improveit360_url"
                                       @if(empty(old('improveit360_url'))) value="" @else value="{{old('improveit360_url')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Builder ID">Improveit360 Source</label>
                                <input type="text" class="form-control input-campaign-crm" name="improveit360_source" id="improveit360_source" aria-describedby="improveit360_source"
                                       @if(empty(old('improveit360_source'))) value="" @else value="{{old('improveit360_source')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="improveit360_market_segment">Improveit360 Market Segment</label>
                                <input type="text" class="form-control input-campaign-crm" name="improveit360_market_segment" id="improveit360_market_segment" aria-describedby="improveit360_market_segment"
                                       @if(empty(old('improveit360_market_segment'))) value="" @else value="{{old('improveit360_market_segment')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="improveit360_source_type">Improveit360 Source Type</label>
                                <input type="text" class="form-control input-campaign-crm" name="improveit360_source_type" id="improveit360_source_type" aria-describedby="improveit360_source_type"
                                       @if(empty(old('improveit360_source_type'))) value="" @else value="{{old('improveit360_source_type')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="improveit360_project">Improveit360 Project</label>
                                <input type="text" class="form-control input-campaign-crm" name="improveit360_project" id="improveit360_project" aria-describedby="improveit360_project"
                                       @if(empty(old('improveit360_project'))) value="" @else value="{{old('improveit360_project')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-10">
                    <div class="card h-100 crm">
                        <h2>Lead Conduit</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="leadconduit_url">URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="leadconduit_url" id="leadconduit_url" aria-describedby="leadconduit_url"
                                       @if(empty(old('leadconduit_url'))) value="" @else value="{{old('leadconduit_url')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-11">
                    <div class="card h-100 crm">
                        <h2>Marketsharpm</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="MSM_source">MSM_source</label>
                                <input type="text" class="form-control input-campaign-crm" name="MSM_source" id="MSM_source" aria-describedby="MSM_source"
                                       @if(empty(old('MSM_source'))) value="" @else value="{{old('MSM_source')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="MSM_coy">MSM_coy</label>
                                <input type="text" class="form-control input-campaign-crm" name="MSM_coy" id="MSM_coy" aria-describedby="MSM_coy"
                                       @if(empty(old('MSM_coy'))) value="" @else value="{{old('MSM_coy')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="MSM_formId">MSM_formId</label>
                                <input type="text" class="form-control input-campaign-crm" name="MSM_formId" id="MSM_formId" aria-describedby="MSM_formId"
                                       @if(empty(old('MSM_formId'))) value="" @else value="{{old('MSM_formId')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-12">
                    <div class="card h-100 crm">
                        <h2>Lead Portal</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="leadconduit_url">Key</label>
                                <input type="text" class="form-control input-campaign-crm" name="leadPortal_Key" id="leadPortal_Key" aria-describedby="leadPortal_Key"
                                       @if(empty(old('leadPortal_Key'))) value="" @else value="{{old('leadPortal_Key')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="leadconduit_url">SRC</label>
                                <input type="text" class="form-control input-campaign-crm" name="leadPortal_SRC" id="leadPortal_SRC" aria-describedby="leadPortal_SRC"
                                       @if(empty(old('leadPortal_SRC'))) value="" @else value="{{old('leadPortal_SRC')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="leadPortal_type">Type</label>
                                <input type="text" class="form-control input-campaign-crm" name="leadPortal_type" id="leadPortal_type" aria-describedby="leadPortal_type"
                                       @if(empty(old('leadPortal_type'))) value="" @else value="{{old('leadPortal_type')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="leadconduit_url">Ping Url</label>
                                <input type="text" class="form-control input-campaign-crm" name="leadPortal_api_url" id="leadPortal_api_url" aria-describedby="leadPortal_api_url"
                                       @if(empty(old('api_url'))) value="" @else value="{{old('api_url')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-13">
                    <div class="card h-100 crm">
                        <h2>leads Pedia Track</h2>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="lp campaign id">lp campaign id</label>
                                <input type="text" class="form-control input-campaign-crm" name="lp_campaign_id" id="lp_campaign_id" aria-describedby="lp_campaign_id"
                                       @if(empty(old('lp_campaign_id'))) value="" @else value="{{old('lp_campaign_id')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="lp campaign key">lp campaign key</label>
                                <input type="text" class="form-control input-campaign-crm" name="lp_campaign_key" id="lp_campaign_key" aria-describedby="lp_campaign_key"
                                       @if(empty(old('lp_campaign_key'))) value="" @else value="{{old('lp_campaign_key')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Ping Url">Ping Url</label>
                                <input type="text" class="form-control input-campaign-crm" name="leads_pedia_track_ping_url" id="leads_pedia_track_ping_url" aria-describedby="leads_pedia_track_ping_url"
                                       @if(empty(old('leads_pedia_track_ping_url'))) value="" @else value="{{old('leads_pedia_track_ping_url')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Post Url">Post Url</label>
                                <input type="text" class="form-control input-campaign-crm" name="leads_pedia_track_post_url" id="leads_pedia_track_ping_url" aria-describedby="leads_pedia_track_ping_url"
                                       @if(empty(old('leads_pedia_track_ping_url'))) value="" @else value="{{old('leads_pedia_track_ping_url')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-14">
                    <div class="card h-100 crm">
                        <h2>AccuLynx API</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="acculynx_api_token">API Key</label>
                                <input type="text" class="form-control input-campaign-crm" name="acculynx_api_key" id="acculynx_api_key" aria-describedby="acculynx_api_key"
                                       @if(empty(old("acculynx_api_key"))) value="" @else value="{{old("acculynx_api_key")}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-15">
                    <div class="card h-100 crm">
                        <h2>Zoho</h2>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="refresh_token">Refresh Token</label>
                                <input type="text" class="form-control input-campaign-crm" name="refresh_token" id="refresh_token" aria-describedby="refresh_token"
                                       @if(empty(old('refresh_token'))) value="" @else value="{{old('refresh_token')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="client_id">Client ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="client_id" id="client_id" aria-describedby="client_id"
                                       @if(empty(old('client_id'))) value="" @else value="{{old('client_id')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="client_secret">Client Secret</label>
                                <input type="text" class="form-control input-campaign-crm" name="client_secret" id="client_secret" aria-describedby="client_secret"
                                       @if(empty(old('client_secret'))) value="" @else value="{{old('client_secret')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="redirect_url">Redirect URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="redirect_url" id="redirect_url" aria-describedby="redirect_url"
                                       @if(empty(old('redirect_url'))) value="" @else value="{{old('redirect_url')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="Lead_Source">Lead Source</label>
                                <input type="text" class="form-control input-campaign-crm" name="Lead_Source" id="Lead_Source" aria-describedby="Lead_Source"
                                       @if(empty(old('Lead_Source'))) value="" @else value="{{old('Lead_Source')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-16">
                    <div class="card h-100 crm">
                        <h2>Hatch {}</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="HatchDeptID">Dept ID</label>
                                <input type="text" class="form-control input-campaign-crm" name="HatchDeptID" id="HatchDeptID" aria-describedby="HatchDeptID"
                                       @if(empty(old('HatchDeptID'))) value="" @else value="{{old('HatchDeptID')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="HatchOrgToken">Org Token</label>
                                <input type="text" class="form-control input-campaign-crm" name="HatchOrgToken" id="HatchOrgToken" aria-describedby="HatchOrgToken"
                                       @if(empty(old('HatchOrgToken'))) value="" @else value="{{old('HatchOrgToken')}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="Hatch_URL_sub">URL Sub</label>
                                <input type="text" class="form-control input-campaign-crm" name="Hatch_URL_sub" id="Hatch_URL_sub" aria-describedby="Hatch_URL_sub"
                                       @if(empty(old('Hatch_URL_sub'))) value="" @else value="{{old('Hatch_URL_sub')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-17">
                    <div class="card h-100 crm">
                        <h2>SalesForce</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="salesforce_oid">oid</label>
                                <input type="text" class="form-control input-campaign-crm" name="salesforce_oid" id="salesforce_oid" aria-describedby="salesforce_oid"
                                       @if(empty(old('salesforce_oid'))) value="" @else value="{{old('salesforce_oid')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="salesforce_lead_source">Lead Source</label>
                                <input type="text" class="form-control input-campaign-crm" name="salesforce_lead_source" id="salesforce_lead_source" aria-describedby="salesforce_lead_source"
                                       @if(empty(old('salesforce_lead_source'))) value="" @else value="{{old('salesforce_lead_source')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="salesforce_url">POST URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="salesforce_url" id="salesforce_url" aria-describedby="salesforce_url"
                                       @if(empty(old('salesforce_url'))) value="" @else value="{{old('salesforce_url')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="salesforce_retURL">Redirect URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="salesforce_retURL" id="salesforce_retURL" aria-describedby="salesforce_retURL"
                                       @if(empty(old('salesforce_retURL'))) value="" @else value="{{old('salesforce_retURL')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-18">
                    <div class="card h-100 crm">
                        <h2>Builder Prime</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="builderPrimePostURL">Posting URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="builderPrimePostURL" id="builderPrimePostURL" aria-describedby="builderPrimePostURL"
                                       @if(empty(old('builderPrimePostURL'))) value="" @else value="{{old('builderPrimePostURL')}}" @endif>
                            </div>

                            <div class="form-group">
                                <label for="builderPrimeSecretKey">Secret Key</label>
                                <input type="text" class="form-control input-campaign-crm" name="builderPrimeSecretKey" id="builderPrimeSecretKey" aria-describedby="builderPrimeSecretKey"
                                       @if(empty(old('builderPrimeSecretKey'))) value="" @else value="{{old('builderPrimeSecretKey')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-19">
                    <div class="card h-100 crm">
                        <h2>Zapier</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="zapier_url">Posting URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="zapier_url" id="zapier_url" aria-describedby="zapier_url"
                                       @if(empty(old('zapier_url'))) value="" @else value="{{old('zapier_url')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-20">
                    <div class="card h-100 crm">
                        <h2>SetShape</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="set_shape_url">Posting URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="set_shape_url" id="set_shape_url" aria-describedby="set_shape_url"
                                       @if(empty(old('set_shape_url'))) value="" @else value="{{old('set_shape_url')}}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-21">
                    <div class="card h-100 crm">
                        <h2>Job Nimbus</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="set_nimbus_key">API key</label>
                                <input type="text" class="form-control input-campaign-crm" name="set_nimbus_key" id="set_nimbus_key" aria-describedby="set_nimbus_key"
                                       @if(empty(old('set_nimbus_key'))) value="" @else value="{{ old('set_nimbus_key') }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 campaign-crm-div campaign-crm-div-22">
                    <div class="card h-100 crm">
                        <h2>Sunbase Data</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sunbase_url">URL</label>
                                <input type="text" class="form-control input-campaign-crm" name="sunbase_url" id="sunbase_url" aria-describedby="sunbase_url"
                                       @if(empty(old('sunbase_url'))) value="" @else value="{{ old('sunbase_url') }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="sunbase_schema_name">Schema Name</label>
                                <input type="text" class="form-control input-campaign-crm" name="sunbase_schema_name" id="sunbase_schema_name" aria-describedby="sunbase_schema_name"
                                       @if(empty(old('sunbase_schema_name'))) value="" @else value="{{ old('sunbase_schema_name') }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{--end crm design--}}

    </div>
</div>
