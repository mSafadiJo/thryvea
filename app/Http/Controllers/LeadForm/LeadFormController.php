<?php

namespace App\Http\Controllers\LeadForm;

use App\AccessLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class LeadFormController extends Controller
{
    public function __construct(Request $request)
    {
        $request->permission_page = '8-17';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
    }

    public function listOfLeadForm()
    {
        return view('Admin.LeadForm.listOfLeadForm');
    }

    public function leadDetails($id){
        $ListOfLeadFormDetails = DB::table('lead_form')->where('id',$id)->first();
        return view('Admin.LeadForm.LeadFormDetails')->with('ListOfLeadFormDetails', $ListOfLeadFormDetails);
    }

    public function list_of_leads_FormAjax(Request $request)
    {
        $start_date = $request->start_date . ' 00:00:00';
        $end_date = $request->end_date . ' 23:59:59';

        $ListOfLeadForm = DB::table('lead_form');

        if (!empty($start_date) && !empty($end_date)) {
            $ListOfLeadForm->whereBetween('created_at', [$start_date, $end_date]);
        }

        $ListOfLeadForm = $ListOfLeadForm->orderBy('created_at', 'DESC')->get();

        $dataJason = '';
        $dataJason .= '<table id="datatable3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Lead Name</th>
                                 <th>Email</th>
                                 <th>Phone Number</th>
                                 <th>Service</th>
                                 <th>Created At</th>';
        if (empty($permission_users) || in_array('8-10', $permission_users)) {
            $dataJason .= '<th>Action</th>';
        }
        $dataJason .= '</tr>
                       </thead>
                       <tbody>';

        if (!empty($ListOfLeadForm)) {
            foreach ($ListOfLeadForm as $val) {
                $dataJason .= '<tr>';
                $dataJason .= '<td>' . $val->id . '</td>';
                $dataJason .= '<td>' . $val->lead_fname . "  " .$val->lead_lname  . '</td>';
                $dataJason .= '<td>' . $val->lead_email . '</td>';
                $dataJason .= '<td>' . $val->lead_phone_number . '</td>';
                $dataJason .= '<td>' . $val->offer . '</td>';
                $dataJason .= '<td>' . $val->created_at . '</td>';

                if (empty($permission_users) || in_array('8-10', $permission_users)) {
                    $dataJason .= '<td>';
                    $dataJason .= '<a href="/LeadFormDetails/' . $val->id . '" class="on-default edit-row" data-toggle="tooltip" data-placement="top" title="" data-original-title="Details" data-trigger="hover" data-animation="false">
                                      <i class="mdi mdi-file-document-box font-18 vertical-middle m-r-10"></i>
                                  </a>';
                    $dataJason .= '</td>';
                }
                $dataJason .= '</tr>';
            }
        }

        $dataJason .= '  </tbody>
                            </table>';

        return $dataJason;
    }


    public function export_leadForm_data(Request $request)
    {
        //Lead Form
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $ListOfLeadForm = DB::table('lead_form');

        if (!empty($start_date) && !empty($end_date)) {
            $ListOfLeadForm->whereBetween('created_at', [$start_date, $end_date]);
        }

        $lead_data = $ListOfLeadForm->orderBy('created_at', 'DESC')->get();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => 1,
            'section_name' => "Export Form Leads",
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

        return (new FastExcel($lead_data))->download('leadsForm.csv', function ($lead) use($info_permission) {
            return [
                'Lead Id' => $lead->id,
                'First Name' => $lead->lead_fname,
                'Last Name' => $lead->lead_lname,
                'Email Address' => ($info_permission == 1 ? $lead->lead_email : ""),
                'Phone Number' => ($info_permission == 1 ? " " . $lead->lead_phone_number . " " : ""),
                'State' => $lead->state,
                'County' => $lead->county,
                'City' => $lead->city,
                'ZipCode' => " " . $lead->lead_zipcode . " ",
                'Address' => $lead->address,
                'Service' => $lead->offer,
                'Traffic Source' => $lead->traffic_source,
                'Trusted Form' => ($info_permission == 1 ? $lead->trusted_form : ""),
                'Jornaya Lead Id' => $lead->leadId,
            ];
        });
    }
}
