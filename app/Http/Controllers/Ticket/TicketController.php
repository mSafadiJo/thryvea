<?php

namespace App\Http\Controllers\Ticket;

use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Rap2hpoutre\FastExcel\FastExcel;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function listOfUserTicket($user_id){
        $from_date = date("Y-m-d", strtotime("-3 month", strtotime(date('Y-m') . '-01'))) . ' 00:00:00';
        $to_date = date('Y-m-t') . ' 23:59:59';

        //Return Ticket Issues for 2 month or not closed
        $ticket_issues = Ticket::where('ticket_type', 1)
            ->where('user_id', $user_id)
            ->where(function ($query) use($from_date, $to_date)  {
                $query->whereBetween('created_at', [$from_date, $to_date])
                    ->orWhere('ticket_status', '<>', 3);
            })
            ->orderBy('created_at', 'desc')
            ->get()->all();

        //Return Ticket Return Lead for 2 month or not closed
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('tickets.ticket_type', 2)
            ->where('tickets.user_id', $user_id)
            ->where(function ($query) use($from_date, $to_date)  {
                $query->whereBetween('tickets.created_at', [$from_date, $to_date])
                    ->orWhere('tickets.ticket_status', '<>', 3);
            })
            ->orderBy('tickets.created_at', 'desc')
            ->get([
                'reason_lead_returned.reason_lead_returned_name', 'tickets.*',
                'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_phone_number'
            ]);

        //Return Buyers Name
        $nameuser = User::where('id', $user_id)->first(['username']);

        return view('Admin.Ticket.index')
            ->with('ticket_issues', $ticket_issues)
            ->with('ticket_returnlead', $ticket_returnlead)
            ->with('nameuser', $nameuser->username);
    }


    public function ShowReturnTicket(){

       // $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
       //  $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';

        //Return Ticket Return Lead for 2 month or not closed
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id');
           // ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            //->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            //->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
           // ->join('users', 'tickets.user_id', '=', 'users.id');
           // ->where('tickets.ticket_type', 2);
           // ->whereBetween('tickets.created_at', [$start_date, $end_date])

        if (!empty($ticket_status_id)) {
            $ticket_returnlead->whereIn('tickets.ticket_status', $ticket_status_id);
        }

        $ticket_returnlead = $ticket_returnlead->orderBy('tickets.created_at', 'desc')
            ->get();


echo "<pre>";
print_r($ticket_returnlead); die();

        return view('Admin.Ticket.TicketReturnLead');
    }

    public function ShowIssueTicket(){
        return view('Admin.Ticket.TicketIssues');
    }


    public function ShowReturnTicketAjax( Request $request){

        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        $ticket_status_id = $request->ticket_status_id;

        //Return Ticket Return Lead for 2 month or not closed
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->where('tickets.ticket_type', 2)
            ->whereBetween('tickets.created_at', [$start_date, $end_date]);

        if (!empty($ticket_status_id)) {
            $ticket_returnlead->whereIn('tickets.ticket_status', $ticket_status_id);
        }

        $ticket_returnlead = $ticket_returnlead->orderBy('tickets.created_at', 'desc')
            ->get([
                'reason_lead_returned.reason_lead_returned_name', 'tickets.*', 'campaigns_leads_users.date', 'service__campaigns.service_campaign_name',
                'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_phone_number','users.user_business_name'
            ]);

        $dataJason = '';
        $dataJason .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';

        $dataJason .= 'id="datatable"';
        $dataJason .= '><thead>
                                <tr>
                                    <th>#</th>
                                    <th>Buyer Name</th>
                                    <th>Lead ID</th>
                                    <th>Lead Name</th>
                                    <th>Service</th>
                                    <th>Lead Phone Number</th>
                                    <th>Reason</th>
                                    <th>Ticket Message</th>
                                    <th>Status</th>
                                    <th>Sold Date</th>
                                    <th>Return Date</th>
                                </tr>
                            </thead>
                            <tbody>';

        if( !empty($ticket_returnlead) ){
            foreach ( $ticket_returnlead as $returnlead ){
                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $returnlead->ticket_id . "</td>";
                $dataJason .= "<td>" . $returnlead->user_business_name . "</td>";
                $dataJason .= "<td>" . $returnlead->campaigns_leads_users_id . "</td>";
                $dataJason .= "<td>" . $returnlead->lead_fname . " " . $returnlead->lead_lname . "</td>";
                $dataJason .= "<td>" . $returnlead->service_campaign_name . "</td>";
                $dataJason .= "<td>" .$returnlead->lead_phone_number . "</td>";
                $dataJason .= "<td>" .$returnlead->reason_lead_returned_name. "</td>";
                $dataJason .= "<td>" .$returnlead->ticket_message. "</td>";
                $dataJason .= "<td>";
                $dataJason .= '<select name="ticket_status" class="form-control" style="height: unset;width: 80%;" id="Refundticket_status_table_Ajax_changing-'. $returnlead->ticket_id . '"
                                    onchange="return Refundticket_status_table_Ajax_changing(\'' . $returnlead->ticket_id . '\')"';
                if( $returnlead->ticket_status == 3 || $returnlead->ticket_status == 4 ){
                    $dataJason .= 'disabled ';
                }
                $dataJason .= '>';

                $dataJason .= '<option value="1" ';
                if( $returnlead->ticket_status == 1 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Not Started</option>';


                $dataJason .= '<option value="5" ';
                if( $returnlead->ticket_status == 5 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>In Progress</option>';

                $dataJason .= '<option value="2" ';
                if( $returnlead->ticket_status == 2 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Open</option>';

                $dataJason .= '<option value="3" ';
                if( $returnlead->ticket_status == 3 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Close</option>';

                $dataJason .= '<option value="4" ';
                if( $returnlead->ticket_status == 4 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Reject</option>';

                $dataJason .= '</select>';
                $dataJason .= '</td>';
                $dataJason .= "<td>" . date('Y/m/d', strtotime($returnlead->date)) . "</td>";
                $dataJason .= "<td>" . date('Y/m/d', strtotime($returnlead->created_at)) . "</td>";
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '</tbody>
                            </table>';

        return $dataJason;

    }

    public function returnTicketsExport (Request $request){
        $start_date = date('Y-m-d', strtotime($request->StartDate)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->EndDate)) . ' 23:59:59';
        $ticket_status_id = $request->ticket_status_id;

        //Return Ticket Return Lead for 2 month or not closed
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->where('tickets.ticket_type', 2)
            ->whereBetween('tickets.created_at', [$start_date, $end_date]);

        if (!empty($ticket_status_id)) {
            $ticket_returnlead->whereIn('tickets.ticket_status', $ticket_status_id);
        }

        $ticket_returnlead = $ticket_returnlead->orderBy('tickets.created_at', 'desc')
            ->get([
                'reason_lead_returned.reason_lead_returned_name', 'tickets.*', 'campaigns_leads_users.date', 'service__campaigns.service_campaign_name',
                'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_phone_number','users.user_business_name'
            ]);

        return (new FastExcel($ticket_returnlead))->download('leads.csv', function ($returnlead) {
            $statusTicket = "";
            switch ($returnlead->ticket_status){
                case 1:
                    $statusTicket = "Not Started";
                    break;
                case 2:
                    $statusTicket = "Open";
                    break;
                case 3:
                    $statusTicket = "Close";
                    break;
                case 4:
                    $statusTicket = "Reject";
                    break;
                case 5:
                    $statusTicket = "In Progress";
                    break;
            }
            return [
                "ID" => $returnlead->ticket_id,
                "Buyer Name" => $returnlead->user_business_name,
                "Lead ID" => $returnlead->campaigns_leads_users_id,
                "Lead Name" => $returnlead->lead_fname . " " . $returnlead->lead_lname,
                "Service" => $returnlead->service_campaign_name,
                "Lead Phone Number" => $returnlead->lead_phone_number,
                "Reason" => $returnlead->reason_lead_returned_name,
                "Ticket Message" => $returnlead->ticket_message,
                "Status" => $statusTicket,
                "Sold Date" => date('Y/m/d', strtotime($returnlead->date)),
                "Return Date" => date('Y/m/d', strtotime($returnlead->created_at)),
            ];
        });


    }

    public function ShowIssueTicketAjax(Request $request){

        $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        $ticket_status_id = $request->ticket_status_id;

        //Return Ticket Issues for 2 month or not closed
        $ticket_issues = Ticket::where('ticket_type', 1)
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->whereBetween('tickets.created_at', [$start_date, $end_date]);

        if (!empty($ticket_status_id)) {
            $ticket_issues->whereIn('tickets.ticket_status', $ticket_status_id);
        }

        $ticket_issues = $ticket_issues->orderBy('tickets.created_at', 'desc')->get();

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '';
        $dataJason .= '<table class="table table-striped table-bordered" cellspacing="0" width="100%"';
        if( empty($permission_users) || in_array('3-4', $permission_users) ){
            $dataJason .= 'id="datatable-buttons"';
        } else {
            $dataJason .= 'id="datatable"';
        }
        $dataJason .= '><thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>username</th>
                                <th>Ticket Message</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                    </thead>
                    <tbody>';

        if( !empty($ticket_issues) ){
            foreach ( $ticket_issues as $issues ){
                $dataJason .= "<tr>";
                $dataJason .= "<td>" . $issues->ticket_id . "</td>";
                $dataJason .= "<td>Issue</td>";
                $dataJason .= "<td>" . $issues->user_business_name . "</td>";
                $dataJason .= "<td>" .$issues->ticket_message. "</td>";
                $dataJason .= "<td>";
                $dataJason .= '<select name="ticket_status" class="form-control" style="height: unset;width: 80%;" id="ticket_issues_status_table_Ajax_changing-'. $issues->ticket_id . '"
                                    onchange="return ticket_issues_status_table_Ajax_changing(\'' . $issues->ticket_id . '\')"';
                if( $issues->ticket_status == 3 || $issues->ticket_status == 4 ){
                    $dataJason .= 'disabled';
                }
                $dataJason .= '>';

                $dataJason .= '<option value="1" ';
                if( $issues->ticket_status == 1 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Not Started</option>';

                $dataJason .= '<option value="5" ';
                if( $issues->ticket_status == 5 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>In Progress</option>';

                $dataJason .= '<option value="2" ';
                if( $issues->ticket_status == 2 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Open</option>';

                $dataJason .= '<option value="3" ';
                if( $issues->ticket_status == 3 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Accepted/Close</option>';

                $dataJason .= '<option value="4" ';
                if( $issues->ticket_status == 4 ) {
                    $dataJason .= 'selected';
                }
                $dataJason .= '>Reject</option>';

                $dataJason .= '</select>';
                $dataJason .= '</td>';
                $dataJason .= "<td>" . date('Y/m/d', strtotime($issues->created_at)) . "</td>";
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '</tbody>
                            </table>';

        return $dataJason;


    }

}
