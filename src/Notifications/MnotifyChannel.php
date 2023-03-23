<?php

namespace Velstack\Mnotify\Notifications;

use Exception;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Velstack\Mnotify\Notifications\Notify;
use Velstack\Mnotify\Traits\Setters;

class MnotifyChannel
{
    use Setters;

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
            $response = Notify::sendQuick([$phone], $message->content());
            return  $response;
        }catch (Exception $exception){
            Log::info("Pusher response => $exception");
            throw $exception;
        }
    }

}
