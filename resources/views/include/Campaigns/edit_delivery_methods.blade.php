<div class="tab-pane fade" id="deliverymethod" role="tabpanel" aria-labelledby="deliverymethod-tab">
    <div class="row m-t-20">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="deliveryMethod">Delivery Method<span class="requiredFields">*</span></label>
                        <select id="deliveryMethod" name="deliveryMethod[]" class="select2 form-control select2-multiple" required="" multiple="multiple" data-placeholder="Choose ...">
                            <optgroup label="Delivery Method">
{{--                                <option value="1" @if( in_array("1", json_decode($campaign->delivery_Method_id,true)) ) selected @endif >SMS</option>--}}
                                <option value="2" @if( in_array("2", json_decode($campaign->delivery_Method_id,true)) ) selected @endif >Email</option>
                                <option value="3" @if( in_array("3", json_decode($campaign->delivery_Method_id,true)) ) selected @endif >CRM</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row email-phone-Dynamic">
                {{--email design--}}
                <div class="col-md-6">
                    <div class="card h-100 crm email-card">
                        <h2>Emails</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="FirstEmail">First Email</label>
                                <input type="email" name="FirstEmail" class="form-control" id="FirstEmail" aria-describedby="FirstEmail"
                                       @if(empty($campaign->email1)) value="" @else value="{{$campaign->email1}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="SecondEmail">Email CC1</label>
                                <input type="email" name="SecondEmail" class="form-control" id="SecondEmail" aria-describedby="SecondEmail"
                                       @if(empty($campaign->email2)) value="" @else value="{{$campaign->email2}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="ThirdEmail">Email CC2</label>
                                <input type="email" name="ThirdEmail" class="form-control" id="ThirdEmail" aria-describedby="ThirdEmail"
                                       @if(empty($campaign->email3)) value="" @else value="{{$campaign->email3}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="FourthEmail">Email CC3</label>
                                <input type="email" name="FourthEmail" class="form-control" id="FourthEmail" aria-describedby="FourthEmail"
                                       @if(empty($campaign->email4)) value="" @else value="{{$campaign->email4}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="FifthEmail">Email CC4</label>
                                <input type="email" name="FifthEmail" class="form-control" id="FifthEmail" aria-describedby="FifthEmail"
                                       @if(empty($campaign->email5)) value="" @else value="{{$campaign->email5}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="SixthEmail">Email CC5</label>
                                <input type="email" name="SixthEmail" class="form-control" id="SixthEmail" aria-describedby="SixthEmail"
                                       @if(empty($campaign->email6)) value="" @else value="{{$campaign->email6}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="SubjectEmail">Subject Email</label>
                                <input type="text" name="SubjectEmail" class="form-control" id="Subject_Email" aria-describedby="Subject_Email"
                                       @if(empty($campaign->subject_email)) value="" @else value="{{$campaign->subject_email}}" @endif
                                >
                            </div>
                        </div>
                    </div>
                </div>
                {{--end email design--}}

                {{--phone design--}}
                <div class="col-md-6">
                    <div class="card h-100 crm phone-card">
                        <h2>SMS</h2>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="First Phone">First Phone Number</label>
                                <input type="text" name="FirstPhone" class="form-control" id="FirstPhone" aria-describedby="FirstPhone"
                                       @if(empty($campaign->phone1)) value="" @else value="{{$campaign->phone1}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="Second Phone">Second Phone Number</label>
                                <input type="text" name="SecondPhone" class="form-control" id="SecondPhone" aria-describedby="SecondPhone"
                                       @if(empty($campaign->phone2)) value="" @else value="{{$campaign->phone2}}" @endif
                                >
                            </div>
                            <div class="form-group">
                                <label for="Third Phone">Third Phone Number</label>
                                <input type="text" name="ThirdPhone" class="form-control" id="ThirdPhone" aria-describedby="ThirdPhone"
                                       @if(empty($campaign->phone3)) value="" @else value="{{$campaign->phone3}}" @endif
                                >
                            </div>
                        </div>
                    </div>
                </div>
                {{--end phone design--}}
            </div>

            {{--crm design--}}
            <div class="crmDynamic">
                <h5>  If You Have a Custom CRM, Leave All The Fields Empty  </h5>
                <p class="is_ping_account_campaign">
                    If the delivery method is PING & POST CRM, turn the toggle on
                    <label class="switch crmSwitch">
                        <input type="checkbox" name="is_ping_account" id="is_ping_account_toggle" value="1" @if($campaign->is_ping_account == 1) checked @endif>
                        <span class="slider round coolToolsToggle "></span>
                    </label>
                </p>
                <p class="is_ping_account_campaign">
                    To connect multi-CRM's with direct POST, this is only available in direct POST.
                    <label class="switch is_multi_crms">
                        <input type="checkbox" name="is_multi_crms" value="1" @if($campaign->is_multi_crms == 1) checked @endif @if($campaign->is_ping_account == 1) disabled @endif>
                        <span class="slider round is_multi_crms "></span>
                    </label>
                </p>
                <div class="row">
                    <div class="col-md-12">
                        @php
                            $campaign->crm = json_decode($campaign->crm, true);
                        @endphp
                        @if($campaign->is_multi_crms == 1)
                            <select class="select2 form-control select2Multiple" multiple name="crm_type[]" id="input-campaign-crm-select" data-placeholder="Choose ...">
                                @else
                                    <select class="select2 form-control" name="crm_type" id="input-campaign-crm-select" data-placeholder="Choose ...">
                                        @endif
                                        <optgroup label="CRM's">
                                            <option value="0" @if(in_array(0, $campaign->crm)) selected @endif>Custom</option>
                                            <option value="1" @if(in_array(1, $campaign->crm)) selected @endif>CallTools</option>
                                            <option value="2" @if(in_array(2, $campaign->crm)) selected @endif>Five9</option>
                                            <option value="3" @if(in_array(3, $campaign->crm)) selected @endif>Leads Pedia</option>
                                            <option value="4" @if(in_array(4, $campaign->crm)) selected @endif>Hubspot</option>
                                            <option value="5" @if(in_array(5, $campaign->crm)) selected @endif>BuilderTrend</option>
                                            <option value="6" @if(in_array(6, $campaign->crm)) selected @endif>Pipe Drive</option>
                                            <option value="7" @if(in_array(7, $campaign->crm)) selected @endif>jangle</option>
                                            <option value="8" @if(in_array(8, $campaign->crm)) selected @endif>LEAD PERFECTION</option>
                                            <option value="9" @if(in_array(9, $campaign->crm)) selected @endif>Improveit360</option>
                                            <option value="10" @if(in_array(10, $campaign->crm)) selected @endif>Lead Conduit</option>
                                            <option value="11" @if(in_array(11, $campaign->crm)) selected @endif>Marketsharpm</option>
                                            <option value="12" @if(in_array(12, $campaign->crm)) selected @endif>Lead Portal</option>
                                            <option value="13" @if(in_array(13, $campaign->crm)) selected @endif>leads Pedia Track</option>
                                            <option value="14" @if(in_array(14, $campaign->crm)) selected @endif>AccuLynx API</option>
                                            <option value="15" @if(in_array(15, $campaign->crm)) selected @endif>Zoho</option>
                                            <option value="16" @if(in_array(16, $campaign->crm)) selected @endif>Hatch {}</option>
                                            <option value="17" @if(in_array(17, $campaign->crm)) selected @endif>SalesForce</option>
                                            <option value="18" @if(in_array(18, $campaign->crm)) selected @endif>Builder Prime</option>
                                            <option value="19" @if(in_array(19, $campaign->crm)) selected @endif>Zapier</option>
                                            <option value="20" @if(in_array(20, $campaign->crm)) selected @endif>SetShape</option>
                                            <option value="21" @if(in_array(21, $campaign->crm)) selected @endif>Job Nimbus</option>
                                            <option value="22" @if(in_array(22, $campaign->crm)) selected @endif>Sunbase</option>
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
                                           @if(empty($crms_array['callToolsTabel']->api_key)) value="" @else value="{{$crms_array['callToolsTabel']->api_key}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="Api Key">File ID</label>
                                    <input type="text" name="FileId" class="form-control input-campaign-crm" id="FileId" aria-describedby="FileId"
                                           @if(empty($crms_array['callToolsTabel']->file_id)) value="" @else value="{{$crms_array['callToolsTabel']->file_id}}" @endif>
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
                                           @if(empty($crms_array['five9Tabel']->five9_domian)) value="" @else value="{{$crms_array['five9Tabel']->five9_domian}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="Five9 List">Five9 List</label>
                                    <input type="text" class="form-control input-campaign-crm" name="Five9List" id="Five9List" aria-describedby="Five9List"
                                           @if(empty($crms_array['five9Tabel']->five9_list)) value="" @else value="{{$crms_array['five9Tabel']->five9_list}}" @endif>
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
                                           @if(empty($crms_array['LeadsPediaTabel']->leads_pedia_url_ping)) value="" @else value="{{$crms_array['LeadsPediaTabel']->leads_pedia_url_ping}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Leads Pedia Url">POST Url</label>
                                    <input type="text" class="form-control input-campaign-crm" name="LeadsPediaUrl" id="LeadsPediaUrl" aria-describedby="LeadsPediaUrl"
                                           @if(empty($crms_array['LeadsPediaTabel']->leads_pedia_url)) value="" @else value="{{$crms_array['LeadsPediaTabel']->leads_pedia_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Campaign ID">Campaign ID</label>
                                    <input type="text" class="form-control input-campaign-crm" name="IP_Campaign_ID" id="IPCampaignID" aria-describedby="IPCampaignID"
                                           @if(empty($crms_array['LeadsPediaTabel']->IP_Campaign_ID)) value="" @else value="{{$crms_array['LeadsPediaTabel']->IP_Campaign_ID}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Campine Key">Campaign Key</label>
                                    <input type="text" class="form-control input-campaign-crm" name="CampineKey" id="CampineKey" aria-describedby="CampineKey"
                                           @if(empty($crms_array['LeadsPediaTabel']->campine_key)) value="" @else value="{{$crms_array['LeadsPediaTabel']->campine_key}}" @endif>
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
                                               @if(!empty($crms_array['hubspotTabel']->key_type)) checked @endif>
                                        <span class="slider round hubspot_key_type"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="hubspotKey">Hubspot Key</label>
                                    <input type="text" class="form-control input-campaign-crm" name="hubspotKey" id="hubspotKey" aria-describedby="HubspotKey"
                                           @if(empty($crms_array['hubspotTabel']->Api_Key)) value="" @else value="{{$crms_array['hubspotTabel']->Api_Key}}" @endif>
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
                                           @if(empty($crms_array['BuilderTrendTabel']->builder_id)) value="" @else value="{{$crms_array['BuilderTrendTabel']->builder_id}}" @endif>
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
                                           @if(empty($crms_array['PipeDriveTabel']->api_token)) value="" @else value="{{$crms_array['PipeDriveTabel']->api_token}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Builder ID">Domain</label>
                                    <input type="text" class="form-control input-campaign-crm" name="persons_domain" id="persons_domain" aria-describedby="PersonsDomain"
                                           @if(empty($crms_array['PipeDriveTabel']->persons_domain)) value="" @else value="{{$crms_array['PipeDriveTabel']->persons_domain}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="pipedrive_person" style="vertical-align: sub;">Persons</label>
                                    <label class="switch">
                                        <input type="checkbox" id="pipedrive_person" class="check-campaign-crm" name="pipedrive_person" value="1"
                                               @if(!empty($crms_array['PipeDriveTabel']->persons)) @if($crms_array['PipeDriveTabel']->persons == 1 ) checked @endif @endif>
                                        <span class="slider round deals_leadsToggle "></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="pipedrive_deals_leads" style="vertical-align: sub;">Deals/Leads</label>
                                    <label class="switch">
                                        <input type="checkbox" id="pipedrive_deals_leads" class="check-campaign-crm" name="pipedrive_deals_leads" value="1"
                                               @if(!empty($crms_array['PipeDriveTabel']->deals_leads)) @if($crms_array['PipeDriveTabel']->deals_leads == 1 ) checked @endif @endif>
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
                                           @if(empty($crms_array['jangelTabel']->Authorization)) value="" @else value="{{$crms_array['jangelTabel']->Authorization}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="PING URL">PING URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="PingUrl" id="PingUrl" aria-describedby="PingUrl"
                                           @if(empty($crms_array['jangelTabel']->PingUrl)) value="" @else value="{{$crms_array['jangelTabel']->PingUrl}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="POST URL">POST URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="PostUrl" id="PostUrl" aria-describedby="PostUrl"
                                           @if(empty($crms_array['jangelTabel']->PostUrl)) value="" @else value="{{$crms_array['jangelTabel']->PostUrl}}" @endif>
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
                                           @if(empty($crms_array['lead_perfectionTabel']->lead_perfection_url)) value="" @else value="{{$crms_array['lead_perfectionTabel']->lead_perfection_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Campaign ID">Source ID</label>
                                    <input type="text" class="form-control input-campaign-crm" name="lead_perfection_srs_id" id="lead_perfection_srs_id" aria-describedby="IPCampaignID"
                                           @if(empty($crms_array['lead_perfectionTabel']->lead_perfection_srs_id)) value="" @else value="{{$crms_array['lead_perfectionTabel']->lead_perfection_srs_id}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="ProductID">Product ID</label>
                                    <input type="text" class="form-control input-campaign-crm" name="lead_perfection_pro_id" id="lead_perfection_pro_id" aria-describedby="Product ID"
                                           @if(empty($crms_array['lead_perfectionTabel']->lead_perfection_pro_id)) value="" @else value="{{$crms_array['lead_perfectionTabel']->lead_perfection_pro_id}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="lead_perfection_pro_desc<">Product Desc</label>
                                    <input type="text" class="form-control input-campaign-crm" name="lead_perfection_pro_desc" id="lead_perfection_pro_desc" aria-describedby="Product Desc"
                                           @if(empty($crms_array['lead_perfectionTabel']->lead_perfection_pro_desc)) value="" @else value="{{$crms_array['lead_perfectionTabel']->lead_perfection_pro_desc}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="lead_perfection_sender">Sender</label>
                                    <input type="text" class="form-control input-campaign-crm" name="lead_perfection_sender" id="lead_perfection_sender" aria-describedby="Sender"
                                           @if(empty($crms_array['lead_perfectionTabel']->lead_perfection_sender)) value="" @else value="{{$crms_array['lead_perfectionTabel']->lead_perfection_sender}}" @endif>
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
                                           @if(empty($crms_array['improveit360Tabel']->improveit360_url)) value="" @else value="{{$crms_array['improveit360Tabel']->improveit360_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Builder ID">Improveit360 Source</label>
                                    <input type="text" class="form-control input-campaign-crm" name="improveit360_source" id="improveit360_source" aria-describedby="improveit360_source"
                                           @if(empty($crms_array['improveit360Tabel']->improveit360_source)) value="" @else value="{{$crms_array['improveit360Tabel']->improveit360_source}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="improveit360_market_segment">Improveit360 Market Segment</label>
                                    <input type="text" class="form-control input-campaign-crm" name="improveit360_market_segment" id="improveit360_market_segment" aria-describedby="improveit360_market_segment"
                                           @if(empty($crms_array['improveit360Tabel']->market_segment)) value="" @else value="{{$crms_array['improveit360Tabel']->market_segment}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="improveit360_source_type">Improveit360 Source Type</label>
                                    <input type="text" class="form-control input-campaign-crm" name="improveit360_source_type" id="improveit360_source_type" aria-describedby="improveit360_source_type"
                                           @if(empty($crms_array['improveit360Tabel']->source_type)) value="" @else value="{{$crms_array['improveit360Tabel']->source_type}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="improveit360_project">Improveit360 Project</label>
                                    <input type="text" class="form-control input-campaign-crm" name="improveit360_project" id="improveit360_project" aria-describedby="improveit360_project"
                                           @if(empty($crms_array['improveit360Tabel']->project)) value="" @else value="{{$crms_array['improveit360Tabel']->project}}" @endif>
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
                                           @if(empty($crms_array['leadconduitTabel']->post_url)) value="" @else value="{{$crms_array['leadconduitTabel']->post_url}}" @endif>
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
                                           @if(empty($crms_array['marketsharpmTabel']->MSM_source)) value="" @else value="{{$crms_array['marketsharpmTabel']->MSM_source}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="MSM_coy">MSM_coy</label>
                                    <input type="text" class="form-control input-campaign-crm" name="MSM_coy" id="MSM_coy" aria-describedby="MSM_coy"
                                           @if(empty($crms_array['marketsharpmTabel']->MSM_coy)) value="" @else value="{{$crms_array['marketsharpmTabel']->MSM_coy}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="MSM_formId">MSM_formId</label>
                                    <input type="text" class="form-control input-campaign-crm" name="MSM_formId" id="MSM_formId" aria-describedby="MSM_formId"
                                           @if(empty($crms_array['marketsharpmTabel']->MSM_formId)) value="" @else value="{{$crms_array['marketsharpmTabel']->MSM_formId}}" @endif>
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
                                           @if(empty($crms_array['LeadPortalTabel']->key)) value="" @else value="{{$crms_array['LeadPortalTabel']->key}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="leadconduit_url">SRC</label>
                                    <input type="text" class="form-control input-campaign-crm" name="leadPortal_SRC" id="leadPortal_SRC" aria-describedby="leadPortal_SRC"
                                           @if(empty($crms_array['LeadPortalTabel']->SRC)) value="" @else value="{{$crms_array['LeadPortalTabel']->SRC}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="leadPortal_type">Type</label>
                                    <input type="text" class="form-control input-campaign-crm" name="leadPortal_type" id="leadPortal_type" aria-describedby="leadPortal_type"
                                           @if(empty($crms_array['LeadPortalTabel']->type)) value="" @else value="{{$crms_array['LeadPortalTabel']->type}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="leadconduit_url">API Spec URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="leadPortal_api_url" id="leadPortal_api_url" aria-describedby="leadPortal_api_url"
                                           @if(empty($crms_array['LeadPortalTabel']->api_url)) value="" @else value="{{$crms_array['LeadPortalTabel']->api_url}}" @endif>
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
                                           @if(empty($crms_array['leadsPediaTrackTabel']->lp_campaign_id)) value="" @else value="{{$crms_array['leadsPediaTrackTabel']->lp_campaign_id}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="lp campaign key">lp campaign key</label>
                                    <input type="text" class="form-control input-campaign-crm" name="lp_campaign_key" id="lp_campaign_key" aria-describedby="lp_campaign_key"
                                           @if(empty($crms_array['leadsPediaTrackTabel']->lp_campaign_key)) value="" @else value="{{$crms_array['leadsPediaTrackTabel']->lp_campaign_key}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Ping Url">Ping Url</label>
                                    <input type="text" class="form-control input-campaign-crm" name="leads_pedia_track_ping_url" id="leads_pedia_track_ping_url" aria-describedby="leads_pedia_track_ping_url"
                                           @if(empty($crms_array['leadsPediaTrackTabel']->ping_url)) value="" @else value="{{$crms_array['leadsPediaTrackTabel']->ping_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Post Url">Post Url</label>
                                    <input type="text" class="form-control input-campaign-crm" name="leads_pedia_track_post_url" id="leads_pedia_track_ping_url" aria-describedby="leads_pedia_track_ping_url"
                                           @if(empty($crms_array['leadsPediaTrackTabel']->post_url)) value="" @else value="{{$crms_array['leadsPediaTrackTabel']->post_url}}" @endif>
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
                                           @if(empty($crms_array['AcculynxCrm']->api_key)) value="" @else value="{{$crms_array['AcculynxCrm']->api_key}}" @endif>
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
                                           @if(empty($crms_array['ZohoCrm']->refresh_token)) value="" @else value="{{$crms_array['ZohoCrm']->refresh_token}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="client_id">Client ID</label>
                                    <input type="text" class="form-control input-campaign-crm" name="client_id" id="client_id" aria-describedby="client_id"
                                           @if(empty($crms_array['ZohoCrm']->client_id)) value="" @else value="{{$crms_array['ZohoCrm']->client_id}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="client_secret">Client Secret</label>
                                    <input type="text" class="form-control input-campaign-crm" name="client_secret" id="client_secret" aria-describedby="client_secret"
                                           @if(empty($crms_array['ZohoCrm']->client_secret)) value="" @else value="{{$crms_array['ZohoCrm']->client_secret}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="redirect_url">Redirect URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="redirect_url" id="redirect_url" aria-describedby="redirect_url"
                                           @if(empty($crms_array['ZohoCrm']->redirect_url)) value="" @else value="{{$crms_array['ZohoCrm']->redirect_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="Lead_Source">Lead Source</label>
                                    <input type="text" class="form-control input-campaign-crm" name="Lead_Source" id="Lead_Source" aria-describedby="Lead_Source"
                                           @if(empty($crms_array['ZohoCrm']->Lead_Source)) value="" @else value="{{$crms_array['ZohoCrm']->Lead_Source}}" @endif>
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
                                           @if(empty($crms_array['HatchCrm']->dep_id)) value="" @else value="{{$crms_array['HatchCrm']->dep_id}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="HatchOrgToken">Org Token</label>
                                    <input type="text" class="form-control input-campaign-crm" name="HatchOrgToken" id="HatchOrgToken" aria-describedby="HatchOrgToken"
                                           @if(empty($crms_array['HatchCrm']->org_token)) value="" @else value="{{$crms_array['HatchCrm']->org_token}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="Hatch_URL_sub">URL Sub</label>
                                    <input type="text" class="form-control input-campaign-crm" name="Hatch_URL_sub" id="Hatch_URL_sub" aria-describedby="Hatch_URL_sub"
                                           @if(empty($crms_array['HatchCrm']->URL_sub)) value="" @else value="{{$crms_array['HatchCrm']->URL_sub}}" @endif>
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
                                           @if(empty($crms_array['salesforceCRM']->oid)) value="" @else value="{{$crms_array['salesforceCRM']->oid}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="salesforce_lead_source">Lead Source</label>
                                    <input type="text" class="form-control input-campaign-crm" name="salesforce_lead_source" id="salesforce_lead_source" aria-describedby="salesforce_lead_source"
                                           @if(empty($crms_array['salesforceCRM']->lead_source)) value="" @else value="{{$crms_array['salesforceCRM']->lead_source}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="salesforce_url">POST URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="salesforce_url" id="salesforce_url" aria-describedby="salesforce_url"
                                           @if(empty($crms_array['salesforceCRM']->url)) value="" @else value="{{$crms_array['salesforceCRM']->url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="salesforce_retURL">Redirect URL</label>
                                    <input type="text" class="form-control input-campaign-crm" name="salesforce_retURL" id="salesforce_retURL" aria-describedby="salesforce_retURL"
                                           @if(empty($crms_array['salesforceCRM']->retURL)) value="" @else value="{{$crms_array['salesforceCRM']->retURL}}" @endif>
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
                                           @if(empty($crms_array['builderPrimetable']->post_url)) value="" @else value="{{$crms_array['builderPrimetable']->post_url}}" @endif>
                                </div>

                                <div class="form-group">
                                    <label for="builderPrimeSecretKey">Secret Key</label>
                                    <input type="text" class="form-control input-campaign-crm" name="builderPrimeSecretKey" id="builderPrimeSecretKey" aria-describedby="builderPrimeSecretKey"
                                           @if(empty($crms_array['builderPrimetable']->secret_key)) value="" @else value="{{$crms_array['builderPrimetable']->secret_key}}" @endif>
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
                                           @if(empty($crms_array['zapier_crm']->post_url)) value="" @else value="{{$crms_array['zapier_crm']->post_url}}" @endif>
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
                                           @if(empty($crms_array['set_shape_crm']->post_url)) value="" @else value="{{$crms_array['set_shape_crm']->post_url}}" @endif>
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
                                           @if(empty($crms_array['set_job_nimbus_crm']->api_key)) value="" @else value="{{$crms_array['set_job_nimbus_crm']->api_key}}" @endif>
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
                                           @if(empty($crms_array['set_sunbase_crm']->url)) value="" @else value="{{$crms_array['set_sunbase_crm']->url}}" @endif>
                                </div>
                                <div class="form-group">
                                    <label for="sunbase_schema_name">Schema Name</label>
                                    <input type="text" class="form-control input-campaign-crm" name="sunbase_schema_name" id="sunbase_schema_name" aria-describedby="sunbase_schema_name"
                                           @if(empty($crms_array['set_sunbase_crm']->schema_name)) value="" @else value="{{$crms_array['set_sunbase_crm']->schema_name}}" @endif>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{--end crm design--}}

        </div>
    </div>
    @php
        $permission_users_buyer = array();
        if( !empty($campaign->permission_users) ){
            $permission_users_buyer = json_decode($campaign->permission_users, true);
        }
    @endphp
    @if( (!in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [1, 2]) && !in_array('1', $permission_users_buyer)) ||
         (in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [1, 2]) && (empty($permission_users) || in_array('7-2', $permission_users))) )
        <div class="row">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary stepy-finish pull-right UpdateCamp">Update</button>
            </div>
        </div>
    @endif
</div>
