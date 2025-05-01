@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered"
           data-action = "/LeadArchive/fetch_data?page=">
        <thead>
        <tr>
            <th>ID</th>
            <th>Lead Name</th>
            <th>State</th>
            <th>Service</th>
            <th>Source</th>
            <th>TS</th>
            <th>status</th>
            <th>Trusted Form URL</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($ListOfLeads as $ListLead)
            <tr>
                <td> {{$ListLead->lead_id}} </td>
                <td> {{$ListLead->lead_fname}} {{$ListLead->lead_lname}} </td>
                <td> {{$ListLead->state_code}} </td>
                <td> {{$ListLead->service_campaign_name}} </td>
                <td> {{$ListLead->lead_source_text}} @if( $ListLead->converted == 1 )  > R @endif</td>
                <td> {{$ListLead->google_ts}} </td>

                @if( $ListLead->is_duplicate_lead == 1 )
                    <td>Duplicated</td>
                @else
                    <td>Not Match</td>
                @endif

                <td>
                    @if( !empty($ListLead->trusted_form) )
                        <a href="{{$ListLead->trusted_form}}" target="_blank">Trusted Form</a>
                    @endif
                </td>
                <td> {{$ListLead->created_at}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    Showing {{($ListOfLeads->currentPage()-1)* $ListOfLeads->perPage()+($ListOfLeads->total() ? 1:0)}} to {{($ListOfLeads->currentPage()-1)*$ListOfLeads->perPage()+count($ListOfLeads)}}  of  {{$ListOfLeads->total()}}  Results
    {!! $ListOfLeads->links() !!}
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
</div>
