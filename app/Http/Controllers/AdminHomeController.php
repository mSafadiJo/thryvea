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

        $yearStart = now()->startOfYear()->format('Y-m-d H:i:s');
        $yearEnd = now()->endOfYear()->format('Y-m-d H:i:s');

        // ========== TODAY'S STATS (for rings) ==========
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
            ->whereBetween('lc.created_at', [$todayStart, $todayEnd])
            ->first();

        $totalLeads = $leadStats->total;
        $soldLeads = $leadStats->sold;
        $unsoldLeads = $leadStats->unsold;

        // ========== MONTHLY FINANCIAL DATA (for charts) ==========
        $monthlyProfit = DB::select("
        SELECT
            DATE_FORMAT(clu.date, '%Y-%m') as month,
            MONTHNAME(clu.date) as month_name,
            MONTH(clu.date) as month_num,
            COALESCE(SUM(clu.campaigns_leads_users_bid), 0) as selling_price,
            COALESCE(SUM(lc.ping_price), 0) as purchasing_price,
            COALESCE(SUM(clu.campaigns_leads_users_bid), 0) - COALESCE(SUM(lc.ping_price), 0) as profit
        FROM campaigns_leads_users clu
        LEFT JOIN leads_customers lc ON lc.lead_id = clu.lead_id
            AND lc.response_data = 'Lead Accepted'
            AND lc.is_duplicate_lead <> 1
            AND lc.lead_fname NOT IN ('test', 'testing', 'Test')
            AND lc.lead_lname NOT IN ('test', 'testing', 'Test')
            AND lc.is_test = 0
        WHERE clu.campaigns_leads_users_type_bid IN ('Exclusive', 'Shared')
        AND clu.is_returned = 0
        AND clu.date BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(clu.date, '%Y-%m'), MONTHNAME(clu.date), MONTH(clu.date)
        ORDER BY month
    ", [$yearStart, $yearEnd]);

        // ========== MONTHLY LEAD COUNTS (for charts) ==========
        $monthlyLeads = DB::select("
        SELECT
            DATE_FORMAT(lc.created_at, '%Y-%m') as month,
            MONTHNAME(lc.created_at) as month_name,
            MONTH(lc.created_at) as month_num,
            COUNT(*) as total_leads,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NOT NULL AND clu.is_returned = 0 THEN lc.lead_id END) as sold_leads,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NULL THEN lc.lead_id END) as unsold_leads
        FROM leads_customers lc
        LEFT JOIN campaigns_leads_users clu ON clu.lead_id = lc.lead_id AND clu.is_returned = 0
        WHERE lc.is_duplicate_lead <> 1
        AND lc.lead_fname NOT IN ('test', 'testing', 'Test')
        AND lc.lead_lname NOT IN ('test', 'testing', 'Test')
        AND lc.is_test = 0
        AND lc.created_at BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(lc.created_at, '%Y-%m'), MONTHNAME(lc.created_at), MONTH(lc.created_at)
        ORDER BY month
    ", [$yearStart, $yearEnd]);

        // ========== FORMAT FOR CHARTS ==========
        $months = [];
        $profitData = [];
        $soldLeadsData = [];
        $unsoldLeadsData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('M', mktime(0, 0, 0, $i, 1));
            $months[] = $monthName;
            $profitData[$i] = 0;
            $soldLeadsData[$i] = 0;
            $unsoldLeadsData[$i] = 0;
        }

        foreach ($monthlyProfit as $row) {
            $profitData[(int)$row->month_num] = (float)$row->profit;
        }

        foreach ($monthlyLeads as $row) {
            $soldLeadsData[(int)$row->month_num] = (int)$row->sold_leads;
            $unsoldLeadsData[(int)$row->month_num] = (int)$row->unsold_leads;
        }

        // ========== TODAY'S PROFIT ==========
        $todaySellingPrice = CampaignsLeadsUsers::whereIn('campaigns_leads_users_type_bid', ['Exclusive', 'Shared'])
            ->whereBetween('date', [$todayStart, $todayEnd])
            ->where('is_returned', 0)
            ->sum('campaigns_leads_users_bid');

        $todayPurchasingPrice = DB::table('leads_customers as lc')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'lc.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('lc.response_data', "Lead Accepted")
            ->whereBetween('lc.created_at', [$todayStart, $todayEnd])
            ->where('lc.is_duplicate_lead', '<>', 1)
            ->whereNotIn('lc.lead_fname', ['test', 'testing', 'Test'])
            ->whereNotIn('lc.lead_lname', ['test', 'testing', 'Test'])
            ->where('lc.is_test', 0)
            ->sum('lc.ping_price');

        $profitToday = $todaySellingPrice - $todayPurchasingPrice;

        // ========== MONTH'S PROFIT (for rings) ==========
        $monthSellingPrice = CampaignsLeadsUsers::whereIn('campaigns_leads_users_type_bid', ['Exclusive', 'Shared'])
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->where('is_returned', 0)
            ->sum('campaigns_leads_users_bid');

        $monthPurchasingPrice = DB::table('leads_customers as lc')
            ->join('campaigns AS camp_seller', 'camp_seller.vendor_id', '=', 'lc.vendor_id')
            ->join('users AS Seller', 'Seller.id', '=', 'camp_seller.user_id')
            ->where('lc.response_data', "Lead Accepted")
            ->whereBetween('lc.created_at', [$monthStart, $monthEnd])
            ->where('lc.is_duplicate_lead', '<>', 1)
            ->whereNotIn('lc.lead_fname', ['test', 'testing', 'Test'])
            ->whereNotIn('lc.lead_lname', ['test', 'testing', 'Test'])
            ->where('lc.is_test', 0)
            ->sum('lc.ping_price');

        $profitMonth = $monthSellingPrice - $monthPurchasingPrice;

        // ========== TODAY'S RING CALCULATIONS ==========
        $marginPercentToday = $todaySellingPrice > 0 ? (($profitToday / $todaySellingPrice) * 100) : 0;
        $marginRingPercentToday = min($marginPercentToday, 100);

        $maxProfitValueToday = 50000;
        $profitPercentToday = $maxProfitValueToday > 0 ? min(($profitToday / $maxProfitValueToday) * 100, 100) : 0;

        $circumference = 364.4;
        $profitOffsetToday = $circumference - ($circumference * $profitPercentToday / 100);

        // ========== MONTH'S RING CALCULATIONS ==========
        $marginPercentMonth = $monthSellingPrice > 0 ? (($profitMonth / $monthSellingPrice) * 100) : 0;
        $marginRingPercentMonth = min($marginPercentMonth, 100);

        $maxProfitValueMonth = 50000 * 30;
        $profitPercentMonth = $maxProfitValueMonth > 0 ? min(($profitMonth / $maxProfitValueMonth) * 100, 100) : 0;
        $profitOffsetMonth = $circumference - ($circumference * $profitPercentMonth / 100);

        return view('Admin.home', compact(
            'totalLeads', 'soldLeads', 'unsoldLeads',
            'profitToday', 'profitMonth',
            'todaySellingPrice', 'todayPurchasingPrice',
            'monthSellingPrice', 'monthPurchasingPrice',
            'marginPercentToday', 'marginRingPercentToday',
            'marginPercentMonth', 'marginRingPercentMonth',
            'profitOffsetToday', 'profitOffsetMonth',
            'circumference', 'maxProfitValueToday', 'maxProfitValueMonth',
            'months', 'profitData', 'soldLeadsData', 'unsoldLeadsData'
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
