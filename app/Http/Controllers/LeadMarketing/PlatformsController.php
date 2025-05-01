<?php

namespace App\Http\Controllers\LeadMarketing;

use App\AccessLog;
use App\MarketingPlatform;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlatformsController extends Controller
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

        $platforms = new MarketingPlatform();

//        if ($request->type != null){
//            $var = explode(",", $request->type);
//            $col_name = $var[0];
//            $query = $var[1];
//            $platforms = $platforms->orderBy($col_name, $query);
//            $sort_type = $request->type;
//        }
//        if ($request->search != null){
//            $sort_search = $request->search;
//            $platforms = $platforms->where('name', 'like', '%'.$sort_search.'%');
//            $sort_search = $request->search;
//        }

//        $platforms = $platforms->orderBy('created_at', 'DESC')->paginate(15);

        $platforms = $platforms->orderBy('created_at', 'DESC')->get();

//        return view('LeadMarketing.platforms.index', compact('platforms', 'col_name', 'query', 'sort_search', 'sort_type'));
        return view('LeadMarketing.platforms.index', compact('platforms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('LeadMarketing.platforms.create');
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
            'name' => ['required', 'string', 'max:255']
        ]);

        $platform = new MarketingPlatform();

        $platform->name = $request->name;

        $platform->save();
        $platform_id = DB::getPdo()->lastInsertId();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $platform_id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'MarketingPlatform',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('Platforms.index');
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
        $platform = MarketingPlatform::find($id);

        return view('LeadMarketing.platforms.edit', compact('platform'));
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
            'name' => ['required', 'string', 'max:255']
        ]);

        $platform = MarketingPlatform::find($id);

        $platform->name = $request->name;

        $platform->save();

        //Access LOG
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $request->name,
            'user_role' => Auth::user()->role_id,
            'section'   => 'MarketingPlatform',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('Platforms.index');
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
}
