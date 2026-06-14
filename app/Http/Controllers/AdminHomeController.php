<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Services\AccessLog\AccessLogService;
use App\Services\Location\LocationService;
use App\Services\User\UserUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;



class AdminHomeController extends Controller
{

    protected LocationService $locationService;
    protected UserUpdateService $userUpdateService;
    protected AccessLogService $AccessLogService;

    public function __construct(LocationService $locationService,UserUpdateService $userUpdateService,AccessLogService $AccessLogService)
    {
        $this->middleware(['auth', 'AdminMiddleware']);
        $this->locationService = $locationService;
        $this->userUpdateService = $userUpdateService;
        $this->AccessLogService = $AccessLogService;
    }

    public function index(){
        $todayStart = date('Y-m-d') . ' 00:00:00';
        $todayEnd = date('Y-m-d') . ' 23:59:59';

// Common filters
        $baseConditions = function ($query) use ($todayStart, $todayEnd) {
            $query->where('is_duplicate_lead', "<>", 1)
                ->where('lead_fname', '!=', "test")
                ->where('lead_lname', '!=', "test")
                ->where('lead_fname', '!=', "testing")
                ->where('lead_lname', '!=', "testing")
                ->where('lead_fname', '!=', "Test")
                ->where('lead_lname', '!=', "Test")
                ->where('is_test', 0)
                ->whereBetween('created_at', [$todayStart, $todayEnd]);
        };

// 1. Total leads (simplest, uses index on created_at)
        $totalLeads = DB::table('leads_customers')
            ->where($baseConditions)
            ->count();

// 2. Sold leads (EXISTS is efficient with proper indexes)
        $soldLeads = DB::table('leads_customers')
            ->where($baseConditions)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('campaigns_leads_users')
                    ->whereColumn('campaigns_leads_users.lead_id', 'leads_customers.lead_id')
                    ->where('is_returned', 0);
            })
            ->count();

// 3. Unsold leads
        $unsoldLeads = DB::table('leads_customers')
            ->where($baseConditions)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('campaigns_leads_users')
                    ->whereColumn('campaigns_leads_users.lead_id', 'leads_customers.lead_id');
            })
            ->count();

        return view('Admin.home', compact('totalLeads', 'soldLeads', 'unsoldLeads'));
    }

    public function AdminProfileShow(){

        $states = $this->locationService->getStates();
        $zipData = $this->locationService->getZipCodeData(Auth::user()->zip_code_id);
        $cities = $this->locationService->getCities();

        $city = $cities->firstWhere('city_id', $zipData['city_id']);
        $state_id = $city?->state_id ?? null;

        return view('Admin.adminProfile', [
            'states' => $states,
            'listOfIds' => [
                'state_id' => $state_id,
                'city_id' => $zipData['city_id'],
                'zip_code' => $zipData['zip_code'],
                'street' => $zipData['street'],
            ],
            'errormsg' => '',
        ]);
   }

    public function updateUser(UpdateUserRequest $request)
    {
        // Validate input data
        $validated = $request->validated();

        // Update Address, User, and Other Related Information
        $this->locationService->updateAddress($validated);
        $this->userUpdateService->updateUser($validated);
        $this->userUpdateService->updateBuyersClaim($validated);
        $this->userUpdateService->updatePaymentTypeAdmin($validated);

        // Create an access log entry
        $this->AccessLogService->createAccessLog($validated,$section="Admin",$action="Update");

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function updatePasswprdUser(Request $request){

        $this->validate($request, [
            'Oldpassword' => 'required|string',
            'password'=> 'required|string|min:8|confirmed'
        ]);

        if(Hash::check($request->Oldpassword, Auth::user()->password)) {
            if( $request->Oldpassword == $request->password ){
                return redirect()->back()->withErrors('New password cannot be the same as the old password');
            } else {
                $this->userUpdateService->updateUserPassword($request->password);
                $data=[
                    'username' => Auth::user()->username,
                    'firstname'=> Auth::user()->user_first_name,
                    'lastname'=> Auth::user()->user_last_name,
                ];
                // Create an access log entry
                $this->AccessLogService->createAccessLog($data,$section="Admin",$action="Update");

                return redirect()->back()->with('success', 'Password updated successfully');
            }
        } else {
            return redirect()->back()->withErrors('Password is Incorrect');
        }
    }
}
