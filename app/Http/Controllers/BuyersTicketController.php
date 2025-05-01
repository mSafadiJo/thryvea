<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class BuyersTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer']);
    }

    public function index(){
        $reason_lead_returneds = DB::table('reason_lead_returned')->get()->all();

        $ticket_issues = Ticket::where('ticket_type', 1)
            ->where('user_id', Auth::user()->id)
            ->get()->all();

        return view('Buyers.ticket.index')
            ->with('reason_lead_returneds', $reason_lead_returneds)
            ->with('ticket_issues', $ticket_issues);
    }

    public function ReturnLeadBuyers(Request $request)
    {
        return view('Buyers.Leads.ListOfReturnLeadBuyers');
    }

    public function ReturnLeadBuyersAjax(Request $request)
    {
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->where('tickets.ticket_type', 2)
            ->where('tickets.user_id', Auth::user()->id);

        if (!empty($start_date) && !empty($end_date)) {
            $ticket_returnlead->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date]);
        }

        $ListOfLeadsReturn = $ticket_returnlead->orderBy('leads_customers.created_at', 'DESC')
            ->get([
                'reason_lead_returned.reason_lead_returned_name', 'tickets.*', 'campaigns_leads_users.date', 'service__campaigns.service_campaign_name',
                'leads_customers.lead_id', 'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.lead_phone_number','users.user_business_name'
            ]);

        $dataJason = '';
        $dataJason .= ' <table id="datatableBuyersReturnLead" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                <th>#</th>
                                <th>Lead ID</th>
                                <th>Lead Name</th>
                                <th>Service</th>
                                <th>Lead Phone Number</th>
                                <th>Reason</th>
                                <th>Ticket Message</th>
                                <th>Reject Message</th>
                                <th>Status</th>
                                <th>Sold Date</th>
                                <th>Return Date</th>';

        $dataJason .= '</tr>
                       </thead>
                       <tbody>';
        if (!empty($ListOfLeadsReturn)) {
            foreach ($ListOfLeadsReturn as $ListLead) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $ListLead->ticket_id . '</td>';
                $dataJason .= '<td>' . $ListLead->lead_id . '</td>';
                $dataJason .= '<td>' . $ListLead->lead_fname . ' ' . $ListLead->lead_lname . '</td>';
                $dataJason .= '<td>' . $ListLead->service_campaign_name . '</td>';
                $dataJason .= '<td>' . $ListLead->lead_phone_number . '</td>';
                $dataJason .= '<td>' . $ListLead->reason_lead_returned_name . '</td>';
                $dataJason .= '<td>' . $ListLead->ticket_message . '</td>';

                $dataJason .= '<td>';
                if ($ListLead->ticket_status == 4) {
                    $dataJason .= ' <button type="button" class="btn btn-primary" onclick="return showTicketMassage('. $ListLead->ticket_id .');"> show</button>';
                    $dataJason .= '<textarea id="showTicketMassage-'.$ListLead->ticket_id.'" name="showTicketMassage-'.$ListLead->ticket_id.'" style="display:none;">'.$ListLead->reject_text.' </textarea>';
                }
                $dataJason .= '</td>';

                $dataJason .= '<td>';
                if ($ListLead->ticket_status == 1) {
                    $dataJason .= 'Not Started';
                } elseif ($ListLead->ticket_status == 2) {
                    $dataJason .= 'Open';
                } elseif ($ListLead->ticket_status == 3) {
                    $dataJason .= 'Accepted / Close';
                } elseif ($ListLead->ticket_status == 4){
                    $dataJason .= 'Reject';
                } elseif ($ListLead->ticket_status == 5){
                    $dataJason .= 'In Progress';
                }
                $dataJason .= '</td>';

                $dataJason .= '<td>' . $ListLead->date . '</td>';
                $dataJason .= '<td>' . $ListLead->created_at . '</td>';
                $dataJason .= '</tr>';
            }
        }
        $dataJason .= '  </tbody>
                            </table>';
        return $dataJason;
    }

    public function store_issues(Request $request){
        $this->validate($request, [
            'ticket_message' => ['required', 'string', 'max:255']
        ]);

        $ticket = new Ticket();
        $ticket->ticket_type = 1;
        $ticket->user_id = Auth::user()->id;
        $ticket->ticket_message = $request->ticket_message;
        $ticket->ticket_status = 1;
        $ticket->save();

        $newticketid = DB::getPdo()->lastInsertId();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $newticketid,
            'section_name' => 'Issue Ticket',
            'user_role' => Auth::user()->role_id,
            'section'   => 'Ticket',
            'action'    => 'Create',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function store_returnlead(Request $request){
        $this->validate($request, [
            'ticket_message' => ['required', 'string', 'max:255'],
            'lead_id' => ['required'],
            'reason_returned' => ['required'],
        ]);

        $lead_found = DB::table('campaigns_leads_users')
            ->where('user_id', Auth::user()->id)
            ->where('lead_id', $request->lead_id)
            ->where('is_returned', 0)
            ->first(['campaigns_leads_users_id', 'campaign_id', 'campaigns_leads_users_type_bid', 'is_returned']);

        if( !empty($lead_found) ){
            $is_exist = Ticket::where('ticket_type', 2)
                ->where('user_id', Auth::user()->id)
                ->where('campaigns_leads_users_id', $lead_found->campaigns_leads_users_id)
                ->first();

            if( empty($is_exist) ) {
                $ticket = new Ticket();

                $ticket->ticket_type = 2;
                $ticket->user_id = Auth::user()->id;
                $ticket->ticket_message = $request->ticket_message;
                $ticket->campaigns_leads_users_id = $lead_found->campaigns_leads_users_id;
                $ticket->reason_lead_returned_id = $request->reason_returned;
                $ticket->campaign_id = $lead_found->campaign_id;
                $ticket->campaigns_leads_users_type_bid = $lead_found->campaigns_leads_users_type_bid;
                $ticket->ticket_status = 1;

                $ticket->save();

                $newticketid = DB::getPdo()->lastInsertId();

                //Access LOG
                AccessLog::create([
                    'user_id' => Auth::user()->id,
                    'user_name' => Auth::user()->username,
                    'section_id' => $newticketid,
                    'section_name' => 'Return Lead Ticket',
                    'user_role' => Auth::user()->role_id,
                    'section' => 'Ticket',
                    'action' => 'Create',
                    'ip_address' => request()->ip(),
                    'location' => json_encode(\Location::get(request()->ip())),
                    'request_method' => json_encode($request->all())
                ]);

                Session::flash('success', 'Return lead #' . $request->lead_id . ' successfully');
            } else {
                Session::flash('error', 'This lead has already been submitted!');
            }
        } else {
            Session::flash('error', 'Unknown Lead id#');
        }

        return redirect()->back();
    }
}
