<?php

namespace App\Http\Controllers\AdminPayments;

use App\TotalAmount;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Slack;

class PaymentAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // later enable it when needed user login while payment
    }

    // start page form after start
    public function pay()
    {
        return view('pay');
    }

    public function handleonlinepay(Request $request)
    {
        $input = $request->input();
        $TodayDate = date("Y-m-d");
        //Return How Many Trx Today
        $Check_transactions = Transaction::where('user_id', $input['user_id'])
            ->where('created_at', "like", "%" . $TodayDate . "%")
            ->where('payment_id', $input['payment_id'])
            ->where('payment_type', (!empty($request['payment_type']) ? $request['payment_type'] : "Auth3" ))
            ->count();

        if ($Check_transactions < 3) {
            /* Create a merchantAuthenticationType object with authentication details
              retrieved from the constants file */
            $merchant_login_id = config('services.authorizeNet.MERCHANT_LOGIN_ID', '');
            if(!empty($request['MERCHANT_LOGIN_ID'])){
                $merchant_login_id = $request['MERCHANT_LOGIN_ID'];
            }
            $merchant_login_key = config('services.authorizeNet.MERCHANT_TRANSACTION_KEY', '');
            if(!empty($request['MERCHANT_TRANSACTION_KEY'])){
                $merchant_login_key = $request['MERCHANT_TRANSACTION_KEY'];
            }

            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
            $merchantAuthentication->setName($merchant_login_id);
            $merchantAuthentication->setTransactionKey($merchant_login_key);

            // Set the transaction's refId
            $refId = 'ref' . time();
            $cardNumber = preg_replace('/\s+/', '', $input['cardNumber']);

            // Create the payment data for a credit card
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($input['expiration-year'] . "-" . $input['expiration-month']);
            $creditCard->setCardCode($input['cvv']);

            // Add the payment data to a paymentType object
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Create a TransactionRequestType object and add the previous objects to it
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($input['amount']);
            $transactionRequestType->setPayment($paymentOne);

            // Assemble the complete transaction request
            $requests = new AnetAPI\CreateTransactionRequest();
            $requests->setMerchantAuthentication($merchantAuthentication);
            $requests->setRefId($refId);
            $requests->setTransactionRequest($transactionRequestType);

            // Create the controller and get the response
            $controller = new AnetController\CreateTransactionController($requests);
//        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

            $transaction = new Transaction();

            $user_id = $input['user_id'];
            $error_response = false;
            $tresponse = $response->getTransactionResponse();

            if ($response != null) {
                // Check to see if the API request was successfully received and acted upon
                if ($response->getMessages()->getResultCode() == "Ok") {
                    // Since the API request was successful, look for a transaction response
                    // and parse it to display the results of authorizing the card

                    if ($tresponse != null && $tresponse->getMessages() != null) {
//                    echo " Successfully created transaction with Transaction ID: " . $tresponse->getTransId() . "\n";
//                    echo " Transaction Response Code: " . $tresponse->getResponseCode() . "\n";
//                    echo " Message Code: " . $tresponse->getMessages()[0]->getCode() . "\n";
//                    echo " Auth Code: " . $tresponse->getAuthCode() . "\n";
//                    echo " Description: " . $tresponse->getMessages()[0]->getDescription() . "\n";
                        $message_text = $tresponse->getMessages()[0]->getDescription() . ", Transaction ID: " . $tresponse->getTransId();
                        $msg_type = "success";

                        $transaction->admin_id = Auth::user()->id;
                        $transaction->user_id = $user_id;
                        $transaction->transactions_value = $input['amount'];
                        $transaction->payment_id = $input['payment_id'];
                        $transaction->transactions_comments = 'Credit Accumulation';
                        $transaction->transactionauthid = $tresponse->getTransId();
                        $transaction->transactions_response = json_encode($tresponse);
                        $transaction->accept = 1;
                        $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Auth3" );

                        $transaction->save();

                        $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                        if (empty($totalAmount)) {
                            $addtotalAmount = new TotalAmount();

                            $addtotalAmount->user_id = $user_id;
                            $addtotalAmount->total_amounts_value = $input['amount'];

                            $addtotalAmount->save();
                        } else {
                            $total = $input['amount'] + $totalAmount['total_amounts_value'];
                            TotalAmount::where('user_id', $user_id)
                                ->update(['total_amounts_value' => $total]);
                        }

                        $user_data = User::where('id', $user_id)->first(['email', 'username']);
                        $email = $user_data->email;
                        $buyer_name = $user_data->username;

                        $amount = $input['amount'];
                        $trx_id = $tresponse->getTransId();

                        //Slack::send("User $email paid $$amount using the Authorize.net, Transaction Id: $trx_id :)");

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

                        if ($tresponse->getErrors() != null) {
                            $message_text = $tresponse->getErrors()[0]->getErrorText();
                            $msg_type = "error";
                        }
                    }
                    // Or, print errors if the API request wasn't successful
                } else {
                    $error_response = true;
                    if ($tresponse != null && $tresponse->getErrors() != null) {
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error";
                    } else {
                        $message_text = $response->getMessages()->getMessage()[0]->getText();
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
                $transaction->transactions_value = $input['amount'];
                $transaction->payment_id = $input['payment_id'];
                $transaction->transactions_comments = 'Credit Accumulation';
                $transaction->transactionauthid = "";
                $transaction->transactions_response = json_encode($tresponse);
                $transaction->accept = 2;
                $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Auth3" );
                $transaction->save();
            }

            \Session::put($msg_type, $message_text);
            return true;
        } else {
            \Session::put('error', "Payment Failed, You can't use the same card for more than one payment a day!");
            return true;
        }
    }

    public function auto_pay($merchant_account, $user_id, $amount, $user_payment, $MERCHANT_LOGIN_ID, $MERCHANT_TRANSACTION_KEY){
        /* Create a merchantAuthenticationType object with authentication details
                    retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        //$merchantAuthentication->setName(config('services.authorizeNet.MERCHANT_LOGIN_ID', ''));
        //$merchantAuthentication->setTransactionKey(config('services.authorizeNet.MERCHANT_TRANSACTION_KEY', ''));
        $merchantAuthentication->setName($MERCHANT_LOGIN_ID);
        $merchantAuthentication->setTransactionKey($MERCHANT_TRANSACTION_KEY);

        // Set the transaction's refId
        $refId = 'ref' . time();

        $visa_paymant = $user_payment['payment_num_trx'] . $user_payment['payment_visa_last4char'] . $user_payment['payment_total_ammount'] . $user_payment['payment_visa_number'];
        $cardNumber = preg_replace('/\s+/', '', $visa_paymant);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($user_payment['payment_exp_year'] . "-" . $user_payment['payment_exp_month']);
        $creditCard->setCardCode($user_payment['payment_cvv']);

        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentOne);

        // Assemble the complete transaction request
        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);

        // Create the controller and get the response
        $controller = new AnetController\CreateTransactionController($requests);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        $tresponse = $response->getTransactionResponse();
        $transaction = new Transaction();

        if ($response != null) {
            // Check to see if the API request was successfully received and acted upon
            if ($response->getMessages()->getResultCode() == "Ok") {
                // Since the API request was successful, look for a transaction response
                // and parse it to display the results of authorizing the card
                //$tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {

                    $transaction->user_id = $user_id;
                    $transaction->transactions_value = $amount;
                    $transaction->payment_id = $user_payment['payment_id'];
                    $transaction->transactions_comments = 'Auto Credit Accumulation';
                    $transaction->transactionauthid = $tresponse->getTransId();
                    $transaction->transactions_response = json_encode($tresponse);
                    $transaction->accept = 1;
                    $transaction->payment_type = $merchant_account;

                    $transaction->save();

                    $totalAmmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

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
                    $trx_id = $tresponse->getTransId();

                    //Slack::send("User $email paid $$amount using the Authorize.net, Transaction Id: $trx_id :)");

                    //New Payments Email For users
                    $data = array(
                        'name' => $buyer_name,
                        'value' => $amount
                    );
                    Mail::send(['text'=>'Mail.payment'], $data, function($message) use($email, $buyer_name) {
                        $message->to($email, $buyer_name)->subject('New Payments');
                        $message->from(config('mail.from.address', ''), config('mail.from.name', ''));
                    });

                    return true;
                }
            }
        }

        $transaction->user_id = $user_id;
        $transaction->transactions_value = $amount;
        $transaction->payment_id = $user_payment['payment_id'];
        $transaction->transactions_comments = 'Auto Credit Accumulation';
        $transaction->transactionauthid = "";
        $transaction->transactions_response = json_encode($tresponse);
        $transaction->accept = 2;
        $transaction->payment_type = $merchant_account;

        $transaction->save();

        $user_data = User::where('id', $user_id)->first(['email', 'username']);
        $email = $user_data->email;
        //Slack::send("User $email, Auto Charge failed!");
        return false;
    }
}
