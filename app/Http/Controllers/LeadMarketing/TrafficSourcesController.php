<?php

namespace App\Http\Controllers\LeadMarketing;

use App\AccessLog;
use App\LeadTrafficSources;
use App\MarketingPlatform;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrafficSourcesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware']);
    }

    public function index(Request $request)
    {
//        $col_name = null;
//        $query = null;
//        $sort_search = null;
//        $sort_type = null;

        $traffic_sources = LeadTrafficSources::join('marketing_platforms', 'marketing_platforms.id', '=', 'lead_traffic_sources.marketing_platform_id');

//        if ($request->type != null){
//            $var = explode(",", $request->type);
//            $col_name = $var[0];
//            $query = $var[1];
//            $traffic_sources = $traffic_sources->orderBy($col_name, $query);
//            $sort_type = $request->type;
//        }
//        if ($request->search != null){
//            $sort_search = $request->search;
//            $traffic_sources = $traffic_sources->where(function ($query) use($sort_search) {
//                $query->where('marketing_platforms.name', 'like', '%'.$sort_search.'%');
//                $query->OrWhere('lead_traffic_sources.name', 'like', '%'.$sort_search.'%');
//            });;
//            $sort_search = $request->search;
//        }

//        $traffic_sources = $traffic_sources->orderBy('created_at', 'DESC')
//            ->select([
//                'lead_traffic_sources.*', 'marketing_platforms.name AS platform_name'
//            ])->paginate(15);

        $traffic_sources = $traffic_sources->orderBy('created_at', 'DESC')
            ->get([
                'lead_traffic_sources.*', 'marketing_platforms.name AS platform_name'
            ]);

//        return view('LeadMarketing.traffic_sources.index', compact('traffic_sources', 'col_name', 'query', 'sort_search', 'sort_type'));
        return view('LeadMarketing.traffic_sources.index', compact('traffic_sources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $platforms = MarketingPlatform::All();
        return view('LeadMarketing.traffic_sources.create', compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'marketing_platform_id' => ['required']
        ]);

        $traffic_source = new LeadTrafficSources();

        $traffic_source->name = $request->name;
        $traffic_source->marketing_platform_id = $request->marketing_platform_id;

        $traffic_source->save();
        $traffic_source_id = DB::getPdo()->lastInsertId();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $traffic_source_id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'MarketingTrafficSources',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('TrafficSources.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $traffic_source = LeadTrafficSources::find($id);
        $platforms = MarketingPlatform::All();

        return view('LeadMarketing.traffic_sources.edit', compact('traffic_source', 'platforms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'marketing_platform_id' => ['required']
        ]);

        $traffic_source = LeadTrafficSources::find($id);

        $traffic_source->name = $request->name;
        $traffic_source->marketing_platform_id = $request->marketing_platform_id;

        $traffic_source->save();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'MarketingTrafficSources',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('TrafficSources.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ts_lead_cost(){
        $traffic_source = LeadTrafficSources::All();
        return view('LeadMarketing.traffic_sources.lead_cost', compact('traffic_source'));
    }

    public function ts_lead_cost_save(Request $request){
        $this->validate($request, [
            'ts_name' => ['required', 'string', 'max:255'],
            'lead_cost' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required']
        ]);

        $from_date = $request->start_date . ' 00:00:00';
        $to_date = $request->end_date . ' 23:59:59';

        $newStr = explode("-", $request->ts_name);
        $ts_id =  $newStr[0];
        $ts_name = $newStr[1];

        DB::table('leads_customers')
            ->whereRaw('LOWER(google_ts) = ?', [strtolower($ts_name)])
            ->where('created_at', ">=", $from_date)
            ->where('created_at', "<=", $to_date)
            ->update(['ping_price' => $request->lead_cost]);

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $ts_id,
            'section_name' => $ts_name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'LeadCostByTS',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        $message = "Lead Cost has been added successfully.";
        return redirect()->back()->with('message', $message);
    }

}
