<?php
namespace App\Http\Controllers;

use App\TotalAmount;
use App\Payment;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Omnipay\Omnipay;

class PayPalProPaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function paypalPro(Request $request){
        $gateway = Omnipay::create('PayPal_Pro');
        $gateway->setUsername(config('services.paypalpro.PAYPAL_USERNAME', ''));
        $gateway->setPassword(config('services.paypalpro.PAYPAL_PASSWORD', ''));
        $gateway->setSignature(config('services.paypalpro.PAYPAL_SIGNATURE', ''));
        $gateway->setTestMode(config('services.paypalpro.PAYPAL_TESTMODE', '')); // here 'true' is for sandbox. Pass 'false' when go live

        $payment_id = '';
        if( !empty($request->payment_id) ) {
            $payment_id = $request->payment_id;
        } else {
            if( $request->paymentMethod == 'other'){
                return redirect('/Transaction/Value/Create/'.$request->value);
            }
            $payment_id = $request->paymentMethod;
        }

        $payments = Payment::where('payment_id', $payment_id)
            ->first([
                'payment_visa_number', 'payment_cvv',
                'payment_exp_month', 'payment_exp_year'
            ]);

        $formData = array(
            'firstName' => Auth::user()->user_first_name,
            'lastName' => Auth::user()->user_last_name,
            'number' => $payments['payment_visa_number'],
            'expiryMonth' => $payments['payment_exp_month'],
            'expiryYear' => $payments['payment_exp_year'],
            'cvv' => $payments['payment_cvv']
        );

        try {
            // Send purchase request
            $response = $gateway->purchase(
                [
                    'amount' => '1',
                    'currency' => 'USD',
                    'card' => $formData
                ]
            )->send();

            // Process response
            if ($response->isSuccessful()) {

                // Payment was successful
                //echo "Payment is successful. Your Transaction ID is: ". $response->getTransactionReference();

                Session::flash('success', 'Payment successful!');

                $transaction = new Transaction();

                $transaction->user_id = Auth::user()->id;
                $transaction->transactions_value = $request['value'];
                $transaction->payment_id = $payment_id;
                $transaction->transactions_comments = 'Credit Accumulation';

                $transaction->save();

                $totalAmmount = TotalAmount::where('user_id', Auth::user()->id)->first('total_amounts_value');

                if( empty($totalAmmount) ){
                    $addtotalAmmount = new TotalAmount();

                    $addtotalAmmount->user_id = Auth::user()->id;
                    $addtotalAmmount->total_amounts_value = $request['value'];

                    $addtotalAmmount->save();
                } else {
                    $total = $request['value'] + $totalAmmount['total_amounts_value'];
                    $updatetotalAmmount = TotalAmount::where('user_id', Auth::user()->id)
                        ->update([ 'total_amounts_value' => $total ]);
                }

                if( !empty($request->payment_id) ) {
                    return redirect()->route('BuyersPayPayment');
                } else {
                    return back();
                }

            } else {
                // Payment failed
//                echo "Payment failed. ". $response->getMessage();
                Session::flash('error', "Payment failed. ". $response->getMessage());
                if( !empty($request->payment_id) ) {
                    return redirect()->route('Transaction.Value.Create', $request->value);
                } else {
                    return back();
                }
            }
        } catch(Exception $e) {
            Session::flash('error', $e->getMessage());
            if( !empty($request->payment_id) ) {
                return redirect()->route('Transaction.Value.Create', $request->value);
            } else {
                return back();
            }
//            echo $e->getMessage();
        }
    }
}
