<?php

namespace App\Http\Controllers;

use App\TotalAmount;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use Slack;

class PaymentPayPallController extends Controller
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

    public function payWithpaypal(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric'
        ]);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        \Session::put('amount', $request->get('amount') );

        if( !empty($request->get('user_id')) ){
            \Session::put('user_id', $request->get('user_id') );
        } else {
            \Session::put('user_id', "" );
        }

        $item_1 = new Item();

        $item_1->setName('Item 1') /** item name **/
        ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($request->get('amount')); /** unit price **/

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($request->get('amount'));

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Your transaction description');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('status')) /** Specify return URL **/
        ->setCancelUrl(URL::to('status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        /** dd($payment->create($this->_api_context));exit; **/
        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {

            if (\Config::get('app.debug')) {

                \Session::put('error', 'Connection timeout');
                if (!empty($request->user_id)) {
                    return redirect()->route('BuyersPayPayment');
                } else {
                    route("Admin.Buyers.payments", $request->user_id);
                }
            } else {

                \Session::put('error', 'Some error occur, sorry for inconvenient');
                if (!empty($request->user_id)) {
                    return redirect()->route('BuyersPayPayment');
                } else {
                    route("Admin.Buyers.payments", $request->user_id);
                }

            }

        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;

            }

        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            /** redirect to paypal **/
            return Redirect::away($redirect_url);

        }

        \Session::put('error', 'Unknown error occurred');
        if( !empty($request->user_id) ){
            return redirect()->route('BuyersPayPayment');
        } else {
            route("Admin.Buyers.payments", $request->user_id);
        }

    }

    public function getPaymentStatus(Request $request)
    {
        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
//        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
        if (empty($request->PayerID) || empty($request->token)) {
            \Session::put('error', 'Payment failed');
            if( Session::get('user_id') == "" ){
                return redirect()->route('BuyersPayPayment');
            } else {
                return redirect()->route("Admin.Buyers.payments", Session::get('user_id'));
            }
        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
//        $execution->setPayerId(Input::get('PayerID'));
        $execution->setPayerId($request->PayerID);

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        $transactions = $result->getTransactions();
        $resources = $transactions[0]->getRelatedResources();
        $sale = $resources[0]->getSale();
        $sale_id = $sale->id;

        if ($result->getState() == 'approved') {
            if( Session::get('user_id') == "" ){
                $user_id = Auth::user()->id;
            } else {
                $user_id = Session::get('user_id');
            }

            if ($amount = Session::get('amount')){
                DB::table('transactions')->insert([
                    'user_id' => $user_id,
                    'admin_id' => Auth::user()->id,
                    'transactions_value' => $amount,
                    'transactions_comments' => 'PayPal',
                    'transaction_paypall' => 1,
                    'transactions_response' => $result->toJSON(),
                    'transactionauthid' => $sale_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'payment_type' => (!empty($request['payment_type']) ? $request['payment_type'] : "PayPal" ),
                    'accept' => 1
                ]);

                $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

                if( empty($totalAmount) ){
                    $addtotalAmount = new TotalAmount();

                    $addtotalAmount->user_id = $user_id;
                    $addtotalAmount->total_amounts_value = $amount;

                    $addtotalAmount->save();
                } else {
                    $total = $amount + $totalAmount['total_amounts_value'];
                    TotalAmount::where('user_id', $user_id)
                        ->update([ 'total_amounts_value' => $total ]);
                }

                \Session::put('success', 'Payment success');

                $user_data = User::where('id', $user_id)->first(['email', 'username']);
                $email = $user_data->email;
                $buyer_name = $user_data->username;

                Slack::send("User $email paid $$amount using the PayPal, Transaction Id: $sale_id :)");

                //New Payments Email For users
                $data = array(
                    'name' => $buyer_name,
                    'value' => $amount
                );

                Mail::send(['text'=>'Mail.payment'], $data, function($message) use($email, $buyer_name) {
                    $message->to($email, $buyer_name)->subject('New Payments');
                    $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
                });

                if( Session::get('user_id') == "" ){
                    return redirect()->route('BuyersPayPayment');
                } else {
                    return redirect()->route("Admin.Buyers.payments", Session::get('user_id'));
                }

            }
        }

        \Session::put('error', 'Payment failed');
        if( Session::get('user_id') == "" ){
            return redirect()->route('BuyersPayPayment');
        } else {
            return redirect()->route("Admin.Buyers.payments", Session::get('user_id'));
        }

    }

}
