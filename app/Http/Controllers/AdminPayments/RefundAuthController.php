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

class RefundAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // later enable it when needed user login while payment
    }

    function refundTransaction(Request $request)
    {

        $input = $request->input();

        $id = $input['id'];
        $amount = $input['amount'];

        $transaction_data = Transaction::join('payments','payments.payment_id','=','transactions.payment_id')
            ->where('transaction_id', $id)
            ->first([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_exp_month', 'payments.payment_exp_year'
            ]);

        $refTransId = $transaction_data['transactionauthid'];

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
        $cardNumber = substr($transaction_data['payment_visa_number'], -4);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate( $transaction_data['payment_exp_month'] . substr($transaction_data['payment_exp_year'], -4));
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setPayment($paymentOne);
        $transactionRequest->setRefTransId($refTransId);


        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId($refId);
        $requests->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($requests);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null) {
//                    echo " Transaction Response code : " . $tresponse->getResponseCode() . "\n";
//                    echo "Refund SUCCESS: " . $tresponse->getTransId() . "\n";
//                    echo " Code : " . $tresponse->getMessages()[0]->getCode() . "\n";
//                    echo " Description : " . $tresponse->getMessages()[0]->getDescription() . "\n";

                    $message_text = $tresponse->getMessages()[0]->getDescription() . ", Transaction ID: " . $tresponse->getTransId();
                    $msg_type = "success";

                    $user_id = $transaction_data['user_id'];

                    $transaction = new Transaction();

                    $transaction->admin_id = Auth::user()->id;
                    $transaction->user_id = $user_id;
                    $transaction->transactions_value = $amount;
                    $transaction->payment_id = $transaction_data['payment_id'];
                    $transaction->transactions_comments = 'Refund Payments';
                    $transaction->transactionauthid = $tresponse->getTransId();
                    $transaction->transactions_response = json_encode($tresponse);
                    $transaction->transaction_status = 2;
                    $transaction->accept = 1;
                    $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Auth3" );

                    $transaction->save();

                    Transaction::where('transaction_id', $id)->update(['is_refund' => 1]);

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

                    $trx_id = $tresponse->getTransId();

                    Slack::send("User $email Refund Payments $$amount using the Authorize.net, Transaction Id: $trx_id :)");

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

                    if ($tresponse->getErrors() != null) {
                        $message_text = $tresponse->getErrors()[0]->getErrorText();
                        $msg_type = "error";
                    }
                }
            } else {
                $message_text = 'There were some issue with the payment. Please try again later.';
                $msg_type = "error";

                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $message_text = $tresponse->getErrors()[0]->getErrorText();
                    $msg_type = "error";
                } else {
                    $message_text = $response->getMessages()->getMessage()[0]->getText();
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


}
