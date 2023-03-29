<?php


namespace Velstack\Mnotify\Notifications;


use Exception;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class VelstackChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'setMnotifyColumnForSMS'))
        {
            $phone = $notifiable->setMnotifyColumnForSMS($notifiable);
        }
        elseif ($notifiable instanceof  AnonymousNotifiable)
        {
            $phone = $notifiable->routeNotificationFor('velstack');
        }
        else
        {
            $phone = $notifiable->phone;
        }


        if (is_null($phone))
        {
            return  response()->json('Destination phone is empty', 400);
        }

        $message = $notification->toVelstack($notifiable);


        try
        {
            $response = Notify::customSMS($message->senderId(), [$phone], $message->content());
            return  $response;
        }catch (Exception $exception)
        {
            Log::info("response => $exception");
            throw $exception;
        }
    }


}
