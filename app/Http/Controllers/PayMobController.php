<?php

namespace App\Http\Controllers;
use modules\Orders\Models\Order;
use PayMob\Facades\PayMob;

use Illuminate\Http\Request;

class PayMobController extends Controller
{
    //
    public function index()
    {
        $orderData= Order::where('customer_id',auth()->user()->id)->where('status', 0)->first();
//        dd(auth()->user()->id);
//        dd($orderData);
        $auth = PayMob::AuthenticationRequest();
//        dd($auth->token);
        $order = PayMob::OrderRegistrationAPI([
            'auth_token' => $auth->token,
            'amount_cents' => 100, //put your price
            'currency' => 'EGP',
            'delivery_needed' => false, // another option true
            'merchant_order_id' => time(), //put order id from your database must be unique id
            'items' => [[ // all items information
                "name" => "ASC1515",
                "amount_cents" => 150 * 100,
                "description" => "Smart Watch",
                "quantity" => "2"
            ]]
        ]);
        $PaymentKey = PayMob::PaymentKeyRequest([
            'auth_token' => $auth->token,
            'amount_cents' => ($orderData->total) * 100, //put your price
            'currency' => 'EGP',
            'order_id' => $order->id,
            "billing_data" => [ // put your client information
                "apartment" => "803",
                "email" => "claudette09@exa.com",
                "floor" => "42",
                "first_name" => "Clifford",
                "street" => "Ethan Land",
                "building" => "8028",
                "phone_number" => "+86(8)9135210487",
                "shipping_method" => "PKG",
                "postal_code" => "01898",
                "city" => "Jaskolskiburgh",
                "country" => "CR",
                "last_name" => "Nicolas",
                "state" => "Utah"
            ]
        ]);

        return view('paymob')->with(['token' => $PaymentKey->token]);

    }
}