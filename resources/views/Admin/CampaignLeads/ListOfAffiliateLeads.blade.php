@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Leads</h4>
            </div>
        </div>
    </div>
    @php
        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
    @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('export_lead_data') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="service_id">Service Name</label>
                                        <select class="select2 form-control select2-multiple" multiple name="service_id[]" id="service_id" data-placeholder="Choose ...">
                                            <optgroup label="Service">
                                                @if( !empty( $data['services'] ) )
                                                    @foreach( $data['services'] as $service )
                                                        <option value="{{ $service->service_campaign_id }}">{{ $service->service_campaign_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="buyer_id">State Name</label>
                                        <select class="select2 form-control select2-multiple" name="states[]" id="statenamelead" multiple="multiple" data-placeholder="Choose ...">
                                            <optgroup label="States">
                                                @if( !empty($data['states']) )
                                                    @foreach($data['states'] as $state)
                                                        <option value="{{ $state->state_id }}">{{ $state->state_code }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="environments">Environments</label>
                                        <select class="select2 form-control" name="environments" id="environments" data-placeholder="Choose ...">
                                            <optgroup label="Environments">
                                                <option value="1" selected>All</option>
                                                <option value="2">Sold</option>
                                                <option value="3">UnSold</option>
                                                <option value="5">Test Lead</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="buyer_id">Buyers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="buyer_id[]" id="buyer_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['buyers'] ) )
                                                    @foreach( $data['buyers'] as $buyer )
                                                        <option value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="buyer_id">Sellers</label>
                                        <select class="select2 form-control select2-multiple" multiple name="seller_id[]" id="seller_id" data-placeholder="Choose ...">
                                            <optgroup label="Buyer Name">
                                                @if( !empty( $data['sellers'] ) )
                                                    @foreach( $data['sellers'] as $buyer )
                                                        <option value="{{ $buyer->id }}">{{ $buyer->user_business_name }}</option>
                                                    @endforeach
                                                @endif
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">Counties Filter</label>--}}
{{--                                        <input id="counties-reports" class="form-control" style="width:100%;" placeholder="type a counties, scroll for more results" name="county_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">Cities Filter</label>--}}
{{--                                        <input id="cities-reports" class="form-control" style="width:100%;" placeholder="type a cities, scroll for more results" name="city_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">ZipCodes Filter</label>--}}
{{--                                        <input id="zipcodes-reports" class="form-control" style="width:100%;" placeholder="type a zipcodes, scroll for more results" name="zipcode_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker1" name="start_date" placeholder="From Date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" autocomplete="false" class="form-control start_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" id="datepicker2" name="end_date" placeholder="To Date" value="{{ date('Y-m-d') }}" autocomplete="false" class="form-control end_date_pagination"/>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="hidden" name="type" value="6">
                                                <button type="submit" class="btn btn-primary col-lg-12">Export</button>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div style="margin-bottom: 5%;"></div>
                <h4 class="header-title">List Of Affiliate Leads</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search" style="width: 22%;float:right;margin-bottom:1%;"/>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div id="table_data">
                            <div class="table-responsive">
                                <table id="pagination-table" class="table table-striped table-bordered"
                                       data-action = "/LeadAffiliate/fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Lead Name</th>
                                        <th>State</th>
                                        <th>Service</th>
                                        <th>Source</th>
                                        <th>LeadFrom</th>
                                        <th>SoldTo</th>
                                        <th>Status</th>
                                        <th>type</th>
                                        <th>Purchasing Price</th>
                                        <th>Selling Price</th>
                                        <th>QA status</th>
                                        <th>Created At</th>
                                        @if (empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-11', $permission_users))
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ListOfLeads as $val)
                                        <tr>
                                            <td>{{$val->lead_id}}</td>
                                            <td>{{ $val->lead_fname }} {{$val->lead_lname}} <br> {{$val->lead_phone_number}} </td>
                                            <td>{{$val->state_code}}</td>
                                            <td>{{$val->service_campaign_name}}</td>
                                            <td>{{$val->lead_source_text}}</td>
                                            <td>{{$val->seller_business_name}}</td>
                                            <td>{{$val->buyerUser}}</td>

                                            @if ($val->status == 1)
                                                <td>Deleted</td>
                                            @elseif ($val->status == 2)
                                                <td>Blocked</td>
                                            @else
                                                @if ($val->is_duplicate_lead == 1)
                                                    <td>Duplicated</td>
                                                @else
                                                    @if (!empty($val->bid_type) || $val->bid_type != 0)
                                                        @php
                                                            $val->is_returned_concat = str_replace("1","Returned",$val->is_returned_concat);
                                                            $val->is_returned_concat = str_replace("0","Sold",$val->is_returned_concat);
                                                        @endphp
                                                        <td> {{$val->is_returned_concat}}</td>
                                                    @else
                                                        @php
                                                            $response_data_status = (!empty($val->response_data) ? $val->response_data : "Not Match");
                                                        @endphp
                                                        <td>{{$response_data_status}}</td>
                                                    @endif
                                                @endif
                                            @endif

                                            <td>{{$val->bid_type}}</td>
                                            <td> {{number_format($val->ping_price, 2, '.', ',')}}</td>
                                            <td> {{number_format($val->sum_bid, 2, '.', ',')}} </td>
                                            <td>
                                                <select name="QA_status" class="form-control" style="height: unset;width: 80%;" id="QA_LeadStatus_table_Ajax_changing-{{$val->lead_id}}"
                                                        onchange="return QA_LeadStatus_table_Ajax_changing('{{$val->lead_id}}');"
                                                        @if( !(empty($permission_users) || in_array('8-22', $permission_users)) ) disabled @endif >
                                                    @if(!empty($QA_status))
                                                        <option value="">select status</option>
                                                        @foreach($QA_status as $QAstatus)
                                                            <option value="{{ $QAstatus }}" @if( $QAstatus == $val->QA_status ) selected @endif>{{ $QAstatus }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>{{$val->created_at}}</td>
                                            @if (empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-11', $permission_users))
                                                <td>
                                                    @if (empty($permission_users) || in_array('8-10', $permission_users))
                                                        <a href="/Admin/lead/{{$val->lead_id}}/details/Lost" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                                            <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                                        </a>
                                                    @endif
                                                    @if (empty($permission_users) || in_array('8-11', $permission_users))
                                                        <a href="/Admin/PushLead/{{$val->lead_id}}" class="on-default edit-row" style="display: block;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Push Lead" data-trigger="hover" data-animation="false">
                                                            Push Lead
                                                        </a>
                                                    @endif
                                                    @php
                                                        $city = explode('=>', $val->city_name);
                                                        $city = (!empty($city[0]) ? trim($city[0]) : "");
                                                    @endphp
                                                    <a href="https://earth.google.com/web/search/{{ $val->lead_address }},{{ $city }},{{ $val->state_code }},{{ $val->zip_code_list }}, USA" target="_blank" style="cursor: pointer;" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="Show Google Maps Location" data-original-title="Show Google Maps Location" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-share" style="color: #0b0b0b"></i>
                                                    </a>
                                                    <a href="https://www.zillow.com/homes/{{ $val->lead_address }},{{ $city }},{{ $val->state_code }},{{ $val->zip_code_list }}_rb/" target="_blank" style="cursor: pointer;" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="Show Location On Zillow" data-original-title="Show Location On Zillow" data-trigger="hover" data-animation="false">
                                                        <i class="fa fa-share"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $ListOfLeads->links() !!}
                                Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}
                                {{--Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()+($ListOfLeads->total() ? 1:0)}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}  of  {{$ListOfLeads->total()}}  Results--}}
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Of Page 1-->
    @include('include.include_reports')
@endsection
