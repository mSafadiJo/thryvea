<?php

namespace App\Http\Controllers;

use App\TotalAmount;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Session;
use Stripe;
use Slack;
use App\User;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('Buyers.Payment.stripe');
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function generate_token(Request $request){
        $stripe_secret = config('services.stripe.secret', '');
        if (!empty($request['stripe_secret'])) {
            $stripe_secret = $request['stripe_secret'];
        }
        try {
            Stripe\Stripe::setApiKey($stripe_secret);
            $charge = Stripe\Token::create([
                'card' => [
                    'number' => $request['cardNumber'],
                    'exp_month' => $request['expiration-month'],
                    'exp_year' => $request['expiration-year'],
                    'cvc' => $request['cvv'],
                ],
            ]);

            $request['value'] = $request['amount'];
            $request['stripeToken'] = $charge['id'];
            $this->stripePost($request);
        } catch (\Exception $e) {
            \Session::put('error', $e->getMessage());
            return true;
        }
    }

    public function stripePost(Request $request)
    {
        $TodayDate = date("Y-m-d");
        $user_id = Auth::user()->id;
        if (!empty($request->user_id)) {
            $user_id = $request->user_id;
        }

        $Check_transactions = Transaction::where('user_id', $user_id)
            ->where('created_at', "like", "%" . $TodayDate . "%")
            ->where('payment_id', $request['payment_id'])
            ->where('payment_type', "Stripe")
            ->count();

        if ($Check_transactions < 3) {
            $transaction = new Transaction();
            $payment_id = $request->payment_id;

            $stripe_secret = config('services.stripe.secret', '');
            if (!empty($request['stripe_secret'])) {
                $stripe_secret = $request['stripe_secret'];
            }

            try {
                Stripe\Stripe::setApiKey($stripe_secret);
                $charge = Stripe\Charge::create([
                    "amount" => $request->value * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "payment from " . $_SERVER['SERVER_NAME']
                ]);

            } catch (\Exception $e) {
                \Session::put('error', $e->getMessage());

                $transaction->user_id = $user_id;
                $transaction->transactions_value = $request['value'];
                $transaction->payment_id = $payment_id;
                $transaction->transactions_comments = 'Credit Accumulation';
                $transaction->transactionauthid = "";
                $transaction->transactions_response = json_encode($e->getMessage());
                $transaction->accept = 2;
                $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Stripe");
                if (!empty($request->user_id)) {
                    $transaction->admin_id = Auth::user()->id;
                }
                $transaction->save();

                return false;
            }

            if ($charge['status'] != 'succeeded') {
                \Session::put('error', "Payment Failed, Customer’s payment was declined by card network or otherwise expired");

                $transaction->user_id = $user_id;
                $transaction->transactions_value = $request['value'];
                $transaction->payment_id = $payment_id;
                $transaction->transactions_comments = 'Credit Accumulation';
                $transaction->transactionauthid = $charge['id'];
                $transaction->transactions_response = json_encode($charge);
                $transaction->accept = 2;
                $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Stripe");
                if (!empty($request->user_id)) {
                    $transaction->admin_id = Auth::user()->id;
                }
                $transaction->save();

                return false;
            }

            $trx_id = $charge['id'];

            $transaction->user_id = $user_id;
            $transaction->transactions_value = $request['value'];
            $transaction->payment_id = $payment_id;
            $transaction->transactions_comments = 'Credit Accumulation';
            $transaction->transactionauthid = $trx_id;
            $transaction->transactions_response = json_encode($charge);
            $transaction->accept = 1;
            $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Stripe");
            if (!empty($request->user_id)) {
                $transaction->admin_id = Auth::user()->id;
            }

            $transaction->save();

            $totalAmount = TotalAmount::where('user_id', $user_id)->first('total_amounts_value');

            if (empty($totalAmount)) {
                $addtotalAmount = new TotalAmount();

                $addtotalAmount->user_id = $user_id;
                $addtotalAmount->total_amounts_value = $request['value'];

                $addtotalAmount->save();
            } else {
                $total = $request['value'] + $totalAmount['total_amounts_value'];
                TotalAmount::where('user_id', $user_id)
                    ->update(['total_amounts_value' => $total]);
            }

            \Session::put('success', "Payment successful!, Transaction Id: $trx_id :)");

            $user_data = User::where('id', $user_id)->first(['email', 'username']);
            $email = $user_data->email;
            $buyer_name = $user_data->username;
            $amount = $request->value;

            Slack::send("User $email paid $$amount using the Stripe, Transaction Id: $trx_id :)");

            //New Payments Email For users
            $data = array(
                'name' => $buyer_name,
                'value' => $amount
            );

            Mail::send(['text'=>'Mail.payment'], $data, function($message) use($email, $buyer_name) {
                $message->to($email, $buyer_name)->subject('New Payments');
                $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
            });

            return true;
        } else {
            \Session::put('error', "Payment Failed, You can't use the same card for more than one payment a day!");
            return false;
        }

    }

    public function auto_pay($merchant_account, $user_id, $amount, $user_payment, $stripe_secret){
        $visa_paymant = $user_payment['payment_num_trx'] . $user_payment['payment_visa_last4char'] . $user_payment['payment_total_ammount'] . $user_payment['payment_visa_number'];
        $is_failed = 0;
        $error_msg = "";
        try {
            Stripe\Stripe::setApiKey($stripe_secret);
            $charge = Stripe\Token::create([
                'card' => [
                    'number' => $visa_paymant,
                    'exp_month' => $user_payment['payment_exp_month'],
                    'exp_year' => $user_payment['payment_exp_year'],
                    'cvc' => $user_payment['payment_cvv']
                ],
            ]);

            $stripeToken = $charge['id'];
        } catch (\Exception $e) {
            $is_failed = 1;
            $error_msg = json_encode($e->getMessage());
        }

        try {
            Stripe\Stripe::setApiKey($stripe_secret);
            $charge = Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => "usd",
                "source" => $stripeToken,
                "description" => "payment from " . $_SERVER['SERVER_NAME']
            ]);
        } catch (\Exception $e) {
            $is_failed = 1;
            $error_msg = json_encode($e->getMessage());
        }

        if ($charge['status'] != 'succeeded') {
            $is_failed = 1;
            $error_msg = json_encode($charge);
        }

        $transaction = new Transaction();

        if($is_failed == 1){
            $transaction->user_id = $user_id;
            $transaction->transactions_value = $amount;
            $transaction->payment_id = $user_payment['payment_id'];
            $transaction->transactions_comments = 'Auto Credit Accumulation';
            $transaction->transactionauthid = $charge['id'];
            $transaction->transactions_response = $error_msg;
            $transaction->accept = 2;
            $transaction->payment_type = $merchant_account;
            $transaction->save();

            $user_data = User::where('id', $user_id)->first(['email', 'username']);
            $email = $user_data->email;
            Slack::send("User $email, Auto Charge failed!");

            return false;
        }

        $trx_id = $charge['id'];

        $transaction->user_id = $user_id;
        $transaction->transactions_value = $amount;
        $transaction->payment_id = $user_payment['payment_id'];
        $transaction->transactions_comments = 'Auto Credit Accumulation';
        $transaction->transactionauthid = $trx_id;
        $transaction->transactions_response = json_encode($charge);
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

        Slack::send("User $email paid $$amount using the Stripe, Transaction Id: $trx_id :)");

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

    public function refund_payments(Request $request)
    {
        $input = $request->input();
        $refund = array();
        $id = $input['id'];
        $amount = $input['amount'];

        $transaction_data = Transaction::join('payments','payments.payment_id','=','transactions.payment_id')
            ->where('transaction_id', $id)
            ->first([
                'transactions.*', 'payments.payment_visa_number', 'payments.payment_exp_month', 'payments.payment_exp_year'
            ]);

        $stripe_secret = config('services.stripe.secret', '');
        if (!empty($request['stripe_secret'])) {
            $stripe_secret = $request['stripe_secret'];
        }

        try {
            Stripe\Stripe::setApiKey($stripe_secret);
            $refund = Stripe\Refund::create([
                'amount' => $amount * 100,
                'charge' => $transaction_data->transactionauthid,
            ]);

        } catch ( \Exception $e ) {
            \Session::put('error', $e->getMessage());
            return true;
        }

        \Session::put('success', 'Refund successful!');

        $user_id = $transaction_data['user_id'];

        $transaction = new Transaction();

        $transaction->admin_id = Auth::user()->id;
        $transaction->user_id = $user_id;
        $transaction->transactions_value = $amount;
        $transaction->payment_id = $transaction_data['payment_id'];
        $transaction->transactions_comments = 'Refund Payments';
        $transaction->transactionauthid = $refund['id'];
        $transaction->transactions_response = json_encode($refund);
        $transaction->transaction_status = 2;
        $transaction->accept = 1;
        $transaction->payment_type = (!empty($request['payment_type']) ? $request['payment_type'] : "Stripe");

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
        $trx_id = $refund['id'];

        Slack::send("User $email Refund Payments $$amount using the Stripe, Transaction Id: $trx_id :)");

        //New Refund Email For users
        $data = array(
            'name' => $buyer_name,
            'value' => $amount
        );

        Mail::send(['text'=>'Mail.refund'], $data, function($message) use($email, $buyer_name) {
            $message->to($email, $buyer_name)->subject('Refund Payments');
            $message->from(config('mail.from.address', ''),config('mail.from.name', ''));
        });

        return true;
    }
}
