<?php

namespace Velstack\Mnotify;

use Exception;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
class MnotifyChannel
{

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'setMnotifyColumnForSMS'))
        {
            $phone = $notifiable->setMnotifyColumnForSMS($notifiable);
        }
        elseif ($notifiable instanceof  AnonymousNotifiable)
        {
            $phone = $notifiable->routeNotificationFor('mnotify');
        }
        else
        {
            $phone = $notifiable->phone;
        }

        if (is_null($phone)){
            return  response()->json('Destination phone is empty', 400);
        }

        $message = $notification->toMnotify($notifiable);


        try {
            $response = SMS::sendQuick([$phone], $message->content());
            return json_decode($response);
        }catch (Exception $exception){
            Log::info("Pusher response => $exception");
            throw $exception;
        }
    }

}
