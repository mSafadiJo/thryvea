@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered"
           data-action = "/LeadLost/fetch_data?page=">
        <thead>
        <tr>
            <th>ID</th>
            <th>Lead Name</th>
            <th>State</th>
            <th>Service</th>
            <th>Source</th>
            <th>TS</th>
            <th>Status</th>
            <th>Trusted Form URL</th>
            <th>Date</th>
            @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users)
                || in_array('8-11', $permission_users) || in_array('8-2', $permission_users) )
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
                <td> {{$ListLead->service_campaign_name}} </td>
                <td> {{$ListLead->lead_source_text}} @if( $ListLead->converted == 1 )> R @endif </td>
                <td> {{$ListLead->google_ts}} </td>

                @if ($ListLead->is_duplicate_lead == 1)
                    <td>Duplicated</td>
                @elseif ($ListLead->status == 2)
                    <td>Blocked</td>
                @elseif ($ListLead->status == 3)
                    <td>ReAffiliate</td>
                @elseif ($ListLead->status == 4)
                    @if( $ListLead->flag == 1 )
                        <td>Flag</td>
                    @elseif( $ListLead->flag == 2 )
                        <td>Flag (International)</td>
                    @else
                        <td>Flag</td>
                    @endif
                @else
                    <td>Not Match</td>
                @endif

                <td>
                    @if (!empty($ListLead->trusted_form))
                        <a href="{{$ListLead->trusted_form}}" target="_blank">Trusted Form</a>
                    @endif
                </td>
                <td> {{$ListLead->created_at}} </td>
                @if (empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users)
                || in_array('8-11', $permission_users) || in_array('8-2', $permission_users))
                    <td>
                        @if (empty($permission_users) || in_array('8-10', $permission_users))
                            <a href="/Admin/lead/{{$ListLead->lead_id}}/details/Lost" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                            </a>
                        @endif
                        @if (empty($permission_users) || in_array('8-2', $permission_users))
                            @if ($ListLead->is_duplicate_lead == 0)
                                <a href="/Admin/EditLead/{{$ListLead->lead_id}}/CustomerLead"  class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Lead" data-trigger="hover" data-animation="false">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                            @endif
                        @endif
                        @if (empty($permission_users) || in_array('8-3', $permission_users))
                            <a href="/Admin/DeleteLead/{{$ListLead->lead_id}}" onclick="return confirm('Are you sure you want to delete this item?');" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Lead" data-trigger="hover" data-animation="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if (empty($permission_users) || in_array('8-11', $permission_users))
                            @if ($ListLead->is_duplicate_lead == 0)
                                <a href="/Admin/PushLead/{{$ListLead->lead_id}}" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Push Lead" data-trigger="hover" data-animation="false">
                                    Push Lead
                                </a>
                            @endif
                        @endif
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
