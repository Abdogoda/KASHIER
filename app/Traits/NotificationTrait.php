<?php
namespace App\Traits;

use App\Models\FundAccount;
use App\Models\Notification;

trait NotificationTrait{
 
    public function addNotification($type, $message){
        $notification =  Notification::create([
            'type' => $type,
            'message' => $message,
        ]);
        
        return $notification;
    }
}