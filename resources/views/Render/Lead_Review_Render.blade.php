@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
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
