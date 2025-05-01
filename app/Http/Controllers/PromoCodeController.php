<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\promoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromoCodeController extends Controller
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

    public function index()
    {
        $promoCodes = promoCode::All();

        return view('SuperAdmin.promoCode.index')
            ->with('promoCodes', $promoCodes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SuperAdmin.promoCode.create');
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
            'promo_code' => ['required', 'string', 'max:255', 'unique:promo_codes'],
            'value' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required']
        ]);

        //Save promoCode
        $promoCode = new promoCode();

        $promoCode->promo_code = $request['promo_code'];
        $promoCode->promo_code_value = $request['value'];
        $promoCode->promo_code_from_date = $request['start_date'];
        $promoCode->promo_code_to_date = $request['end_date'];

        $promoCode->save();
        $promoCode_id = DB::getPdo()->lastInsertId();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $promoCode_id,
            'section_name' => 'Promo Code ' . $request['promo_code'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'PromoCode',
            'action'    => 'Created',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('PromoCode.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promo_code = promoCode::where('promo_code_id', $id)->first();

        return view('SuperAdmin.promoCode.edit')
            ->with('promo_code', $promo_code);
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
            'promo_code' => ['required', 'string', 'max:255'],
            'value' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required']
        ]);

        promoCode::where('promo_code_id', $id)
            ->update([
                "promo_code" => $request['promo_code'],
                "promo_code_value" => $request['value'],
                "promo_code_from_date" => $request['start_date'],
                "promo_code_to_date" => $request['end_date']
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => 'Promo Code ' . $request['promo_code'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'PromoCode',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->route('PromoCode.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promo_code_name = promoCode::where('promo_code_id', $id)->first(['promo_code']);

        promoCode::where('promo_code_id', $id)
            ->delete();

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => 'Promo Code ' . $promo_code_name['promo_code'],
            'user_role' => Auth::user()->role_id,
            'section'   => 'PromoCode',
            'action'    => 'Delete',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ''
        ]);

        return redirect()->route('PromoCode.index');
    }
}
