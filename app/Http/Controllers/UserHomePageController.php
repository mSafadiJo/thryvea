<?php

namespace App\Http\Controllers;

use App\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\State;

class UserHomePageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'buyersCustomer']);
    }

    public function userProfileShow(){
        $states = State::All();
        $zip_code_id = Auth::user()->zip_code_id;
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

        return view('Buyers.UserProfile')->with('states', $states)->with('listOfIds', $listOfIds)->with('errormsg', '');
    }

    public function updateUser(Request $request){
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
            'zip_code_id' => 'required'
        ]);

        $updateAddress = DB::table('zip_codes')
            ->where('zip_code_id', $request['zip_code_id'] )
            ->update([
                'zip_code' => $request['zipcode'],
                'street_name' => $request['streetname'],
                'city_id' => $request['city'] ]);

        $updateUser = DB::table('users')
            ->where('id', Auth::user()->id)
            ->update([
                'user_first_name' => $request['firstname'],
                'user_last_name' => $request['lastname'],
                'username' => ucwords($request['firstname'] . ' ' .$request['lastname']),
                'email' => $request['email'],
                'user_owner' => $request['owner'],
                'user_business_name' => $request['businessname'],
                'user_phone_number' => $request['phonenumber'],
                'user_mobile_number' => $request['mobilenumber']]);

        AccessLog::create([
            'user_id' => Auth::user()->id,
            'user_name' => ucwords($request['firstname'] . ' ' .$request['lastname']),
            'section_id' => Auth::user()->id,
            'section_name' => ucwords($request['firstname'] . ' ' .$request['lastname']),
            'user_role' => Auth::user()->role_id,
            'section'   => 'Buyers',
            'action'    => 'Updated',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($request->all())
        ]);

        return redirect()->back();
    }

    public function updatePasswprdUser(Request $request){
        $this->validate($request, [
            'Oldpassword' => 'required|string',
            'password'=> 'required|string|min:8|confirmed'
        ]);

        if(Hash::check($request->Oldpassword, Auth::user()->password)) {

            if( $request->Oldpassword == $request->password ){
                $states = State::All();
                $zip_code_id = Auth::user()->zip_code_id;
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

                return redirect()->back()->with('states', $states)->with('listOfIds', $listOfIds)->with('errormsg', 'New password cannot be the same as the old password');
            } else {
                $updateUser = DB::table('users')
                    ->where('id', Auth::user()->id)
                    ->update(['password' => Hash::make($request['password'])]);

                $username = DB::table('users')->where('id', Auth::user()->id)->first('username');
                AccessLog::create([
                    'user_id' => Auth::user()->id,
                    'user_name' => $username->username,
                    'section_id' => Auth::user()->id,
                    'section_name' => $username->username,
                    'user_role' => Auth::user()->role_id,
                    'section'   => 'Buyers',
                    'action'    => 'Updated',
                    'ip_address' => request()->ip(),
                    'location' => json_encode(\Location::get(request()->ip())),
                    'request_method' => json_encode($request->all())
                ]);

                $states = State::All();
                $zip_code_id = Auth::user()->zip_code_id;
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

                return redirect()->back()->with('states', $states)->with('listOfIds', $listOfIds)->with('errormsg', 'Success');
            }
        } else {
            $states = State::All();
            $zip_code_id = Auth::user()->zip_code_id;
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

            return redirect()->back()->with('states', $states)->with('listOfIds', $listOfIds)->with('errormsg', 'Password is Incorrect');
        }
    }
}
