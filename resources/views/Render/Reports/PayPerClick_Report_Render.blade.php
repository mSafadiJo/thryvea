@php
    $permission_users = array();
    if( !empty(Auth::user()->permission_users) ){
        $permission_users = json_decode(Auth::user()->permission_users, true);
    }
@endphp
<div class="table-responsive">
    <table id="pagination-table" class="table table-striped table-bordered" data-action="/Report/PayPerClick_fetch_data?page=">
        <thead>
        <tr>
            <th>Campaign ID</th>
            <th>Campaign Name</th>
            <th>Number of Click</th>
            <th>Number of Conversions</th>
            <th>Total Cost</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($campaigns as $campaign)
            <tr>
                <td>{{ $campaign->campaign_id }}</td>
                <td>{{ $campaign->campaign_name }}</td>
                <td>{{ (!empty($total_click_leads[$campaign->campaign_id]) ? $total_click_leads[$campaign->campaign_id] : 0) }}</td>
                <td>{{ (!empty($total_conversions_leads[$campaign->campaign_id]) ? $total_conversions_leads[$campaign->campaign_id] : 0) }}</td>
                <td>${{ (!empty($sum_click_leads[$campaign->campaign_id]) ? $sum_click_leads[$campaign->campaign_id] : 0) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    Showing {{($campaigns->currentPage()-1)* $campaigns->perPage()+($campaigns->total() ? 1:0)}}
    to {{($campaigns->currentPage()-1)*$campaigns->perPage()+count($campaigns)}}
    of {{$campaigns->total()}} Results
    {!! $campaigns->links() !!}
    <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
</div>
