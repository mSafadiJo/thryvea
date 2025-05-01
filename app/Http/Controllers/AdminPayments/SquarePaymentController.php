<?php

namespace App\Http\Controllers\AdminPayments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SquarePaymentController extends Controller
{
    public function charge(){
        //now for configure square you required
        $accessToken = 'xxxxx'; // from square dashboard
        $locationId = 'xxxxx'; // from square dashboard
        $defaultApiConfig = new \SquareConnect\Configuration();

        $defaultApiConfig->setHost("https://connect.squareupsandbox.com"); // for testing in sandbox
//        $defaultApiConfig->setHost("https://connect.squareup.com");

        $defaultApiConfig->setAccessToken($accessToken);
        $defaultApiClient = new \SquareConnect\ApiClient($defaultApiConfig); //need to pass in all api


        //add customer with card at Square server
        $name = 'xxxxx';
        $email = 'xxx@gmail.com';
        $streetAddress ='xxxx';

        $customerAddress = new \SquareConnect\Model\Address();
        $customerAddress->setAddressLine1($streetAddress);

        $customer = new \SquareConnect\Model\CreateCustomerRequest();
        $customer->setGivenName($name);
        $customer->setEmailAddress($email);
        $customer->setAddress($customerAddress);


        $customersApi = new \SquareConnect\Api\CustomersApi($defaultApiClient);
        $result = $customersApi->createCustomer($customer);
        $customerId = $result->getCustomer()->getId(); //save this customerId


        //now add card with customer
        $customerId = 'xxxx';
        $body = new \SquareConnect\Model\CreateCustomerCardRequest();
        $body->setCardNonce($cardNonce); // nonce get from SqPaymentForm one time token

        $customersApi = new \SquareConnect\Api\CustomersApi($this->defaultApiClient);
        $result = $customersApi->createCustomerCard($customerId, $body);

        $card_id = $result->getCard()->getId(); // save this card_id for take payment
        $card_brand = $result->getCard()->getCardBrand();
        $card_last_four = $result->getCard()->getLast4();
        $card_exp_month = $result->getCard()->getExpMonth();
        $card_exp_year = $result->getCard()->getExpYear();


        //charge customer
        $payment_body = new \SquareConnect\Model\CreatePaymentRequest();
        $amountMoney = new \SquareConnect\Model\Money();

        # Monetary amounts are specified in the smallest unit of the applicable currency.
        # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
        $amountMoney->setAmount(100);
        $amountMoney->setCurrency("USD");
        $payment_body->setCustomerId($customerId);
        $payment_body->setSourceId($cardId);
        $payment_body->setAmountMoney($amountMoney);
        $payment_body->setLocationId($this->locationId);

        # Every payment you process with the SDK must have a unique idempotency key.
        # If you're unsure whether a particular payment succeeded, you can reattempt
        # it with the same idempotency key without worrying about double charging
        # the buyer.
        $payment_body->setIdempotencyKey(uniqid());

        $paymentsApi = new \SquareConnect\Api\PaymentsApi($this->defaultApiClient);
        $result = $paymentsApi->createPayment($payment_body);
        $transactionId = $result->getPayment()->getId(); //save this transactionId


    }
}
