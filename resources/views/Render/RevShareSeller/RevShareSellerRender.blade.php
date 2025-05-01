@if(!empty($list_of_leads))
    @foreach ($list_of_leads as $lead)
        <?php
            $sum_bid_leads = (!empty($lead->sum_bid_leads) ? $lead->sum_bid_leads : 0);
        ?>
        <tr>
            <td>{{ $lead->lead_id }}</td>
            <td>{{ $lead->lead_fname . " " . $lead->lead_lname }}</td>
            <td>{{ $sum_bid_leads }}</td>
            <td>{{ ($sum_bid_leads * Auth::user()->profit_percentage) }}</td>
            <td>{{ $lead->created_at }}</td>
        </tr>
    @endforeach
<tr>
    <td colspan="3">
        Showing {{($list_of_leads->currentPage()-1)* $list_of_leads->perPage()}} to {{($list_of_leads->currentPage()-1)*$list_of_leads->perPage()+count($list_of_leads)}}
        {{--Showing {{($campaignSoldLeads->currentPage()-1)* $campaignSoldLeads->perPage()+($campaignSoldLeads->total() ? 1:0)}} to {{($campaignSoldLeads->currentPage()-1)*$campaignSoldLeads->perPage()+count($campaignSoldLeads)}}  of  {{$campaignSoldLeads->total()}}  Results--}}
    </td>
    <td colspan="2" align="right">
        {!! $list_of_leads->links() !!}
    </td>
</tr>
@endif
