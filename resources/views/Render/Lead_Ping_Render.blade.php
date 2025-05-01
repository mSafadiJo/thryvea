@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered"
           data-action = "/LeadPing/fetch_data?page=">
        <thead>
        <tr>
            <th>ID</th>
            <th>Vendor ID</th>
            <th>State</th>
            <th>Seller Name</th>
            <th>Campaign Name</th>
            <th>Service</th>
            <th>Status</th>
            <th>Type</th>
            <th>Price</th>
            <th>Accepted By</th>
            <th>Trusted Form URL</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($ListOfLeads as $ListLead)
            <tr>
                <td> {{$ListLead->lead_id}} </td>
                <td> {{$ListLead->vendor_id}}</td>
                <td> {{$ListLead->state_code}} </td>

                <td> {{$ListLead->user_business_name}} </td>
                <td> {{$ListLead->campaign_name}}</td>
                <td> {{$ListLead->service_campaign_name}} </td>

                <td> {{$ListLead->status}} </td>
                <td> {{$ListLead->lead_bid_type}}</td>
                <td> {{$ListLead->price}} </td>

                <td> {{$ListLead->buyer_camp_names}} </td>
                <td> @if( !empty($ListLead->trusted_form) )
                        <a href='{{$ListLead->trusted_form}}' target='_blank'>Trusted Form</a>
                    @endif
                </td>
                <td> {{$ListLead->created_at}} </td>

                @if( empty($permission_users) || in_array('8-10', $permission_users) || in_array('8-3', $permission_users)
                || in_array('8-11', $permission_users) )
                    @if( empty($permission_users) || in_array('8-10', $permission_users) )
                        <td>
                            <a href="/Admin/lead/{{$ListLead->lead_id}}/details/PING" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                            </a>
                        </td>
                    @endif
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
