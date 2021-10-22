<?php

namespace App\Http\Controllers;

use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use modules\Customers\Models\Customer;
use modules\Orders\Models\Order;
use modules\Products\Models\Product;
use modules\Users\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
//    public function index()
//    {
//        return view('home');
//    }

    public function sendPushNotification($order_id,$status) {
        $order = OrderProduct::findOrFail($order_id);
        $order->status =$status;
        $order->save();
        $firebaseToken = Customer::whereNotNull('fcm_token')->pluck('fcm_token')->all();

        $SERVER_API_KEY = 'AAAA7FPQA2o:APA91bGqbvpwuR94NhyfQGOFwnfToxtKGR-ygt9FS2QTGwJa_jkr8BBhhYFfbcgUF-eO_ynhwTbUmbtEjrjEZeIynOQa5XPnWRJJuziRikZV2PJ97aINk0O0nKHCeBDaEOzEGd_YuaDy';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "success",
                "body" => "Your OrderProduct Make Succesfully",
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

//         dd($response);
        return redirect()->route('home');
    }


    public function savePushNotificationToken(Request $request){
        auth()->user()->update(['fcm_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }



    public function index(){


//        $order = OrderProduct::where('customer_id', auth()->user()->id)->where('status', 'pending')->get();
        $order = Order::with('products')->where('customer_id', auth()->user()->id)->where('status', 0)->get();
//       dd($order);
//        $orderProducts =OrderProduct::whereHas('order',function($q){
////            return $q->where('customer_id',auth()->user()->id);
//            return $q->where('customer_id',1);
//        })->get();

//        $orderProducts = DB::table('order_product')->leftJoin('products', 'order_product.product_id', '=', 'products.id')->get();
//        $orderProducts = OrderProduct::find(1);
//        dd($order);
        return view('home', compact( 'order',));
//        return view('home', compact( 'order'));
    }



    public function editOrderStatus($order_id, $status){
        $order = Order::findOrFail($order_id);
        $order->status = $status;
        $order->save();
        return redirect()->route('home');
    }


}
