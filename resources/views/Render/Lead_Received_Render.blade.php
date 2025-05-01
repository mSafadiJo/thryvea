@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
@if(!empty($campaignSoldLeads))
    @foreach ($campaignSoldLeads as $campaignlead)
        <tr>
            <td> {{$campaignlead->campaigns_leads_users_id}} </td>
            <td> {{$campaignlead->lead_id}} </td>
            <td> {{$campaignlead->lead_fname}} {{$campaignlead->lead_lname}} </td>
            <td> {{$campaignlead->state_code}} </td>
            <td> {{$campaignlead->user_business_name}} </td>
            <td> {{$campaignlead->campaign_name}} </td>
            <td> {{$campaignlead->service_campaign_name}} </td>
            <td> {{$campaignlead->lead_source_text}}
                @if( $campaignlead->converted == 1 )
                    > R
                @endif
            </td>
            <td> {{$campaignlead->google_ts}} </td>
            <td> {{$campaignlead->campaigns_leads_users_type_bid}} </td>
            <td> {{$campaignlead->campaigns_leads_users_bid}} </td>
            <td> {{ (!empty($campaignlead->campaigns_leads_users_note) ?  $campaignlead->campaigns_leads_users_note : "In Progress") }} </td>
            <td>
                @if (!empty($campaignlead->trusted_form))
                    <a href="{{$campaignlead->trusted_form}}" target="_blank">Trusted Form</a>
                @endif
            </td>
            <td> {{$campaignlead->created_at_lead}} </td>
            @if (empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users) || in_array('8-11', $permission_users))
                <td>
                    @if (empty($permission_users) || in_array('8-10', $permission_users))
                        <a href="/Admin/lead/{{$campaignlead->campaigns_leads_users_id}}/details/Received" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                            <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                        </a>
                    @endif
                    @if (empty($permission_users) || in_array('8-3', $permission_users))
                        @if( Auth::user()->role_id == 1 )
                            <a href="/Admin/DeleteSoldLead/{{$campaignlead->campaigns_leads_users_id}}" onclick="return confirm('Are you sure you want to delete this item?');" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Lead" data-trigger="hover" data-animation="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        @endif
                    @endif
                    @if (empty($permission_users) || in_array('8-11', $permission_users))
                        <a href="/Admin/PushLead/{{$campaignlead->lead_id}}" class="on-default edit-row" style="display: block;" data-toggle="tooltip" data-placement="top" title="" data-original-title="Push Lead" data-trigger="hover" data-animation="false">
                            Push Lead
                        </a>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
    <tr>
        <td colspan="7">
            Showing {{($campaignSoldLeads->currentPage()-1)* $campaignSoldLeads->perPage()}} to {{($campaignSoldLeads->currentPage()-1)*$campaignSoldLeads->perPage()+count($campaignSoldLeads)}}
            {{--Showing {{($campaignSoldLeads->currentPage()-1)* $campaignSoldLeads->perPage()+($campaignSoldLeads->total() ? 1:0)}} to {{($campaignSoldLeads->currentPage()-1)*$campaignSoldLeads->perPage()+count($campaignSoldLeads)}}  of  {{$campaignSoldLeads->total()}}  Results--}}
        </td>
        <td colspan="8" align="right">
            {!! $campaignSoldLeads->links() !!}
        </td>
    </tr>
@endif

