<?php

namespace App\Http\Controllers\CallLeads;

use App\AccessLog;
use App\Http\Controllers\Controller;
use App\Models\CallLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class CallLeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(){
        $services = DB::table('service__campaigns')->where('service_is_active', 1)->get();
        return view('Admin.CallLeads.index', compact('services'));
    }

    public function search(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $service_id = $request->service_id;
        $environments = $request->environments;

        $leads = CallLeads::join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'call_leads.service_id');

        if (!empty($service_id)) {
            $leads->whereIn('call_leads.service_id', $service_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $leads->whereBetween('call_leads.created_at', [$start_date, $end_date]);
        }

        if ($environments == 5) {
            $leads->where(function ($query) {
                    $query->where('call_leads.first_name', "test");
                    $query->OrWhere('call_leads.last_name', "test");
                    $query->OrWhere('call_leads.first_name', "testing");
                    $query->OrWhere('call_leads.last_name', "testing");
                    $query->OrWhere('call_leads.first_name', "Test");
                    $query->OrWhere('call_leads.last_name', "Test");
                })
                ->where('call_leads.is_duplicate_lead',"<>", 1);
        } elseif ($environments == 6) {
            $leads->where('call_leads.is_duplicate_lead', 1);
        } else {
            $leads->where('call_leads.is_duplicate_lead',"<>", 1)
                ->where('call_leads.first_name', '!=', "test")
                ->where('call_leads.last_name', '!=', "test")
                ->where('call_leads.first_name', '!=', "testing")
                ->where('call_leads.last_name', '!=', "testing")
                ->where('call_leads.first_name', '!=', "Test")
                ->where('call_leads.last_name', '!=', "Test");
        }

        $leads = $leads->orderBy('call_leads.created_at', 'DESC')
            ->groupBy('call_leads.id')
            ->get([
                'service__campaigns.service_campaign_name', 'call_leads.*'
            ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }

        $dataJason = '<table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lead Name</th>
                                    <th>Phone Number</th>
                                    <th>Service</th>
                                    <th>TS</th>
                                    <th>Is Verified</th>
                                    <th>Created At</th>';
        if (empty($permission_users) || in_array('8-10', $permission_users)) {
            $dataJason .= '<th>Action</th>';
        }
        $dataJason .= '</tr>
                       </thead>
                       <tbody>';

        if (!empty($leads)) {
            foreach ($leads as $val) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $val->id . '</td>';
                $dataJason .= '<td>' . $val->first_name . ' ' . $val->last_name . '</td>';
                $dataJason .= '<td>' . $val->phone_number . '</td>';
                $dataJason .= '<td>' . $val->service_campaign_name . '</td>';
                $dataJason .= '<td>' . $val->google_ts . '</td>';
                $dataJason .= '<td>' . ($val->is_verified_phone == 1 ? "True" : "False") . '</td>';
                $dataJason .= '<td>' . $val->created_at . '</td>';
                if (empty($permission_users) || in_array('8-10', $permission_users)) {
                    $dataJason .= '<td>';
                    if (empty($permission_users) || in_array('8-10', $permission_users)) {
                        $dataJason .= '<a href="/Admin/CallLead/' . $val->id . '/details" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                       <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                   </a>';
                    }
                    $dataJason .= '</td>';
                }
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '  </tbody>
                            </table>';

        return $dataJason;
    }

    public function show($id){
        $lead = CallLeads::join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'call_leads.service_id')
            ->where('call_leads.id', $id)
            ->first([
                'service__campaigns.service_campaign_name', 'call_leads.*'
            ]);

        return view('Admin.CallLeads.details', compact('lead'));
    }

    public function export(Request $request){
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';
        $service_id = $request->service_id;
        $environments = $request->environments;

        $leads = CallLeads::join('service__campaigns', 'service__campaigns.service_campaign_id', '=', 'call_leads.service_id');

        if (!empty($service_id)) {
            $leads->whereIn('call_leads.service_id', $service_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $leads->whereBetween('call_leads.created_at', [$start_date, $end_date]);
        }

        if ($environments == 5) {
            $leads->where(function ($query) {
                $query->where('call_leads.first_name', "test");
                $query->OrWhere('call_leads.last_name', "test");
                $query->OrWhere('call_leads.first_name', "testing");
                $query->OrWhere('call_leads.last_name', "testing");
                $query->OrWhere('call_leads.first_name', "Test");
                $query->OrWhere('call_leads.last_name', "Test");
            })
                ->where('call_leads.is_duplicate_lead',"<>", 1);
        } elseif ($environments == 6) {
            $leads->where('call_leads.is_duplicate_lead', 1);
        } else {
            $leads->where('call_leads.is_duplicate_lead',"<>", 1)
                ->where('call_leads.first_name', '!=', "test")
                ->where('call_leads.last_name', '!=', "test")
                ->where('call_leads.first_name', '!=', "testing")
                ->where('call_leads.last_name', '!=', "testing")
                ->where('call_leads.first_name', '!=', "Test")
                ->where('call_leads.last_name', '!=', "Test");
        }

        $leads = $leads->orderBy('call_leads.created_at', 'DESC')
            ->groupBy('call_leads.id')
            ->get([
                'service__campaigns.service_campaign_name', 'call_leads.*'
            ]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export Call Leads",
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadManagement',
            'action'    => 'Export',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $permission_users = array();
        if( !empty(Auth::user()->permission_users) ){
            $permission_users = json_decode(Auth::user()->permission_users, true);
        }
        $info_permission = 0;
        if( empty($permission_users) || in_array('8-23', $permission_users) ){
            $info_permission = 1;
        }

        return (new FastExcel($leads))->download('Call_Leads.csv', function ($lead) use($info_permission) {
            return [
                'ID' => $lead->id,
                'First Name' => $lead->first_name,
                'Last Name' => $lead->last_name,
                'Phone Number' => ($info_permission == 1 ? " " . $lead->phone_number . " " : ""),
                'Service' => $lead->service_campaign_name,
                'Is Verified' => ($lead->is_verified_phone == 1 ? "True" : "False"),
                'Website' => $lead->lead_serverDomain,
                'Time On Browser' => $lead->lead_timeInBrowseData,
                'IP Address' => $lead->lead_ipaddress,
                'PC info' => $lead->lead_aboutUserBrowser,
                'Browser' => $lead->lead_browser_name,
                'Created Date' =>  date('d M Y h:i:s A', strtotime($lead->created_at)),
                'Traffic Source' => $lead->lead_source_text,
                'Jornaya Id' => $lead->universal_leadid,
                'TrustedForm URL' => ($info_permission == 1 ? $lead->trusted_form : ""),
                'TS' => $lead->google_ts,
                'C' => $lead->google_c,
                'K' => $lead->google_k,
                'G' => $lead->google_g,
                'Token' => $lead->token,
                'Visitor ID' => $lead->visitor_id,
                'Full URL' => $lead->lead_FullUrl,
                'gclid' => $lead->google_gclid,
                'TCPA Compliant' => ( $lead->tcpa_compliant == 1 ? "Yes" : "No" ),
                'TCPA Language' => $lead->tcpa_consent_text
            ];
        });
    }
}
