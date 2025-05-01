<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRequest;
use App\Services\AccessLog\AccessLogService;
use App\Services\Location\LocationService;
use App\Services\User\UserUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('Admin.home');
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
                $this->userUpdateService->createAccessLog($data);

                return redirect()->back()->with('success', 'Password updated successfully');
            }
        } else {
            return redirect()->back()->withErrors('Password is Incorrect');
        }
    }
}
