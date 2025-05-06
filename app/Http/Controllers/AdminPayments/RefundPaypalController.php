<?php

namespace App\Http\Controllers\AdminPayments;

use App\TotalAmount;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Refund;
use PayPal\Auth\OAuthTokenCredential;
use Slack;
use PayPal\Api\Amount;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Rest\ApiContext;

class RefundPaypalController extends Controller
{
    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    function refund_payment(Request $request){

        $input = $request->input();

        $id = $input['id'];
        $amount = $input['amount'];

        $transaction_data = Transaction::where('transaction_id', $id)->first();

        $saleId = $transaction_data->transactionauthid;

        $paymentValue =  (string) round($amount,2);

        $amt = new Amount();
        $amt->setCurrency('USD')
            ->setTotal($paymentValue);

        $refundRequest = new Refund();
        $refundRequest->setAmount($amt);

        $sale = new Sale();
        $sale->setId($saleId);
        try {
            $refundedSale = $sale->refundSale($refundRequest, $this->_api_context);
        } catch (Exception $ex) {
            // echo $ex->getCode();
            //echo $ex->getData();
            //die($ex);
            $message_text = 'There were some issue with the Refund payment. Please try again later.';
            $msg_type = "error";

            \Session::put($msg_type, $message_text);
            return true;
        } catch (Exception $ex) {
            $message_text = 'There were some issue with the Refund payment. Please try again later.';
            $msg_type = "error";

            \Session::put($msg_type, $message_text);
            return true;
        }

        $message_text ='Refund Successfully';
        $msg_type = "success";

        $user_id = $transaction_data['user_id'];

        $transaction = new Transaction();

        $transaction->admin_id = Auth::user()->id;
        $transaction->user_id = $user_id;
        $transaction->transactions_value = $amount;
        $transaction->transactions_comments = 'Refund Payments';
//        $transaction->transactionauthid = $tresponse->getTransId();
        $transaction->transactions_response = $refundedSale->toJSON();
        $transaction->transaction_status = 2;
        $transaction->accept = 1;
        $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "PayPal" );

        $transaction->save();

        Transaction::where('transaction_id', $request->id)
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

        //Slack::send("User $email Refund Payments $$amount using the PayPal :)");

        //New Refund Email For users
        $data = array(
            'name' => $buyer_name,
            'value' => $amount
        );

        Mail::send(['text'=>'Mail.refund'], $data, function($message) use($email, $buyer_name) {
            $message->to($email, $buyer_name)->subject('Refund Payments');
            $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
        });

        \Session::put($msg_type, $message_text);

        return true;
    }
}
