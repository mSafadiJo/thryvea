<?php

namespace App\Http\Controllers\Ourpartners;

use App\AccessLog;
use App\Imports\ProjectsImport;
use App\Models\Ourpartner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;

class OurPartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */

    public function __construct()
    {
        $this->middleware(['auth', 'AdminMiddleware'])->except('sendPartners');
    }

    public function index(){
        $partners = DB::table('ourpartners')
            ->orderBy('created_at', 'desc')
            ->select(['partner', 'created_at', 'id'])
            ->paginate(10);

        return view('SuperAdmin.ourPartners.viewPartners', compact('partners'));
    }

    public function fetch_data(Request $request){
        if($request->ajax()) {
            $partners = DB::table('ourpartners')
                ->where(function ($query) use ($request){
                    $query->where("partner", "like", "%" . $request->get("query") . "%");
                })
                ->orderBy('created_at', 'DESC')
                ->select(['partner', 'created_at', 'id'])
                ->paginate(10);

            return view('Render.OurPartners.Our_Partners_Render', compact('partners'))->render();
        }
    }

    public function exportPartners(){
        $partners = DB::table('ourpartners')->get(['partner']);

        return (new FastExcel($partners))->download('OurPartners.csv', function ($partners) {
            return [
                'Partner' => $partners->partner,
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SuperAdmin.ourPartners.addPartners');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        $partner = $request['partner'];
        $partnerArea = $request['partnerArea'];
        $comp = explode("\r\n", $partnerArea);
        if(strlen($partner) > 0) {
            if (!(Ourpartner::where('partner', $partner)->exists())){
                $ourpartner = new Ourpartner([
                    'partner'    =>  $partner,
                ]);
                $ourpartner->save();
            }
        }
        if(strlen($partnerArea) > 0){
            foreach ($comp as $item){
                if (!(Ourpartner::where('partner', $item)->exists())){
                    $ourpartner = new Ourpartner([
                        'partner'    =>  $item,
                    ]);
                    $ourpartner->save();
                }
            }
        }
        if($request->hasFile('file')) {
            Excel::import(new ProjectsImport(), \request()->file('file'));
        }

        return redirect()->route('OurPartners.index')->with('success', 'Data Added');
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $partner = Ourpartner::find($id);
        return view('SuperAdmin.ourPartners.editPartner', compact('partner', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'partner' => 'required',
        ]);
        $partner = Ourpartner::find($id);
        $partner->partner = $request->get('partner');
        $partner->save();
        return redirect()->route('OurPartners.index')->with('success', 'Data Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $partner = Ourpartner::find($id);
        $partner->delete();
        return redirect()->route('OurPartners.index')->with('success', 'Data Deleted');
    }

    public function sendPartners(){
        $ourPartners = Ourpartner::get('partner');
        return $ourPartners;
    }
}
