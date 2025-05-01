<?php

namespace App\Http\Controllers\Auth;

use App\AccessLog;
use App\promoCode;
use App\TotalAmount;
use App\Transaction;
use App\User;
use App\Http\Controllers\Controller;
use App\Zip_code;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Rules\GoogleRecaptcha;
use Slack;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'firstname' => ['required', 'string', 'min:3', 'max:15'],
            'lastname' => ['required', 'string', 'min:3', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'owner' => ['required', 'string', 'max:255'],
            'businessname' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'string', 'min:8', 'max:12'],
            'mobilenumber' => ['required', 'string', 'min:8', 'max:12'],
            'state' => ['required'],
            'zipcode' => ['required'],
            'city' => ['required', 'string', 'max:255'],
            'streetname' => ['required', 'string', 'max:255'],
//            'g-recaptcha-response' => ['required', new GoogleRecaptcha],
            'promocode'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        //Save ZipCode
        $zip_code = new Zip_code();
        $zip_code->city_id = $data['city'];
        $zip_code->zip_code = $data['zipcode'];
        $zip_code->street_name = $data['streetname'];

        $zip_code->save();
        $zip_code_id = DB::getPdo()->lastInsertId();

        $user = User::create([
            'user_first_name' => $data['firstname'],
            'user_last_name' => $data['lastname'],
            'username' => ucwords($data['firstname'] . " " . $data['lastname']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_owner' => $data['owner'],
            'zip_code_id' => $zip_code_id,
            'user_business_name' => $data['businessname'],
            'user_phone_number' => $data['phonenumber'],
            'user_mobile_number' => $data['mobilenumber'],
            'hash_phone_number' => md5($data['phonenumber']),
            'hash_mobile_number' => md5($data['mobilenumber']),
            'role_id' => 3,
            'user_type'=>3,
            'num_of_login' => 1,
            'user_promocode' => $data['promocode'],
            'last_login' =>  date('Y-m-d H:i:s')
        ]);

        $user_id = DB::getPdo()->lastInsertId();

        $promo_code_value = promoCode::where('promo_code', $data['promocode'])
            ->where('promo_code_from_date', '<=', date('Y-m-d'))
            ->where('promo_code_to_date', '>=', date('Y-m-d'))
            ->first();

        if( !empty($promo_code_value) ){

            $promo_code_value = $promo_code_value->promo_code_value;

            $transaction = new Transaction();

            $transaction->user_id =$user_id;
            $transaction->transactions_value = $promo_code_value;
            $transaction->transactions_comments = 'Promo Codes Credit';

            $transaction->save();

            $addtotalAmmount = new TotalAmount();

            $addtotalAmmount->user_id = $user_id;
            $addtotalAmmount->total_amounts_value = $promo_code_value;

            $addtotalAmmount->save();
        }

        AccessLog::create([
            'user_id' => $user_id,
            'user_name' => ucwords($data['firstname'] . " " . $data['lastname']),
            'section_id' => $user_id,
            'section_name' => ucwords($data['firstname'] . " " . $data['lastname']),
            'user_role' => 3,
            'section'   => 'Buyers',
            'action'    => 'Registered',
            'ip_address' => request()->ip(),
            'location' => json_encode(\Location::get(request()->ip())),
            'request_method' => json_encode($data)
        ]);

        $email = $data['email'];
        //Slack::send("A new user was registered, email:$email :)");

        return $user;
    }
}
