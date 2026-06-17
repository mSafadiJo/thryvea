<?php

namespace App\Http\Controllers;

use App\CampaignsLeadsUsers;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\AccessLog\AccessLogService;
use App\Services\Location\LocationService;
use App\Services\User\UserUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        // ========== TODAY'S STATS (unchanged) ==========
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

        // ========== MONTHLY DATA: Cache with fallback to full query ==========
        $cacheKey = 'dashboard_monthly_data_' . now()->year;
        $cachedData = Cache::get($cacheKey);

        // If cache is empty, compute ALL months and store
        if (empty($cachedData)) {
            $cachedData = $this->computeAllMonthsData($yearStart, $yearEnd);
            Cache::put($cacheKey, $cachedData, now()->endOfYear());
        }
        // If cache exists but current month missing, update only current month
        elseif (!isset($cachedData[now()->format('Y-m')])) {
            $currentMonthData = $this->computeCurrentMonthData();
            $cachedData = array_merge($cachedData, $currentMonthData);
            Cache::put($cacheKey, $cachedData, now()->endOfYear());
        }

        // Build arrays for charts
        $months = [];
        $profitData = [];
        $soldLeadsData = [];
        $unsoldLeadsData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthKey = now()->year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $months[] = date('M', mktime(0, 0, 0, $i, 1));

            $profitData[$i] = $cachedData[$monthKey]['profit'] ?? 0;
            $soldLeadsData[$i] = $cachedData[$monthKey]['sold_leads'] ?? 0;
            $unsoldLeadsData[$i] = $cachedData[$monthKey]['unsold_leads'] ?? 0;
        }

        // ========== TODAY'S & MONTH'S PROFIT (real-time) ==========
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

        // ========== RING CALCULATIONS (unchanged) ==========
        $marginPercentToday = $todaySellingPrice > 0 ? (($profitToday / $todaySellingPrice) * 100) : 0;
        $marginRingPercentToday = min($marginPercentToday, 100);
        $maxProfitValueToday = 50000;
        $profitPercentToday = $maxProfitValueToday > 0 ? min(($profitToday / $maxProfitValueToday) * 100, 100) : 0;
        $circumference = 364.4;
        $profitOffsetToday = $circumference - ($circumference * $profitPercentToday / 100);

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

    /**
     * Compute ALL months data (used when cache is empty)
     */
    private function computeAllMonthsData(string $yearStart, string $yearEnd): array
    {
        // Get profit for all months
        $monthlyProfit = DB::select("
        SELECT
            DATE_FORMAT(clu.date, '%Y-%m') as month,
            MONTH(clu.date) as month_num,
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
        GROUP BY DATE_FORMAT(clu.date, '%Y-%m'), MONTH(clu.date)
        ORDER BY month
    ", [$yearStart, $yearEnd]);

        // Get lead counts for all months
        $monthlyLeads = DB::select("
        SELECT
            DATE_FORMAT(lc.created_at, '%Y-%m') as month,
            MONTH(lc.created_at) as month_num,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NOT NULL AND clu.is_returned = 0 THEN lc.lead_id END) as sold_leads,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NULL THEN lc.lead_id END) as unsold_leads
        FROM leads_customers lc
        LEFT JOIN campaigns_leads_users clu ON clu.lead_id = lc.lead_id AND clu.is_returned = 0
        WHERE lc.is_duplicate_lead <> 1
        AND lc.lead_fname NOT IN ('test', 'testing', 'Test')
        AND lc.lead_lname NOT IN ('test', 'testing', 'Test')
        AND lc.is_test = 0
        AND lc.created_at BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(lc.created_at, '%Y-%m'), MONTH(lc.created_at)
        ORDER BY month
    ", [$yearStart, $yearEnd]);

        // Build cache structure
        $cachedData = [];
        foreach ($monthlyProfit as $row) {
            $cachedData[$row->month] = [
                'profit' => (float) $row->profit,
                'sold_leads' => 0,
                'unsold_leads' => 0,
            ];
        }
        foreach ($monthlyLeads as $row) {
            if (!isset($cachedData[$row->month])) {
                $cachedData[$row->month] = ['profit' => 0, 'sold_leads' => 0, 'unsold_leads' => 0];
            }
            $cachedData[$row->month]['sold_leads'] = (int) $row->sold_leads;
            $cachedData[$row->month]['unsold_leads'] = (int) $row->unsold_leads;
        }

        return $cachedData;
    }

    /**
     * Compute data for current month only (used for updates)
     */
    private function computeCurrentMonthData(): array
    {
        $currentMonthStart = now()->startOfMonth()->format('Y-m-d H:i:s');
        $currentMonthEnd = now()->endOfMonth()->format('Y-m-d H:i:s');
        $currentMonthKey = now()->format('Y-m');

        $profitRow = DB::selectOne("
        SELECT
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
    ", [$currentMonthStart, $currentMonthEnd]);

        $leadsRow = DB::selectOne("
        SELECT
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NOT NULL AND clu.is_returned = 0 THEN lc.lead_id END) as sold_leads,
            COUNT(DISTINCT CASE WHEN clu.lead_id IS NULL THEN lc.lead_id END) as unsold_leads
        FROM leads_customers lc
        LEFT JOIN campaigns_leads_users clu ON clu.lead_id = lc.lead_id AND clu.is_returned = 0
        WHERE lc.is_duplicate_lead <> 1
        AND lc.lead_fname NOT IN ('test', 'testing', 'Test')
        AND lc.lead_lname NOT IN ('test', 'testing', 'Test')
        AND lc.is_test = 0
        AND lc.created_at BETWEEN ? AND ?
    ", [$currentMonthStart, $currentMonthEnd]);

        return [
            $currentMonthKey => [
                'profit' => (float) ($profitRow->profit ?? 0),
                'sold_leads' => (int) ($leadsRow->sold_leads ?? 0),
                'unsold_leads' => (int) ($leadsRow->unsold_leads ?? 0),
            ]
        ];
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
