<?php

namespace App\Http\Controllers\AdminPayments;

use App\TotalAmount;
use App\Transaction;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Slack;

class EcheckPaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // later enable it when needed user login while payment
    }

    public function debitBankAccount(Request $request, $user_id)
    {
        $this->validate($request, [
            'amount' => ['required'],
            'routing_number' => ['required'],
            'account_number' => ['required'],
            'username' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'account_type' => ['required', 'string', 'max:255']
        ]);

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

        //Generate random bank account number
        $randomAccountNumber= $request->account_number;
//        $randomAccountNumber= rand(100000000,9999999999);//Test

        // Create the payment data for a Bank Account
        $bankAccount = new AnetAPI\BankAccountType();
//        $bankAccount->setAccountType('checking');
        $bankAccount->setAccountType($request->account_type);
        // see eCheck documentation for proper echeck type to use for each situation
        $bankAccount->setEcheckType('WEB');
        $bankAccount->setRoutingNumber($request->routing_number);
//        $bankAccount->setRoutingNumber('122000661');//Test

        $bankAccount->setAccountNumber($randomAccountNumber);

        $bankAccount->setNameOnAccount($request->username);
        $bankAccount->setBankName($request->bank_name);
//        $bankAccount->setNameOnAccount('John Doe');//Test
//        $bankAccount->setBankName('Wells Fargo Bank NA');//Test

        $paymentBank= new AnetAPI\PaymentType();
        $paymentBank->setBankAccount($bankAccount);

        //create a bank debit transaction

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($request->amount);
        $transactionRequestType->setPayment($paymentBank);
        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
//                    echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
//                    echo " Debit Bank Account APPROVED  :" . "\n";
//                    echo " Debit Bank Account AUTH CODE : " . $tresponse->getAuthCode() . "\n";
//                    echo " Debit Bank Account TRANS ID  : " . $tresponse->getTransId() . "\n";
//                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
//                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";
                    $message_text = $tresponse->getMessages()[0]->getDescription() . ", Transaction ID: " . $tresponse->getTransId();
                    $msg_type = "success";

                    $transaction = new Transaction();

                    if( Auth::user()->role_id == 1 || Auth::user()->role_id == 2 ){
                        $transaction->admin_id = Auth::user()->id;
                    }
                    $transaction->user_id = $user_id;
                    $transaction->transactions_value = $request->amount;
                    $transaction->transactions_comments = 'eCheck';
                    $transaction->transactionauthid = $tresponse->getTransId();
                    $transaction->transactions_response = json_encode($tresponse);
                    $transaction->request_method = json_encode($request->all());
                    $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "eCheck" );
                    $transaction->accept = 1;

                    $transaction->save();

                    $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                    if( empty($totalAmount) ){
                        $addtotalAmount = new TotalAmount();

                        $addtotalAmount->user_id = $user_id;
                        $addtotalAmount->total_amounts_value = $request->amount;

                        $addtotalAmount->save();
                    } else {
                        $total = $request->amount + $totalAmount['total_amounts_value'];
                        TotalAmount::where('user_id', $user_id)
                            ->update([ 'total_amounts_value' => $total ]);
                    }

                    $user_data = User::where('id', $user_id)->first(['email', 'username']);
                    $email = $user_data->email;
                    $buyer_name = $user_data->username;

                    $amount = $request->amount;
                    $trx_id = $tresponse->getTransId();

                    Slack::send("User $email paid $$amount using the e-Check Authorize.net, Transaction Id: $trx_id :)");

                    //New Payments Email For users
                    $data = array(
                        'name' => $buyer_name,
                        'value' => $amount
                    );

                    Mail::send(['text'=>'Mail.payment'], $data, function($message) use($email, $buyer_name) {
                        $message->to($email, $buyer_name)->subject('New Payments');
                        $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
                    });
                } else {
//                    echo "Transaction Failed \n";
                    $message_text = 'There were some issue with the payment. Please try again later.';
                    $msg_type = "error";
                    if ($tresponse->getErrors() != null) {
//                        echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
//                        echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error";
                    }
                }
            } else {
//                echo "Transaction Failed \n";
                $message_text = 'There were some issue with the payment. Please try again later.';
                $msg_type = "error";

                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
//                    echo " Error code  : " . $tresponse->getErrors()[0]->getErrorCode() . "\n";
//                    echo " Error message : " . $tresponse->getErrors()[0]->getErrorText() . "\n";
                    $message_text = $tresponse->getErrors()[0]->getErrorText();
                    $msg_type = "error";
                } else {
//                    echo " Error code  : " . $response->getMessages()->getMessage()[0]->getCode() . "\n";
//                    echo " Error message : " . $response->getMessages()->getMessage()[0]->getText() . "\n";
                    $message_text = $response->getMessages()->getMessage()[0]->getText();
                    $msg_type = "error";
                }
            }
        } else {
//            echo  "No response returned \n";
            $message_text = "No response returned";
            $msg_type = "error";
        }

        \Session::put($msg_type, $message_text);

        return back()->with($msg_type, $message_text);
    }
}
