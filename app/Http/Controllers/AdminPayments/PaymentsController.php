<?php

namespace App\Http\Controllers\AdminPayments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StripePaymentController;
use App\Payment;
use App\User;
use App\ZipCodesList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function add(Request $request){
        $merchant_account = $request['merchant_account'];
        if( $request->payment_id == "other" ){
            if( in_array(Auth::user()->role_id, [1,2]) ){
                return redirect()->route('Transaction.Value.Create.Admin', [$request['user_id'], $request['amount'], $merchant_account]);
            } else {
                return redirect()->route('Transaction.Value.Create', [$request['amount'], $merchant_account]);
            }
        }

        //Get Payments Details
        $payments = Payment::where('payment_id', $request->payment_id)
            ->first([
                'payment_full_name', 'payment_visa_number', 'payment_cvv',
                'payment_exp_month', 'payment_exp_year', 'payment_zip_code',
                'payment_address', 'payment_visa_last4char', 'payment_num_trx',
                'payment_total_ammount'
            ]);

        $visa_paymant = $payments['payment_num_trx'] . $payments['payment_visa_last4char'] . $payments['payment_total_ammount'] . $payments['payment_visa_number'];

        //Set up the payments fullname
        $full_name = $payments['payment_full_name'];
        $name_arr = explode(" ",$full_name);
        $first_name = (!empty($name_arr[0]) ? $name_arr[0] : "");
        array_shift($name_arr);
        $last_name = implode(" ",$name_arr);

        $request['full_name'] = $full_name;
        $request['first_name'] = $first_name;
        $request['last_name'] = $last_name;

        $request['cardNumber'] = $visa_paymant;
        $request['expiration-year'] = $payments['payment_exp_year'];
        $request['expiration-month'] = $payments['payment_exp_month'];
        $request['cvv'] = $payments['payment_cvv'];

        //Return address info
        $addressDetails = ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->where('zip_codes_lists.zip_code_list', $payments['payment_zip_code'])
            ->first([
                'states.state_name', 'states.state_code', 'cities.city_name'
            ]);
        $city_arr = explode('=>',  $addressDetails->city_name);

        $request['zipcode'] = $payments['payment_zip_code'];
        $request['address1'] = $payments['payment_address'];
        $request['city'] = $city_arr[0];
        $request['state'] = $addressDetails->state_code;
        $request['state_name'] = $addressDetails->state_name;

        //Return user email
        if( in_array(Auth::user()->role_id, [1,2]) ){
            $user_email = User::find($request['user_id']);
            $request['email'] = $user_email->email;
        } else {
            $request['email'] = Auth::user()->email;
        }

        //Check the merchant account
        $request['payment_type'] = $merchant_account;

        switch ($merchant_account){
            case "Auth1":
                $request['MERCHANT_LOGIN_ID'] = "7wY89Qmx";
                $request['MERCHANT_TRANSACTION_KEY'] = "7eBnmN33m2T7W94G";
                $payments = new PaymentAuthController();
                $payments->handleonlinepay($request);
                return back();
                break;
            case "Auth2":
                $request['MERCHANT_LOGIN_ID'] = "2LmJH4r4f";
                $request['MERCHANT_TRANSACTION_KEY'] = "684598e7KDqwwKDD";
                $payments = new PaymentAuthController();
                $payments->handleonlinepay($request);
                return back();
                break;
            case "Auth3":
                $request['MERCHANT_LOGIN_ID'] = "47h7eRZyAZ26";
                $request['MERCHANT_TRANSACTION_KEY'] = "5z6D6eHa79p6X7CW";
                $payments = new PaymentAuthController();
                $payments->handleonlinepay($request);
                return back();
                break;
            case "Auth4":
                $request['MERCHANT_LOGIN_ID'] = "89LvP4kPM";
                $request['MERCHANT_TRANSACTION_KEY'] = "44K4V372tHUk2j6A";
                $payments = new PaymentAuthController();
                $payments->handleonlinepay($request);
                return back();
                break;
            case "NMI":
                $request['NMI_SECURITY_KEY'] = "p8Mfe6mN6HMDcFW5tmGmc7saQn36G3nm";
                $payments = new NMIPaymentController();
                $payments->do_sale($request);
                return back();
                break;
            case "NMI2":
                $request['NMI_SECURITY_KEY'] = "523rMS4zpDnJVNaC5G56UR5swzmzG78k";
                $payments = new NMIPaymentController();
                $payments->do_sale($request);
                return back();
                break;
            case "NMI3":
                $request['NMI_SECURITY_KEY'] = "KM55mz4DQ8MKQStNKu22uUTz394359y8";
                $payments = new NMIPaymentController();
                $payments->do_sale($request);
                return back();
                break;
            case "Stripe":
                $request['stripe_secret'] = config('services.stripe.secret', '');
                $payments = new StripePaymentController();
                $payments->generate_token($request);
                return back();
                break;
            default:
                return back();
        }
    }

    public function refund(Request $request){
        //Check the merchant account
        $merchant_account = $request['merchant_account'];
        $request['payment_type'] = $merchant_account;

        switch ($merchant_account){
            case "Auth1":
                $request['MERCHANT_LOGIN_ID'] = "7wY89Qmx";
                $request['MERCHANT_TRANSACTION_KEY'] = "7eBnmN33m2T7W94G";
                $payments = new RefundAuthController();
                $payments->refundTransaction($request);
                return back();
                break;
            case "Auth2":
                $request['MERCHANT_LOGIN_ID'] = "2LmJH4r4f";
                $request['MERCHANT_TRANSACTION_KEY'] = "684598e7KDqwwKDD";
                $payments = new RefundAuthController();
                $payments->refundTransaction($request);
                return back();
                break;
            case "Auth3":
                $request['MERCHANT_LOGIN_ID'] = "47h7eRZyAZ26";
                $request['MERCHANT_TRANSACTION_KEY'] = "5z6D6eHa79p6X7CW";
                $payments = new RefundAuthController();
                $payments->refundTransaction($request);
                return back();
                break;
            case "Auth4":
                $request['MERCHANT_LOGIN_ID'] = "89LvP4kPM";
                $request['MERCHANT_TRANSACTION_KEY'] = "44K4V372tHUk2j6A";
                $payments = new RefundAuthController();
                $payments->refundTransaction($request);
                return back();
                break;
            case "eCheck":
                $request['MERCHANT_LOGIN_ID'] = config('services.authorizeNet.MERCHANT_LOGIN_ID', '');
                $request['MERCHANT_TRANSACTION_KEY'] = config('services.authorizeNet.MERCHANT_TRANSACTION_KEY', '');
                $payments = new RefundEcheckController();
                $payments->refund_payments($request);
                return back();
                break;
            case "NMI":
                $request['NMI_SECURITY_KEY'] = "p8Mfe6mN6HMDcFW5tmGmc7saQn36G3nm";
                $payments = new NMIPaymentController();
                $payments->doRefund($request);
                return back();
                break;
            case "NMI2":
                $request['NMI_SECURITY_KEY'] = "523rMS4zpDnJVNaC5G56UR5swzmzG78k";
                $payments = new NMIPaymentController();
                $payments->doRefund($request);
                return back();
                break;
            case "NMI3":
                $request['NMI_SECURITY_KEY'] = "KM55mz4DQ8MKQStNKu22uUTz394359y8";
                $payments = new NMIPaymentController();
                $payments->doRefund($request);
                return back();
                break;
            case "Stripe":
                $request['stripe_secret'] = config('services.stripe.secret', '');
                $payments = new StripePaymentController();
                $payments->refund_payments($request);
                return back();
                break;
            case "PayPal":
                $payments = new RefundPaypalController();
                $payments->refund_payment($request);
                return back();
                break;
            default:
                return back();
        }
    }
}
