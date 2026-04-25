<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Mail\NewOrderNotification;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public $afterCommit = true;
    
    public function created(Order $order): void
    {
        $order->load(['client.region', 'items.product', 'creator']);
        $regionId = $order->client->region_id;


        $commercials = User::where('role', 'commercial')
            ->where('region_id', $regionId)
            ->get();

        try {
            foreach ($commercials as $commercial) {
                Mail::to($commercial->email)->send(new NewOrderNotification($order));
            }

            $depotId = $order->creator->depot_id;
            $depositaires = User::where('role', 'depositaire')
                ->where('depot_id', $depotId)
                ->get();

            foreach ($depositaires as $depositaire) {
                Mail::to($depositaire->email)->send(new NewOrderNotification($order));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("SMTP Error while sending NewOrderNotification: " . $e->getMessage());
        }
    }
}
