<?php

namespace App\Http\Controllers;

use App\AccessLog;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Services\AccessLog\AccessLogService;
use App\Services\Admin\AdminService;
use App\Services\Location\LocationService;
use App\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    protected LocationService $locationService;
    protected AdminService $AdminService;

    protected AccessLogService $AccessLogService;
    public function __construct(Request $request,LocationService $locationService,AdminService $AdminService,AccessLogService $AccessLogService)
    {
        $request->permission_page = '4-0';
        $this->middleware(['auth', 'AdminMiddleware', 'PermissionUsers']);
        $this->locationService = $locationService;
        $this->AdminService = $AdminService;
        $this->AccessLogService = $AccessLogService;
    }

    public function index()
    {
        $admins = $this->AdminService->getAllAdmin();

        return view('SuperAdmin.Admin.index',compact('admins'));
    }

    public function create()
    {
        $states = $this->locationService->getStates();
        return view('SuperAdmin.Admin.AdminForm')->with('states', $states);
    }

    public function store(StoreAdminRequest $request)
    {

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $zip_code_id = $this->locationService->storeAddress($validated);
            $user_id = $this->AdminService->storeAdmin($validated, $zip_code_id);
            $this->AccessLogService->createAccessLog($validated, "Admin", "Created", $user_id);

            DB::commit();

            return redirect('AdminManagment');

        } catch (\Exception $e) {
            DB::rollBack();

            // Optional: Log the error or return a message
            Log::error('Admin creation failed', ['error' => $e->getMessage()]);

        }
    }

    public function edit($id)
    {
        $states = State::All();
        $user_data = User::find($id);

        $zip_code_id = $user_data->zip_code_id;
        $zip_codes = DB::table('zip_codes')->where('zip_code_id', $zip_code_id)->get();

        $zip_code = $street = $city_id = '';
        foreach( $zip_codes as $item ){
            $zip_code = $item->zip_code;
            $street = $item->street_name;
            $city_id = $item->city_id;
        }

        $state_id = DB::table('cities')->where('city_id', $city_id) ->first('state_id');

        $listOfIds = array(
            'state_id'      => $state_id->state_id,
            'city_id'       => $city_id,
            'zip_code'      => $zip_code,
            'street'        => $street
        );

        return view('SuperAdmin.Admin.edit')
            ->with('states', $states)
            ->with('user_data', $user_data)
            ->with('listOfIds', $listOfIds)
            ->with('errormsg', '');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'owner' => 'required|string',
            'businessname' => 'required|string',
            'phonenumber' => 'required',
            'mobilenumber' => 'required',
            'state' => 'required',
            'city'=> 'required',
            'zipcode' => 'required',
            'streetname'=> 'required',
            'zip_code_id' => 'required',
            'user_account_type' => 'required',
            'user_privileges'
        ]);

        $user_privileges = "";
        if( !empty($request['user_privileges'])) {
            $user_privileges = json_encode($request['user_privileges']);
        }

        $user_details =   DB::table('users')->where('id', $id)->first();

        $imagename1 = $user_details->adminIcon1;
        if ($request->hasFile('adminIcon1')) {
            $image1 = $request->file('adminIcon1');
            $imagename1 = time().'-1.'.$image1->getClientOriginalExtension();
            $destinationPath1 = public_path('/images/salesDashboard');
            $image1->move($destinationPath1, $imagename1);
        }

        $imagename2 = $user_details->adminIcon2;
        if ($request->hasFile('adminIcon2')) {
            $image2 = $request->file('adminIcon2');
            $imagename2 = time().'-2.'.$image2->getClientOriginalExtension();
            $destinationPath2 = public_path('/images/salesDashboard');
            $image2->move($destinationPath2, $imagename2);
        }

        $imagename3 = $user_details->adminIcon3;
        if ($request->hasFile('adminIcon3')) {
            $image3 = $request->file('adminIcon3');
            $imagename3 = time().'-3.'.$image3->getClientOriginalExtension();
            $destinationPath3 = public_path('/images/salesDashboard');
            $image3->move($destinationPath3, $imagename3);
        }

        DB::table('zip_codes')
            ->where('zip_code_id', $request['zip_code_id'] )
            ->update([
                'zip_code' => $request['zipcode'],
                'street_name' => $request['streetname'],
                'city_id' => $request['city'],
                'state_id' => $request['state']
            ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'user_first_name' => $request['firstname'],
                'user_last_name' => $request['lastname'],
                'username' => ucwords($request['firstname'] . ' ' .$request['lastname']),
                'email' => $request['email'],
                'user_owner' => $request['owner'],
                'user_business_name' => $request['businessname'],
                'user_phone_number' => $request['phonenumber'],
                'user_mobile_number' => $request['mobilenumber'],
                'role_id' => $request['user_role'],
                'user_type' => $request['user_role'],
                'account_type' => $request['user_account_type'],
                'permission_users' => $user_privileges,
                'adminIcon1' => $imagename1,
                'adminIcon2' => $imagename2,
                'adminIcon3' => $imagename3
            ]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => ucwords($request['firstname'] . ' ' .$request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'Admin',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function destroy($id)
    {
        DB::table('users')->where('id', $id)
            ->update(['user_visibility'=>0]);


        $username = DB::table('users')->where('id', $id)->first('username');
        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => Auth::user()->username,
            'section_id' => $id,
            'section_name' => $username->username,
            'user_role' => Auth::user()->role_id,
            'section'   => 'Admin',
            'action'    => 'Deleted',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => ''
        ]);

        return redirect()->back();
    }
}
