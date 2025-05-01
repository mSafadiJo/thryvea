@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
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
