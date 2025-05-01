@extends('layouts.adminapp')

@section('content')
    <!-- Page 1-->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box page-title-box">
                <h4 class="header-title">Leads Review</h4>
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
                <h4 class="header-title">List Of Leads Revies</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ route('export_lead_data') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-6">
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
                                <div class="col-lg-6">
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

{{--                                <div class="col-lg-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">Counties Filter</label>--}}
{{--                                        <input id="counties-reports" class="form-control" style="width:100%;" placeholder="type a counties, scroll for more results" name="county_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">Cities Filter</label>--}}
{{--                                        <input id="cities-reports" class="form-control" style="width:100%;" placeholder="type a cities, scroll for more results" name="city_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="service_id">ZipCodes Filter</label>--}}
{{--                                        <input id="zipcodes-reports" class="form-control" style="width:100%;" placeholder="type a zipcodes, scroll for more results" name="zipcode_id[]" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="buyer_id">Traffic Source (TS)</label>--}}
{{--                                        <select class="select2 form-control select2-multiple" name="traffic_source[]" id="traffic_source" multiple="multiple" data-placeholder="Choose ...">--}}
{{--                                            <optgroup label="Traffic Source (TS)">--}}
{{--                                                @if( !empty($data['list_of_ts']) )--}}
{{--                                                    @foreach($data['list_of_ts'] as $item)--}}
{{--                                                        <option value="{{ $item }}">{{ $item }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                @endif--}}
{{--                                            </optgroup>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="buyer_id">Ad Group (G)</label>--}}
{{--                                        <select class="select2 form-control select2-multiple" name="google_g[]" id="google_g" multiple="multiple" data-placeholder="Choose ...">--}}
{{--                                            <optgroup label="Ad Group (G)">--}}
{{--                                                @if( !empty($data['list_of_g']) )--}}
{{--                                                    @foreach($data['list_of_g'] as $item)--}}
{{--                                                        <option value="{{ $item }}">{{ $item }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                @endif--}}
{{--                                            </optgroup>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="buyer_id">Campaign (C)</label>--}}
{{--                                        <select class="select2 form-control select2-multiple" name="google_c[]" id="google_c" multiple="multiple" data-placeholder="Choose ...">--}}
{{--                                            <optgroup label="Campaign (C)">--}}
{{--                                                @if( !empty($data['list_of_c']) )--}}
{{--                                                    @foreach($data['list_of_c'] as $item)--}}
{{--                                                        <option value="{{ $item }}">{{ $item }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                @endif--}}
{{--                                            </optgroup>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-lg-3">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="buyer_id">Keyword (K)</label>--}}
{{--                                        <select class="select2 form-control select2-multiple" name="google_k[]" id="google_k" multiple="multiple" data-placeholder="Choose ...">--}}
{{--                                            <optgroup label="Keyword (K)">--}}
{{--                                                @if( !empty($data['list_of_k']) )--}}
{{--                                                    @foreach($data['list_of_k'] as $item)--}}
{{--                                                        <option value="{{ $item }}">{{ $item }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                @endif--}}
{{--                                            </optgroup>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <input type="text" name="start_date" id="form_datetime"  value="{{ date('Y-m-d', strtotime('-1 days')) }} 00:00" readonly class="form-control form_datetime start_date_pagination" placeholder="From Date">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" name="end_date" id="to_datetime" value="{{ date('Y-m-d') }} 23:59" readonly class="form-control form_datetime end_date_pagination" placeholder="End Date">
                                </div>
                                <div class="col-lg-4">
                                    <div class="row">
                                        @if( empty($permission_users) || in_array('8-4', $permission_users) )
                                            <div class="col-lg-6">
                                                <button type="button" class="btn btn-primary col-lg-12" id="filterLeadTables">Search</button>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="hidden" name="type" value="7">
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
                                       data-action = "/LeadReview/fetch_data?page=">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Lead Name</th>
                                        <th>State</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>TS</th>
                                        <th>Type</th>
                                        <th>Services</th>
                                        <th>Date</th>
                                        @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users) || in_array('8-2', $permission_users) )
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ListOfLeads as $ListLead)
                                        <tr>
                                            <td> {{$ListLead->lead_id}} </td>
                                            <td> {{$ListLead->lead_fname}} {{$ListLead->lead_lname}} </td>
                                            <td> {{$ListLead->state_code}} </td>
                                            <td> {{$ListLead->lead_source_text}} </td>
                                            @if( $ListLead->is_completed == 1 )
                                                <td>complete</td>
                                            @else
                                                <td>Not complete</td>
                                            @endif
                                            <td> {{$ListLead->google_ts}} </td>
                                            <td>
                                                @if( $ListLead->is_multi_service == 1 )
                                                    Multi Services
                                                @else
                                                    Single Service
                                                @endif
                                            </td>

                                            <td>
                                                @if( $ListLead->is_multi_service == 1 )
                                                    @php $lead_type_service_id = json_decode($ListLead->lead_type_service_id, true) @endphp
                                                    @if( !empty($lead_type_service_id) )
                                                        @foreach ($lead_type_service_id as $key => $val)
                                                            @php $service_name = App\Service_Campaign::where('service_campaign_id',
                                                            $val)->first(['service_campaign_name'])['service_campaign_name'] @endphp
                                                            @if( $key !== 0 )
                                                                ,
                                                            @endif

                                                            {{$service_name}}
                                                        @endforeach
                                                    @endif
                                                @else
                                                    @php $service_name = App\Service_Campaign::where('service_campaign_id',
                                                    $ListLead->lead_type_service_id)->first(['service_campaign_name'])['service_campaign_name']@endphp
                                                    {{$service_name}}
                                                @endif
                                            </td>
                                            <td> {{date('Y/m/d H:i A', strtotime($ListLead->created_at))}} </td>
                                            @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users) || in_array('8-2', $permission_users) )
                                                <td>
                                                    @if( empty($permission_users) || in_array('8-10', $permission_users) )
                                                        <a href="/Admin/lead/{{$ListLead->lead_id}}/details/review" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                                            <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                                        </a>
                                                    @endif
                                                    @if (empty($permission_users) || in_array('8-2', $permission_users))
                                                        <a href="/Admin/EditLead/{{$ListLead->lead_id}}/review"  class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Lead" data-trigger="hover" data-animation="false">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                    @if( empty($permission_users) || in_array('8-3', $permission_users) )
                                                        <a href="/Admin/DeleteLead/{{$ListLead->lead_id}}/review" onclick="return confirm('Are you sure you want to delete this item?');" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Lead" data-trigger="hover" data-animation="false">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()+($ListOfLeads->total() ? 1:0)}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}  of  {{$ListOfLeads->total()}}  Results
                                {!! $ListOfLeads->links() !!}
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
