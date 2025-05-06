<?php

namespace App\Http\Controllers\AdminPayments;

use App\Http\Controllers\Controller;
use App\TotalAmount;
use App\Transaction;
use App\User;
use App\ZipCodesList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Slack;

class NMIPaymentController extends Controller
{
    public function do_sale(Request $request){
        $TodayDate = date("Y-m-d");
        //Return How Many Trx Today
        $Check_transactions = Transaction::where('user_id', $request['user_id'])
            ->where('created_at', "like", "%" . $TodayDate . "%")
            ->where('payment_id', $request['payment_id'])
            ->where('payment_type', (!empty($request['payment_type']) ? $request['payment_type'] : "Auth3" ))
            ->count();

        $transaction = new Transaction();

        $user_id = $request['user_id'];
        $error_response = false;
        if ($Check_transactions < 3) {
            //Get nmi security_key
            $security_key = config('services.NMIPayments.NMI_SECURITY_KEY', '');
            if (!empty($request['NMI_SECURITY_KEY'])) {
                $security_key = $request['NMI_SECURITY_KEY'];
            }

            $query = "";
            // Login Information
            $query .= "security_key=" . urlencode($security_key) . "&";
            // Sales Information
            $query .= "ccnumber=" . urlencode($request['cardNumber']) . "&";
            $query .= "ccexp=" . urlencode($request['expiration-month'] . '/' . $request['expiration-year']) . "&";
            $query .= "amount=" . urlencode(number_format($request['amount'], 2, ".", "")) . "&";
            $query .= "cvv=" . urlencode($request['cvv']) . "&";
            // Order Information
//        $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
//        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
//        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
//        $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
//        $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
//        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
            // Billing Information
            $query .= "firstname=" . urlencode($request['first_name']) . "&";
            $query .= "lastname=" . urlencode($request['last_name']) . "&";
//        $query .= "company=" . urlencode($this->billing['company']) . "&";
            $query .= "address1=" . urlencode($request['address1']) . "&";
//        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
            $query .= "city=" . urlencode($request['city']) . "&";
            $query .= "state=" . urlencode($request['state']) . "&";
            $query .= "zip=" . urlencode($request['zipcode']) . "&";
//        $query .= "country=" . urlencode($this->billing['country']) . "&";
//        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
//        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
            $query .= "email=" . urlencode($request['email']) . "&";
//        $query .= "website=" . urlencode($this->billing['website']) . "&";
            // Shipping Information
//        $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
//        $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
//        $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
//        $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
//        $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
//        $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
//        $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
//        $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
//        $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
//        $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
            $query .= "type=sale";

            $payments_response = $this->_doPost($query);

            if(!empty($payments_response)){
                if ($payments_response['response'] == 1 || $payments_response['responsetext'] == "SUCCESS") {
                    $amount = $request['amount'];
                    $trx_id = $payments_response['transactionid'];

                    $message_text = $payments_response['responsetext'] . ", Transaction ID: " . $trx_id;
                    $msg_type = "success";

                    //Store Transactions
                    $transaction->admin_id = Auth::user()->id;
                    $transaction->user_id = $user_id;
                    $transaction->transactions_value = $amount;
                    $transaction->payment_id = $request['payment_id'];
                    $transaction->transactions_comments = 'Credit Accumulation';
                    $transaction->transactionauthid = $trx_id;
                    $transaction->transactions_response = json_encode($payments_response);
                    $transaction->accept = 1;
                    $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "NMI");

                    $transaction->save();

                    //Get Current Total Amount
                    $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                    //Set new total amount
                    if (empty($totalAmount)) {
                        $addtotalAmount = new TotalAmount();

                        $addtotalAmount->user_id = $user_id;
                        $addtotalAmount->total_amounts_value = $amount;

                        $addtotalAmount->save();
                    } else {
                        $total = $amount + $totalAmount['total_amounts_value'];
                        TotalAmount::where('user_id', $user_id)
                            ->update(['total_amounts_value' => $total]);
                    }

                    $user_data = User::where('id', $user_id)->first(['email', 'username']);
                    $email = $user_data->email;
                    $buyer_name = $user_data->username;

                    //Slack::send("User $email paid $$amount using the NMI Payments, Transaction Id: $trx_id :)");

                    //New Payments Email For users
                    $data = array(
                        'name' => $buyer_name,
                        'value' => $amount
                    );

                    Mail::send(['text' => 'Mail.payment'], $data, function ($message) use ($email, $buyer_name) {
                        $message->to($email, $buyer_name)->subject('New Payments');
                        $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
                    });
                } else {
                    $error_response = true;
                    $message_text = 'There were some issue with the payment. Please try again later.';
                    $msg_type = "error";

                    if ($payments_response['responsetext'] != null) {
                        $message_text = $payments_response['responsetext'];
                        $msg_type = "error";
                    }
                }
            } else {
                $error_response = true;
                $message_text = "No response returned";
                $msg_type = "error";
            }

            if( $error_response == true ){
                $transaction->admin_id = Auth::user()->id;
                $transaction->user_id = $user_id;
                $transaction->transactions_value = $request['amount'];
                $transaction->payment_id = $request['payment_id'];
                $transaction->transactions_comments = 'Credit Accumulation';
                $transaction->transactionauthid = "";
                $transaction->transactions_response = json_encode($payments_response);
                $transaction->accept = 2;
                $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "NMI" );
                $transaction->save();
            }

            \Session::put($msg_type, $message_text);
            return true;
        } else {
            \Session::put('error', "Payment Failed, You can't use the same card for more than one payment a day!");
            return true;
        }
    }

    public function auto_pay($merchant_account, $user_id, $amount, $user_payment, $security_key){
        $visa_paymant = $user_payment['payment_num_trx'] . $user_payment['payment_visa_last4char'] . $user_payment['payment_total_ammount'] . $user_payment['payment_visa_number'];

        //Set up the payments fullname
        $full_name = $user_payment['payment_full_name'];
        $name_arr = explode(" ",$full_name);
        $first_name = (!empty($name_arr[0]) ? $name_arr[0] : "");
        array_shift($name_arr);
        $last_name = implode(" ",$name_arr);

        //Return address info
        $addressDetails = ZipCodesList::join('states', 'states.state_id', '=', 'zip_codes_lists.state_id')
            ->join('cities', 'cities.city_id', '=', 'zip_codes_lists.city_id')
            ->where('zip_codes_lists.zip_code_list', $user_payment['payment_zip_code'])
            ->first([
                'states.state_name', 'states.state_code', 'cities.city_name'
            ]);
        $city_arr = explode('=>',  $addressDetails->city_name);

        $zipcode = $user_payment['payment_zip_code'];
        $address1 = $user_payment['payment_address'];
        $city = $city_arr[0];
        $state = $addressDetails->state_code;
        $state_name = $addressDetails->state_name;
        $error_response = false;

        $transaction = new Transaction();

        $query = "";
        // Login Information
        $query .= "security_key=" . urlencode($security_key) . "&";
        // Sales Information
        $query .= "ccnumber=" . urlencode($visa_paymant) . "&";
        $query .= "ccexp=" . urlencode($user_payment['payment_exp_month'] . '/' . $user_payment['payment_exp_year']) . "&";
        $query .= "amount=" . urlencode(number_format($amount, 2, ".", "")) . "&";
        $query .= "cvv=" . urlencode($user_payment['payment_cvv']) . "&";
        // Order Information
//        $query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
//        $query .= "orderid=" . urlencode($this->order['orderid']) . "&";
//        $query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
//        $query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
//        $query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
//        $query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
        // Billing Information
        $query .= "firstname=" . urlencode($first_name) . "&";
        $query .= "lastname=" . urlencode($last_name) . "&";
//        $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($address1) . "&";
//        $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($city) . "&";
        $query .= "state=" . urlencode($state) . "&";
        $query .= "zip=" . urlencode($zipcode) . "&";
//        $query .= "country=" . urlencode($this->billing['country']) . "&";
//        $query .= "phone=" . urlencode($this->billing['phone']) . "&";
//        $query .= "fax=" . urlencode($this->billing['fax']) . "&";
//        $query .= "email=" . urlencode($this->billing['email']) . "&";
//        $query .= "website=" . urlencode($this->billing['website']) . "&";
        // Shipping Information
//        $query .= "shipping_firstname=" . urlencode($this->shipping['firstname']) . "&";
//        $query .= "shipping_lastname=" . urlencode($this->shipping['lastname']) . "&";
//        $query .= "shipping_company=" . urlencode($this->shipping['company']) . "&";
//        $query .= "shipping_address1=" . urlencode($this->shipping['address1']) . "&";
//        $query .= "shipping_address2=" . urlencode($this->shipping['address2']) . "&";
//        $query .= "shipping_city=" . urlencode($this->shipping['city']) . "&";
//        $query .= "shipping_state=" . urlencode($this->shipping['state']) . "&";
//        $query .= "shipping_zip=" . urlencode($this->shipping['zip']) . "&";
//        $query .= "shipping_country=" . urlencode($this->shipping['country']) . "&";
//        $query .= "shipping_email=" . urlencode($this->shipping['email']) . "&";
        $query .= "type=sale";

        $payments_response = $this->_doPost($query);

        if(!empty($payments_response)){
            if ($payments_response['response'] == 1 || $payments_response['responsetext'] == "SUCCESS") {
                $trx_id = $payments_response['transactionid'];

                //Store Transactions
                $transaction->user_id = $user_id;
                $transaction->transactions_value = $amount;
                $transaction->payment_id = $user_payment['payment_id'];
                $transaction->transactions_comments = 'Auto Credit Accumulation';
                $transaction->transactionauthid = $trx_id;
                $transaction->transactions_response = json_encode($payments_response);
                $transaction->accept = 1;
                $transaction->payment_type = $merchant_account;

                $transaction->save();

                //Get Current Total Amount
                $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                //Set new total amount
                if( empty($totalAmmount) ){
                    $addtotalAmmount = new TotalAmount();

                    $addtotalAmmount->user_id = $user_id;
                    $addtotalAmmount->total_amounts_value = $amount;

                    $addtotalAmmount->save();
                } else {
                    $total = $amount + $totalAmmount['total_amounts_value'];
                    TotalAmount::where('user_id', $user_id)
                        ->update([ 'total_amounts_value' => $total ]);
                }

                $user_data = User::where('id', $user_id)->first(['email', 'username']);
                $email = $user_data->email;
                $buyer_name = $user_data->username;

                //Slack::send("User $email paid $$amount using the NMI Payments, Transaction Id: $trx_id :)");

                //New Payments Email For users
                $data = array(
                    'name' => $buyer_name,
                    'value' => $amount
                );
                Mail::send(['text'=>'Mail.payment'], $data, function($message) use($email, $buyer_name) {
                    $message->to($email, $buyer_name)->subject('New Payments');
                    $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
                });
            } else {
                $error_response = true;
            }
        } else {
            $error_response = true;
        }

        if( $error_response == true ){
            $transaction->user_id = $user_id;
            $transaction->transactions_value = $amount;
            $transaction->payment_id = $user_payment['payment_id'];
            $transaction->transactions_comments = 'Auto Credit Accumulation';
            $transaction->transactionauthid = "";
            $transaction->transactions_response = json_encode($payments_response);
            $transaction->accept = 2;
            $transaction->payment_type = $merchant_account;
            $transaction->save();

            $user_data = User::where('id', $user_id)->first(['email', 'username']);
            $email = $user_data->email;
            //Slack::send("User $email, Auto Charge failed!");

            return false;
        }

        return true;
    }

    function doRefund(Request $request) {
        $security_key = config('services.NMIPayments.NMI_SECURITY_KEY', '');
        if (!empty($request['NMI_SECURITY_KEY'])) {
            $security_key = $request['NMI_SECURITY_KEY'];
        }

        $id = $request['id'];
        $amount = $request['amount'];

        $transaction_data = Transaction::join('payments','payments.payment_id','=','transactions.payment_id')
            ->where('transaction_id', $id)
            ->first([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_exp_month', 'payments.payment_exp_year'
            ]);

        $refTransId = $transaction_data['transactionauthid'];

        $query  = "";
        // Login Information
        $query .= "security_key=" . urlencode($security_key) . "&";
        // Transaction Information
        $query .= "transactionid=" . urlencode($refTransId) . "&";
        if ($amount>0) {
            $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
        }
        $query .= "type=refund";
        $payments_response = $this->_doPost($query);

        if(!empty($payments_response)){
            if ($payments_response['response'] == 1 || $payments_response['responsetext'] == "SUCCESS") {
                $amount = $request['amount'];
                $trx_id = $payments_response['transactionid'];

                $message_text = $payments_response['responsetext'] . ", Transaction ID: " . $trx_id;
                $msg_type = "success";

                $user_id = $transaction_data['user_id'];

                $transaction = new Transaction();

                $transaction->admin_id = Auth::user()->id;
                $transaction->user_id = $user_id;
                $transaction->transactions_value = $amount;
                $transaction->payment_id = $transaction_data['payment_id'];
                $transaction->transactions_comments = 'Refund Payments';
                $transaction->transactionauthid = $trx_id;
                $transaction->transactions_response = json_encode($payments_response);
                $transaction->transaction_status = 2;
                $transaction->accept = 1;
                $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "NMI" );

                $transaction->save();

                Transaction::where('transaction_id', $id)
                    ->update([
                        'is_refund' => 1
                    ]);

                $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                if( empty($totalAmount) ){
                    $addtotalAmount = new TotalAmount();

                    $addtotalAmount->user_id = $user_id;
                    $addtotalAmount->total_amounts_value = '-'. $amount;

                    $addtotalAmount->save();
                } else {
                    $total = $totalAmount['total_amounts_value'] - $amount;
                    TotalAmount::where('user_id', $user_id)
                        ->update([ 'total_amounts_value' => $total ]);
                }

                $user_data = User::where('id', $user_id)->first(['email', 'username']);
                $email = $user_data->email;
                $buyer_name = $user_data->username;

                //Slack::send("User $email Refund Payments $$amount using the NMI Payments, Transaction Id: $trx_id :)");

                //New Refund Email For users
                $data = array(
                    'name' => $buyer_name,
                    'value' => $amount
                );

                Mail::send(['text'=>'Mail.refund'], $data, function($message) use($email, $buyer_name) {
                    $message->to($email, $buyer_name)->subject('Refund Payments');
                    $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
                });
            } else {
                $message_text = 'There were some issue with the payment. Please try again later.';
                $msg_type = "error";

                if ($payments_response['responsetext'] != null) {
                    $message_text = $payments_response['responsetext'];
                    $msg_type = "error";
                }
            }
        } else {
            $message_text = "No response returned";
            $msg_type = "error";
        }

        \Session::put($msg_type, $message_text);
        return true;
    }

    function _doPost($query) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.nmi.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($data = curl_exec($ch))) {
            return false;
        }
        curl_close($ch);
        unset($ch);
        $data = explode("&",$data);
        for($i=0;$i<count($data);$i++) {
            $rdata = explode("=",$data[$i]);
            $this->responses[$rdata[0]] = $rdata[1];
        }
        return $this->responses;
        //{"response":"3","responsetext":"Duplicate transaction REFID:1183118766","authcode":"","transactionid":"","avsresponse":"","cvvresponse":"","orderid":"","type":"sale","response_code":"300"}
    }
}
