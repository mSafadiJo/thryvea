<?php

namespace App\Http\Controllers;

use App\CampaignsLeadsUsers;
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

    public function index()
    {
        $todayStart = now()->startOfDay()->format('Y-m-d H:i:s');
        $todayEnd = now()->endOfDay()->format('Y-m-d H:i:s');

        $monthStart = now()->startOfMonth()->format('Y-m-d H:i:s');
        $monthEnd = now()->endOfMonth()->format('Y-m-d H:i:s');

        // Lead counts with single query - FIX: use lc.created_at
        $leadStats = DB::table('leads_customers as lc')
            ->selectRaw('
            COUNT(*) as total,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NOT NULL AND clu.is_returned = 0 THEN lc.lead_id END) as sold,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NULL THEN lc.lead_id END) as unsold
        ')
            ->leftJoin('campaigns_leads_users as clu', function ($join) {
                $join->on('clu.lead_id', '=', 'lc.lead_id')
                    ->where('clu.is_returned', 0);
            })
            ->where('lc.is_duplicate_lead', '<>', 1)
            ->whereNotIn('lc.lead_fname', ['test', 'testing', 'Test'])
            ->whereNotIn('lc.lead_lname', ['test', 'testing', 'Test'])
            ->where('lc.is_test', 0)
            ->whereBetween('lc.created_at', [$todayStart, $todayEnd])  // <-- FIX: lc.created_at
            ->first();

        $totalLeads = $leadStats->total;
        $soldLeads = $leadStats->sold;
        $unsoldLeads = $leadStats->unsold;

        // Financial data - FIX: use lc.created_at
        $totalPurchasingPrice = DB::table('leads_customers as lc')  // <-- FIX: add alias
        ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'lc.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('lc.response_data', "Lead Accepted")  // <-- FIX: lc.response_data
            ->whereBetween('lc.created_at', [$monthStart, $monthEnd])  // <-- FIX: lc.created_at
            ->where('lc.is_duplicate_lead', '<>', 1)  // <-- FIX: lc.is_duplicate_lead
            ->whereNotIn('lc.lead_fname', ['test', 'testing', 'Test'])  // <-- FIX: lc.lead_fname
            ->whereNotIn('lc.lead_lname', ['test', 'testing', 'Test'])  // <-- FIX: lc.lead_lname
            ->where('lc.is_test', 0)  // <-- FIX: lc.is_test
            ->sum('lc.ping_price');  // <-- FIX: lc.ping_price

        $totalSellingPrice = CampaignsLeadsUsers::whereIn('campaigns_leads_users_type_bid', ['Exclusive', 'Shared'])
            ->whereBetween('date', [$monthStart, $monthEnd])  // This is 'date' not 'created_at', so no conflict
            ->where('is_returned', 0)
            ->sum('campaigns_leads_users_bid');

        // Calculations
        $profit = $totalSellingPrice - $totalPurchasingPrice;
        $marginPercent = $totalSellingPrice > 0 ? (($profit / $totalSellingPrice) * 100) : 0;
        $marginRingPercent = min($marginPercent, 100);

        $maxProfitValue = 50000;
        $profitPercent = $maxProfitValue > 0 ? min(($profit / $maxProfitValue) * 100, 100) : 0;

        $circumference = 364.4;
        $profitOffset = $circumference - ($circumference * $profitPercent / 100);

        return view('Admin.home', compact(
            'totalLeads', 'soldLeads', 'unsoldLeads',
            'totalSellingPrice', 'totalPurchasingPrice',
            'marginPercent', 'marginRingPercent',
            'profit', 'profitOffset', 'circumference', 'maxProfitValue'
        ));
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
