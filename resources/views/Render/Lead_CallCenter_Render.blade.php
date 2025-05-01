@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered"
           data-action = "/LeadCallCenter/fetch_data?page=">
        <thead>
        <tr>
            <th>Unique ID</th>
            <th>Lead ID</th>
            <th>Lead Name</th>
            <th>State</th>
            <th>SoldTo</th>
            <th>Campaign Name</th>
            <th>Service</th>
            <th>Agent Name</th>
            <th>type</th>
            <th>Bid</th>
            <th>Trusted Form URL</th>
            <th>Date</th>
            @if( empty($permission_users) || in_array('8-10', $permission_users) )
                <th>Action</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($ListOfLeads as $campaignlead)
            <tr>
                <td> {{$campaignlead->campaigns_leads_users_id}} </td>
                <td> {{$campaignlead->lead_id}} </td>
                <td> {{$campaignlead->lead_fname}} {{$campaignlead->lead_lname}} </td>
                <td> {{$campaignlead->state_code}} </td>
                <td> {{$campaignlead->user_business_name}} </td>
                <td> {{$campaignlead->campaign_name}} </td>
                <td> {{$campaignlead->service_campaign_name}} </td>
                <td> {{$campaignlead->agent_name}} </td>
                <td> {{$campaignlead->campaigns_leads_users_type_bid}} </td>
                <td> {{$campaignlead->campaigns_leads_users_bid}} </td>
                <td>
                    @if (!empty($campaignlead->trusted_form))
                        <a href="{{$campaignlead->trusted_form}}" target="_blank">Trusted Form</a>
                    @endif
                </td>
                <td> {{$campaignlead->created_at_lead}} </td>
                @if (empty($permission_users) || in_array('8-10', $permission_users) )
                    <td>
                        <a href="/Admin/lead/{{$campaignlead->campaigns_leads_users_id}}/details/Received" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                            <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                        </a>
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
