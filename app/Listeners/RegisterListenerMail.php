<?php

namespace App\Listeners;

use App\Events\RegisterEventMail;
use App\Mail\ContactResponseMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use modules\Customers\Models\Customer;

class RegisterListenerMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RegisterEventMail $event)
    {
//        $customer = auth('sanctum')->user();
//        Mail::to($customer)->send(new ContactResponseMail($event->customer));

//        $productId = DB::getPdo()->lastInsertId();
//        $customer = Customer::get()->inserted()->lastInsertId();
//        $customerId = DB::getPdo()->lastInsertId();
//        $customer = Customer::find($customerId);
        Mail::to($event->customer->email)->send(new ContactResponseMail($event->customer));
    }
}
