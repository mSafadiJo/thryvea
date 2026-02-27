<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\TotalAmount;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use DateInterval;
use DatePeriod;
use DateTime;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'UserActiveMiddleware', 'buyersCustomer']);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // to redirect to Buyer And Seller Home
        if (Auth::user()->role_id == 4) {
            return redirect()->route('BuyerAndSellerHome');
        }
        // to redirect to Buyer Home
        else if (Auth::user()->role_id == 3 || Auth::user()->role_id == 6) {
            return redirect()->route('BuyerHome');
        }
        // to redirect to Seller Home
        else if (Auth::user()->role_id == 5) {
            return redirect()->route('SellerHome');
        }
        // to Rev Share Seller Home
        else if (Auth::user()->role_id == 7) {
            return redirect()->route('RevShareSellerHome');
        }
    }

    public function HomeBuyer()
    {
        $LeadsCount = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('campaigns_leads_users.user_id', Auth::user()->id)
            ->where('campaigns.campaign_Type', '<>', 3)
            ->where('campaigns_leads_users.is_returned', 0)->count();

        // get Return Lead The user’s
        $ticket_returnlead = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
            ->where('ticket_type', 2)->where('user_id', Auth::user()->id)->count();

        // get Count campaigns The user’s
        $campaignsCount = DB::table('campaigns')->where('user_id', Auth::user()->id)
            ->where('campaign_visibility', 1)
            ->where('is_seller', 0)
            ->where('campaign_status_id', 1)
            ->count();

        // get service if campaign is active
        $services = DB::table('campaigns')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->where('campaigns.user_id', Auth::user()->id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.is_seller', 0)
            ->where('campaigns.campaign_status_id', 1)
            ->pluck('service__campaigns.service_campaign_name')->unique()->toArray();

        // get total Ammount user
        $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

        $transactions_comments = ['Credit Accumulation', 'Auto Credit Accumulation', 'eCheck', 'PayPal', 'ACH Credit', 'Add Credit'];

        // get total bid user
        $total_bid = Transaction::where('user_id', Auth::user()->id)
            ->where('transaction_status', 1)
            ->where("transactions.accept", 1)
            ->whereIn('transactions_comments', $transactions_comments)
            ->sum('transactions_value');

        $total_spend = DB::table('campaigns_leads_users')->where('user_id', Auth::user()->id)
            ->where('is_returned', 0)->sum('campaigns_leads_users_bid');

        $last_transaction = DB::table('campaigns_leads_users')->where('user_id', Auth::user()->id)
            ->orderBy('date', 'desc')->first();

        // to get lead count Dailies
        $date = date('Y-m-d');
        $leadsCampaignsDailies = DB::table('campaigns_leads_users')
            ->where('user_id', Auth::user()->id)
            ->where('created_at', "like","%".$date."%")
            ->count();

        // to get lead count Weekly
        $leadsCampaignsWeekly = DB::table('campaigns_leads_users')
            ->where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [date('Y-m-d', strtotime(Carbon::now()->startOfWeek())),date('Y-m-d', strtotime(Carbon::now()->endOfWeek()))])
            ->count();

        // to get lead count Monthly
        $leadsCampaignsMonthly = DB::table('campaigns_leads_users')
            ->where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [date('Y-m'). '-1',date('Y-m-t')])
            ->count();

        //to get lead last today
        $LeadsToday = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('campaigns_leads_users.user_id', Auth::user()->id)
            ->where('campaigns.campaign_Type', '<>', 3)
            ->where('campaigns_leads_users.is_returned', 0)
            ->where('campaigns_leads_users.created_at', "like","%".$date."%")
            ->get(['leads_customers.*', 'campaigns_leads_users.campaigns_leads_users_id']);

        //to get return leads amount
        $list_of_return_amount = DB::table('transactions')
            ->where("user_id", Auth::user()->id)
            ->where("accept", 1)
            ->where('transactions_comments', 'like', '%Return Leads Amount%')
            ->whereNotNull("transactionauthid")
            ->sum('transactions_value');

        return view('Buyers.HomeBuyer')
            ->with('LeadsCount', $LeadsCount)
            ->with('ticket_returnlead', $ticket_returnlead)
            ->with('campaignsCount', $campaignsCount)
            ->with('total_bid', $total_bid)
            ->with('totalAmmount', $totalAmmount)
            ->with('total_spend', $total_spend)
            ->with('last_transaction', $last_transaction)
            ->with('services', $services)
            ->with('leadsCampaignsDailies', $leadsCampaignsDailies)
            ->with('leadsCampaignsWeekly', $leadsCampaignsWeekly)
            ->with('leadsCampaignsMonthly', $leadsCampaignsMonthly)
            ->with('LeadsToday', $LeadsToday)
            ->with('list_of_return_amount', $list_of_return_amount);
    }

    public function HomeBuyerAndSeller()
    {
        return view('Buyers.HomeBuyerAndSeller');
    }

    public function HomeSeller(){
        $CountSellerLead = DB::table('leads_customers')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->where('camp_seller.user_id', Auth::user()->id)
            ->where('leads_customers.response_data', "Lead Accepted")
            ->count();

        // to get lead count Dailies
        $date = date('Y-m-d');
        $CountSellerLeadDailies = DB::table('leads_customers')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->where('camp_seller.user_id', Auth::user()->id)
            ->where('leads_customers.response_data', "Lead Accepted")
            ->where('leads_customers.created_at', "like","%".$date."%")
            ->count();

        // to get lead count Weekly
        $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
        $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
        $CountSellerLeadWeekly = DB::table('leads_customers')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->where('camp_seller.user_id', Auth::user()->id)
            ->where('leads_customers.response_data', "Lead Accepted")
            ->whereBetween('leads_customers.created_at', [$weekStartDate, $weekEndDate])
            ->count();

        // to get lead count Monthly
        $dateS = Carbon::now()->startOfMonth()->format('Y-m-d 00:00:00');
        $dateE = Carbon::now()->endOfMonth()->format('Y-m-d 23:59:59');
        $CountSellerLeadMonthly = DB::table('leads_customers')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id')
            ->where('camp_seller.user_id', Auth::user()->id)
            ->where('leads_customers.response_data', "Lead Accepted")
            ->whereBetween('leads_customers.created_at', [$dateS, $dateE])
            ->count();

        return view('Buyers.HomeSeller')
            ->with('CountSellerLead', $CountSellerLead)
            ->with('CountSellerLeadDailies', $CountSellerLeadDailies)
            ->with('CountSellerLeadWeekly', $CountSellerLeadWeekly)
            ->with('CountSellerLeadMonthly', $CountSellerLeadMonthly);
    }

    public function ListOfLeadsBuyers()
    {

        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        return view('Buyers.Leads.ListOfLeadBuyers')->with('services', $services);
    }

    public function ListOfLeadsSeller()
    {
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get()->All();
        return view('Buyers.Leads.ListOfLeadSeller')->with('services', $services);
    }

    public function export_lead_data(Request $request){
        $service_id = $request->service_id;
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $type = $request->type;

        if( $type == 1 ){
            //Received Lead
            $lead_data = DB::table('campaigns_leads_users')
                ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('states', 'states.state_id', '=', 'leads_customers.lead_state_id')
                ->join('counties', 'counties.county_id', '=', 'leads_customers.lead_county_id')
                ->join('cities', 'cities.city_id', '=', 'leads_customers.lead_city_id')
                ->join('zip_codes_lists', 'zip_codes_lists.zip_code_list_id', '=', 'leads_customers.lead_zipcode_id')
                ->where('campaigns_leads_users.user_id', Auth::user()->id)
                ->where('campaigns.campaign_Type', '<>', 3)
                //->where('campaigns.campaign_visibility', 1)
                ->where('campaigns_leads_users.is_returned', 0);

            if (!empty($service_id)) {
                $lead_data->whereIn('leads_customers.lead_type_service_id', $service_id);
            }

            if (!empty($start_date) && !empty($end_date)) {
                $lead_data->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date]);
            }

            $lead_data = $lead_data->orderBy('campaigns_leads_users.created_at', 'DESC')
                ->get([
                    'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name',
                    'campaigns_leads_users.campaigns_leads_users_type_bid',
                    'campaigns_leads_users.campaigns_leads_users_bid',
                    'campaigns_leads_users.created_at AS created_at_lead',
                    'campaigns_leads_users.campaigns_leads_users_note',
                    'states.state_code', 'counties.county_name', 'cities.city_name', 'zip_codes_lists.zip_code_list',
                    'service__campaigns.service_campaign_name', 'leads_customers.*'
                ]);

            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) {
                return [
                    'Lead ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => $lead->lead_email,
                    'Phone Number' => " " . $lead->lead_phone_number . " ",
                    'Service' => $lead->service_campaign_name,
                    'Status' => $lead->campaigns_leads_users_note,
                    'Bid' => $lead->campaigns_leads_users_bid,
                    'Type' => $lead->campaigns_leads_users_type_bid,
                    'Campaign' => $lead->campaign_name,
                    'Address' => $lead->lead_address,
                    'City' => $lead->city_name,
                    'County' => $lead->county_name,
                    'State' => $lead->state_code,
                    'ZIPCode' => " " . $lead->zip_code_list . " ",
                    'Created Date' => $lead->created_at_lead,
                    'Details' => $lead->lead_details_text,
                    'Jornaya Id' => $lead->universal_leadid,
                    'TrustedForm URL' => $lead->trusted_form
                ];
            });
        }
        else if( $type == 3 ) {
            //Return Lead
            $listOfLeads = Ticket::join('reason_lead_returned', 'reason_lead_returned.reason_lead_returned_id', 'tickets.reason_lead_returned_id')
                ->join('campaigns_leads_users', 'campaigns_leads_users.campaigns_leads_users_id', '=', 'tickets.campaigns_leads_users_id')
                ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
                ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
                ->join('users', 'tickets.user_id', '=', 'users.id')
                ->where('tickets.ticket_type', 2)
                ->where('tickets.user_id', Auth::user()->id);

            if( !empty($start_date) && !empty($end_date) ){
                $listOfLeads->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date]);
            }

            $lead_data = $listOfLeads->orderBy('leads_customers.created_at', 'DESC')
                ->get([
                    'reason_lead_returned.reason_lead_returned_name', 'tickets.*', 'campaigns_leads_users.date', 'service__campaigns.service_campaign_name',
                    'leads_customers.lead_id', 'leads_customers.*','users.user_business_name'
                ]);

            return (new FastExcel($lead_data))->download('leads.csv', function ($lead) {
                return [
                    'Lead ID' => $lead->lead_id,
                    'First Name' => $lead->lead_fname,
                    'Last Name' => $lead->lead_lname,
                    'Email Address' => $lead->lead_email,
                    'Phone Number' => " " . $lead->lead_phone_number . " ",
                    'Service' => $lead->service_campaign_name,
                    'Reason' => $lead->reason_lead_returned_name,
                    'Ticket Message' => $lead->ticket_message,
                    'Reject Message' => $lead->reject_text,
                    'Status' => ($lead->ticket_status == 1 ? 'Not Started' : ($lead->ticket_status == 2 ? 'Open' : ($lead->ticket_status == 3 ? 'Accepted / Close' : ($lead->ticket_status == 4 ? 'Reject' : ($lead->ticket_status == 5 ? 'In Progress' : ''))))),
                    'Sold Date' => $lead->date,
                    'Return Date' => $lead->created_at,
                ];
            });
        }
    }

    public function ListOfCampaign()
    {
        $campaigns = DB::table('campaigns')
            ->join('campaign_status', 'campaign_status.campaign_status_id', '=', 'campaigns.campaign_status_id')
            ->join('campaign_types', 'campaign_types.campaign_types_id', '=', 'campaigns.campaign_Type')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->where('campaigns.user_id', Auth::user()->id)
            ->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.is_seller', 0)
            ->orderBy('campaigns.created_at', 'DESC')
            ->get(['campaigns.*', 'service__campaigns.service_campaign_name', 'campaign_status.campaign_status_name',  'campaign_types.campaign_types_name']);

        $campaign_status = DB::table('campaign_status')->get()->all();

        return view('Buyers.Campaign.ListOfCampain')
            ->with('campaigns', $campaigns)
            ->with('campaign_status', $campaign_status);
    }

    public function list_of_leads_BuyersAjax(Request $request)
    {
        $service_id = $request->service_id;
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $campaignLeads = DB::table('campaigns_leads_users')
            ->join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'campaigns.service_campaign_id')
            ->join('leads_customers', 'leads_customers.lead_id', '=', 'campaigns_leads_users.lead_id')
            ->where('campaigns_leads_users.user_id', Auth::user()->id)
            //->where('campaigns.campaign_visibility', 1)
            ->where('campaigns.campaign_Type', '<>', 3)
            ->where('campaigns_leads_users.is_returned', 0);

        if (!empty($service_id)) {
            $campaignLeads->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $campaignLeads->whereBetween('campaigns_leads_users.created_at', [$start_date, $end_date]);
        }

        $ListOfLeadsNotIn = $campaignLeads->orderBy('campaigns_leads_users.created_at', 'DESC')
            ->get([
                'campaigns_leads_users.campaigns_leads_users_id', 'campaigns.campaign_name',
                'campaigns_leads_users.campaigns_leads_users_type_bid',
                'campaigns_leads_users.created_at AS created_at_lead',
                'campaigns_leads_users.campaigns_leads_users_note', 'campaigns_leads_users.buyer_lead_note',
                'service__campaigns.service_campaign_name', 'leads_customers.*'
            ])->all();

        $dataJason = '';
        $dataJason .= ' <table id="datatableBuyersLead" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                <th>Lead ID</th>
                                <th>Lead Name</th>
                                <th>Campaign Name</th>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Trusted Form URL</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>';

        $dataJason .= '</tr>
                       </thead>
                       <tbody>';
        if (!empty($ListOfLeadsNotIn)) {
            foreach ($ListOfLeadsNotIn as $ListLead) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $ListLead->lead_id . '</td>';
                $dataJason .= '<td>' . $ListLead->lead_fname . ' ' . $ListLead->lead_lname . '</td>';
                $dataJason .= '<td>' . $ListLead->campaign_name . '</td>';
                $dataJason .= '<td>' . $ListLead->service_campaign_name . '</td>';
                $dataJason .= '<td>' . $ListLead->campaigns_leads_users_type_bid . '</td>';
                $dataJason .= '<td>';
                if (!empty($ListLead->trusted_form)) {
                    $dataJason .= '<a href="' . $ListLead->trusted_form . '" target="_blank">Trusted Form</a>';
                }
                $dataJason .= '</td>';

                $dataJason .= '<td>';
                $dataJason .= '<select id="LeadBuyersStatusChange-' . $ListLead->campaigns_leads_users_id . '" class="form-control" style="height: unset;width: 80%;"
                                                    onchange="return changeLeadStatusForBuyer(' . $ListLead->campaigns_leads_users_id . ');">';
                if ($ListLead->campaigns_leads_users_note == "In Progress") {
                    $dataJason .= '<option value="In Progress" selected>In Progress</option>';
                } else {
                    $dataJason .= '<option value="In Progress" >In Progress</option>';
                }
                if ($ListLead->campaigns_leads_users_note == "Appointment") {
                    $dataJason .= '<option value="Appointment" selected>Appointment</option>';
                } else {
                    $dataJason .= '<option value="Appointment" >Appointment</option>';
                }
                if ($ListLead->campaigns_leads_users_note == "Sale") {
                    $dataJason .= '<option value="Sale" selected>Sale</option>';
                } else {
                    $dataJason .= '<option value="Sale" >Sale</option>';
                }
                if ($ListLead->campaigns_leads_users_note == "Interested") {
                    $dataJason .= '<option value="Interested" selected>Interested</option>';
                } else {
                    $dataJason .= '<option value="Interested" >Interested</option>';
                }
                if ($ListLead->campaigns_leads_users_note == "Not Interested") {
                    $dataJason .= '<option value="Not Interested" selected>Not Interested</option>';
                } else {
                    $dataJason .= '<option value="Not Interested" >Not Interested</option>';
                }
                if ($ListLead->campaigns_leads_users_note == "Sold") {
                    $dataJason .= '<option value="Sold" selected>Sold</option>';
                } else {
                    $dataJason .= '<option value="Sold" >Sold</option>';
                }
                $dataJason .= '</select>';
                $dataJason .= '<td>' . $ListLead->created_at_lead . '</td>';
                $dataJason .= '<td>';
                $dataJason .= '<a href="'.route('ShowCampaignLeadsDetails', $ListLead->campaigns_leads_users_id).'" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                            </a>';

                $dataJason .= '<span style="caesar: pointer" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="Lead Note"
                                    data-original-title="Lead Note" data-trigger="hover" data-animation="false"
                                    onclick="show_script_text_data(\'' . $ListLead->campaigns_leads_users_id . '\', \'Notes On Lead #' . $ListLead->lead_id . ' (' . $ListLead->lead_fname . ' ' . $ListLead->lead_lname . ')\');">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                               </span>
                               <textarea id="show_script_text_data-' . $ListLead->campaigns_leads_users_id . '" style="display: none;">' . $ListLead->buyer_lead_note . '</textarea>';

                $dataJason .= '</td>';

                $dataJason .= '</tr>';
            }
        }
        $dataJason .= '  </tbody>
                            </table>';
        return $dataJason;
    }

    public function buyer_lead_note_update(Request $request){
        DB::table('campaigns_leads_users')
            ->where('campaigns_leads_users_id', $request->lead_id)
            ->update(['buyer_lead_note' => $request->lead_note]);
        return true;
    }

    public function list_of_leads_SellerAjax(Request $request){
        $service_id = $request->service_id;
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $campaignLeadsReport = DB::table('leads_customers')
            ->join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'leads_customers.lead_type_service_id')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'leads_customers.vendor_id');

        if (!empty($service_id)) {
            $campaignLeadsReport->whereIn('leads_customers.lead_type_service_id', $service_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $campaignLeadsReport->whereBetween('leads_customers.created_at', [$start_date, $end_date]);
        }

        $ListOfLeadsNotIn = $campaignLeadsReport->where('camp_seller.campaign_visibility', 1)
            ->where('camp_seller.user_id', Auth::user()->id)
            ->where('leads_customers.response_data', "Lead Accepted")
            ->orderBy('leads_customers.created_at', 'DESC')
            ->groupBy('leads_customers.lead_id')
            ->get([
                'service__campaigns.service_campaign_name', 'leads_customers.*', 'camp_seller.campaign_name AS seller_campaign_name'
            ]);

        $dataJason = '';
        $dataJason .= ' <table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                <th>ID</th>
                                <th>Lead Name</th>
                                <th>Campaign Name</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Created At</th>
                                ';

        $dataJason .= '</tr>
                       </thead>
                       <tbody>';
        if (!empty($ListOfLeadsNotIn)) {
            foreach ($ListOfLeadsNotIn as $ListLead) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $ListLead->lead_id . '</td>';
                $dataJason .= '<td>' . $ListLead->lead_fname . ' ' . $ListLead->lead_lname . '</td>';
                $dataJason .= '<td>' . $ListLead->seller_campaign_name . '</td>';
                $dataJason .= '<td>' . $ListLead->service_campaign_name . '</td>';
                $dataJason .= '<td>'.$ListLead->ping_price.'</td>';
                $dataJason .= '<td>' . $ListLead->created_at . '</td>';
                $dataJason .= '</tr>';
            }
        }
        $dataJason .= '  </tbody>
                            </table>';
        return $dataJason;
    }

    public function lead_change_status(Request $request){
        $id = $request->id;
        $status = $request->status;

        DB::table('campaigns_leads_users')->where('campaigns_leads_users_id', $id)->update([
            'campaigns_leads_users_note' => $status
        ]);

        return true;
    }

    public function listOfClickLeads(){
        return view('Buyers.Leads.ListOfClickLeads');
    }

    public function listOfClickLeadsAjax(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $campaigns = DB::table('campaigns')
            ->where('campaigns.user_id', Auth::user()->id)
            ->where('campaign_Type', 3)
            ->where('campaign_visibility', 1)
            ->get(["campaign_name", "campaign_id"]);

        $click_leads = DB::table('campaigns_leads_users')
            ->Join('campaigns', 'campaigns.campaign_id', '=', 'campaigns_leads_users.campaign_id')
            ->where('campaigns_leads_users.user_id', Auth::user()->id)
            ->where('campaigns_leads_users.is_returned', 0)
            ->where('campaigns.campaign_Type', 3)
            ->where('campaigns.campaign_visibility', 1);

        if (!empty($start_date) && !empty($end_date)) {
            $click_leads->whereBetween('campaigns_leads_users.date', [$start_date, $end_date]);
        }

        $click_leads->groupBy('campaigns_leads_users.campaign_id');

        $total_click_leads = $click_leads->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
            ->pluck('totalLeads', "campaignId")->toarray();

        $sum_click_leads = $click_leads->selectRaw('SUM(campaigns_leads_users.campaigns_leads_users_bid) AS Leads_sum, campaigns.campaign_id as campaignId')
            ->pluck('Leads_sum', "campaignId")->toarray();

        $total_conversions_leads = $click_leads->where('campaigns_leads_users.campaigns_leads_users_note', "converted")
            ->selectRaw('COUNT(campaigns_leads_users.lead_id) AS totalLeads, campaigns.campaign_id as campaignId')
            ->pluck('totalLeads', "campaignId")->toarray();

        $dataJason = '';
        $dataJason .= '<table id="datatableBuyersLead" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Campaign ID</th>
                                    <th>Campaign Name</th>
                                    <th>Number of Click</th>
                                    <th>Number of Conversions</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                       <tbody>';

        foreach ($campaigns as $campaign) {
            $dataJason .= '<tr>';
            $dataJason .= '<td>' . $campaign->campaign_id . '</td>';
            $dataJason .= '<td>' . $campaign->campaign_name . '</td>';
            $dataJason .= '<td>' . (!empty($total_click_leads[$campaign->campaign_id]) ? $total_click_leads[$campaign->campaign_id] : 0) . '</td>';
            $dataJason .= '<td>' . (!empty($total_conversions_leads[$campaign->campaign_id]) ? $total_conversions_leads[$campaign->campaign_id] : 0) . '</td>';
            $dataJason .= '<td>$' . (!empty($sum_click_leads[$campaign->campaign_id]) ? $sum_click_leads[$campaign->campaign_id] : 0) . '</td>';
            $dataJason .= '</tr>';
        }

        $dataJason .= '  </tbody>
                            </table>';

        return $dataJason;
    }

    //RevShare Sellers
    public function HomeRevShareSeller(){
        return view('Buyers.HomeRevShareSeller');
    }

    public function HomeRevShareSellerAjax(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $LeadsCount = DB::table('leads_customers')
            ->where('google_g', Auth::user()->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('is_duplicate_lead', "<>", 1)
            ->where('lead_fname', '!=', "test")
            ->where('lead_lname', '!=', "test")
            ->where('lead_fname', '!=', "testing")
            ->where('lead_lname', '!=', "testing")
            ->where('lead_fname', '!=', "Test")
            ->where('lead_lname', '!=', "Test")
            ->where('is_test', 0);

        $sum_bid_leads_arr = DB::table('leads_customers')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.google_g', Auth::user()->id)
            ->whereBetween('leads_customers.created_at', [$start_date, $end_date])
            ->where('leads_customers.is_duplicate_lead', "<>", 1)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0);

        switch (Auth::user()->id){
            case 55:
                // Mobidea
            case 56:
                // finnetpartners
            case 59:
                // WedeBeek
            $LeadsCount = $LeadsCount->where('is_sec_service', 0)
                    ->where('flag', null);

            $sum_bid_leads_arr = $sum_bid_leads_arr->where('leads_customers.is_sec_service', 0)
                    ->where('leads_customers.flag', null);
                break;
        }

        $LeadsCount = $LeadsCount->distinct("lead_phone_number")
            ->count();

        $sum_bid_leads_arr = $sum_bid_leads_arr->first(DB::raw('SUM(campaigns_leads_users.campaigns_leads_users_bid) as sum_bid_leads'));

        $count_bid_leads = (!empty($LeadsCount) ? $LeadsCount : 0);
        $sum_bid_leads = (!empty($sum_bid_leads_arr->sum_bid_leads) ? $sum_bid_leads_arr->sum_bid_leads : 0);

        //Last Transactions
        $last_transaction = DB::table('leads_customers')
            ->where('google_g', Auth::user()->id)
            ->where('is_duplicate_lead', "<>", 1)
            ->where('lead_fname', '!=', "test")
            ->where('lead_lname', '!=', "test")
            ->where('lead_fname', '!=', "testing")
            ->where('lead_lname', '!=', "testing")
            ->where('lead_fname', '!=', "Test")
            ->where('lead_lname', '!=', "Test")
            ->where('is_test', 0);

        switch (Auth::user()->id) {
             case 55:
                // Mobidea
            case 56:
                // finnetpartners
            case 59:
                // WedeBeek 
            $last_transaction = $last_transaction->where('is_sec_service', 0)
                    ->where('flag', null);
                break;
        }

        $last_transaction = $last_transaction->orderBy('created_at', 'desc')
            ->first('created_at');

        $result = array(
            "LeadsCount" => $count_bid_leads,
            "total_bid_lead" => "$" . $sum_bid_leads,
            "percentage" => "%" . (Auth::user()->profit_percentage * 100),
            "profit_percentage" => "$" . (Auth::user()->profit_percentage * $sum_bid_leads),
            "last_transaction" => (!empty($last_transaction->created_at) ? date('m/d/Y', strtotime($last_transaction->created_at)) : "---")
        );

        return $result;
    }


    public function ListOfLeadsRevShare(){
        return view('Buyers.Leads.ListOfLeadRevShare');
    }

    public function list_of_LeadsRevShareAjax(Request $request){
        if($request->ajax()){
            $start_date = $request->start_date . ' 00:00:00';
            $end_date = $request->end_date . ' 23:59:59';

            $query_search = $request->get('query');
            $query_search = str_replace(" ", "%", $query_search);

            $list_of_leads = DB::table('leads_customers')
                ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
                ->where('leads_customers.google_g', Auth::user()->id)
                ->whereBetween('leads_customers.created_at', [$start_date, $end_date])
                ->where('leads_customers.is_duplicate_lead', "<>", 1)
                ->where('leads_customers.lead_fname', '!=', "test")
                ->where('leads_customers.lead_lname', '!=', "test")
                ->where('leads_customers.lead_fname', '!=', "testing")
                ->where('leads_customers.lead_lname', '!=', "testing")
                ->where('leads_customers.lead_fname', '!=', "Test")
                ->where('leads_customers.lead_lname', '!=', "Test")
                ->where('leads_customers.is_test', 0);

            switch (Auth::user()->id) {
                case 1235:
                    // Turtle Leads TL: 1235
                case 1268:
                    // One Pride: 1268
                case 1305:
                    // DM: 1305
                case 1342:
                    // scoremobi sm: 1342
                $list_of_leads = $list_of_leads->where('leads_customers.is_sec_service', 0)
                        ->where('leads_customers.flag', null);
                    break;
            }

            $list_of_leads = $list_of_leads->where(function ($query) use ($query_search) {
                $query->where('leads_customers.lead_id', 'like', '%' . $query_search . '%');
                $query->orWhere('leads_customers.lead_fname', 'like', '%' . $query_search . '%');
                $query->orWhere('leads_customers.lead_lname', 'like', '%' . $query_search . '%');
                $query->orWhere(DB::raw("concat(leads_customers.lead_fname, ' ', leads_customers.lead_lname)"), 'like', "%".$query_search."%");
            })->groupBy("leads_customers.lead_phone_number")
                ->orderBy('created_at', 'DESC')
                ->select([DB::raw('SUM(campaigns_leads_users.campaigns_leads_users_bid) as sum_bid_leads'), 'leads_customers.lead_id', 'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.created_at'])
                ->simplePaginate(10);

            return view('Render.RevShareSeller.RevShareSellerRender', compact('list_of_leads'))->render();
        }
    }

    public function exportRevShareData(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $sum_bid_leads_arr = DB::table('leads_customers')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.google_g', Auth::user()->id)
            ->whereBetween('leads_customers.created_at', [$start_date, $end_date])
            ->where('leads_customers.is_duplicate_lead', "<>", 1)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0)
            ->where('leads_customers.is_sec_service', 0)
            ->where('leads_customers.flag', null)
            ->distinct("leads_customers.lead_phone_number")
            ->groupBy('date_transaction')
            ->get([DB::raw('SUM(IFNULL(campaigns_leads_users.campaigns_leads_users_bid * ' . Auth::user()->profit_percentage . ' , 0)) as sum_bid_leads'), DB::raw('COUNT(DISTINCT leads_customers.lead_id) as leads_count'), DB::raw('DATE(leads_customers.created_at) as date_transaction')]);

        $sum_bid_leads_arr = json_decode($sum_bid_leads_arr, true);

        $foundRecordsArray = array();

        foreach ($sum_bid_leads_arr as $sumBid){
            $foundRecordsArray[] = $sumBid['date_transaction'];
        }

        $period = new DatePeriod(new DateTime($request->start_date), new DateInterval('P1D'), new DateTime("$request->end_date +1 day"));
        foreach ($period as $date) {
            $dates[] = $date->format("Y-m-d");
        }

        $emptyDatesArray = array_diff($dates, $foundRecordsArray);

        $emptyValuesArray = array();

        foreach ($emptyDatesArray as $date){
            $emptyValuesArray[] = array(
                "sum_bid_leads" => 0,
                "leads_count" => 0,
                "date_transaction" => $date
            );
        }

        $newEmptyFullArrays = array_merge($sum_bid_leads_arr, $emptyValuesArray);

        return (new FastExcel($newEmptyFullArrays))->download('Data.csv', function ($data) {
            return [
                "Number Of Leads" => $data['leads_count'],
                "Profit" => $data['sum_bid_leads'],
                "Date" => $data['date_transaction']
            ];
        });
    }

    public function ExportDataLeadsTable(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $list_of_leads = DB::table('leads_customers')
            ->leftJoin('campaigns_leads_users', 'campaigns_leads_users.lead_id', '=', 'leads_customers.lead_id')
            ->where('leads_customers.google_g', Auth::user()->id)
            ->whereBetween('leads_customers.created_at', [$start_date, $end_date])
            ->where('leads_customers.is_duplicate_lead', "<>", 1)
            ->where('leads_customers.lead_fname', '!=', "test")
            ->where('leads_customers.lead_lname', '!=', "test")
            ->where('leads_customers.lead_fname', '!=', "testing")
            ->where('leads_customers.lead_lname', '!=', "testing")
            ->where('leads_customers.lead_fname', '!=', "Test")
            ->where('leads_customers.lead_lname', '!=', "Test")
            ->where('leads_customers.is_test', 0);

        switch (Auth::user()->id) {
            case 1235:
                // Turtle Leads TL: 1235
            case 1268:
                // One Pride: 1268
            case 1305:
                // DM: 1305
            case 1342:
                // scoremobi sm: 1342
                $list_of_leads = $list_of_leads->where('leads_customers.is_sec_service', 0)
                    ->where('leads_customers.flag', null);
                break;
        }

        $list_of_leads = $list_of_leads->groupBy("leads_customers.lead_phone_number")
            ->orderBy('created_at', 'DESC')
            ->get([DB::raw('SUM(campaigns_leads_users.campaigns_leads_users_bid) as sum_bid_leads'), 'leads_customers.lead_id', 'leads_customers.lead_fname', 'leads_customers.lead_lname', 'leads_customers.created_at']);

        return (new FastExcel($list_of_leads))->download('Data.csv', function ($data) {
            return [
                "Lead ID" => $data->lead_id,
                "Lead Name" => $data->lead_fname . " " . $data->lead_lname,
                "Price" => (!empty($data->sum_bid_leads) ? $data->sum_bid_leads : 0),
                "Profit" => ($data->sum_bid_leads * Auth::user()->profit_percentage),
                "Created At" => $data->created_at
            ];
        });

    }
}
