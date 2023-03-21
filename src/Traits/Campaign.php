<?php


namespace Velstack\Mnotify\Traits;


use App\Models\User;
use Velstack\Mnotify\Notification\MnotifyMessage;

trait Campaign
{
    use Requests, Commands;

    public static function sendQuick(string|array $recipients, $message=null)
    {
        $def = new MnotifyMessage;
        $url = self::bindParamsToEndPoint(self::quickSMSURL());
        $data = [
            'recipient' => $recipients,
            'sender' => self::senderId(),
            'message' => $message ?? $def->message($message),
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];
        $response = self::postRequest($url,$data);
        return $response;
    }

    public static function notify(string $message){
        $object = User::findOrFail(auth()->id());
        if (method_exists($object, 'setMnotifyColumnForSMS')) {
            $phone = $object->setMnotifyColumnForSMS($object);
        } else {
            $phone = $object->phone;
        }
        if (!is_array($phone)){
            $phone = array($phone);
        }

        $data = [
            'recipient' => $phone,
            'sender' => self::senderId(),
            'message' => $message,
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];
        $response = self::postRequest(self::bindParamsToEndPoint(self::quickSMSURL()),$data);
        return $response;
    }

    public static function sendGroupSMS(array $id, $message_id=null)
    {
        $def = new MnotifyMessage();
        $url = self::bindParamsToEndPoint(self::groupSMSURL());
        $data = [
            'group_id' => $id,
            'sender' => self::senderId(),
            'message_id' => $message_id ?? $def->message($message_id),
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];
        $response = self::postRequest($url,$data);
        return $response;
    }

    public static function sendVoiceCall(string $campaign_message, array|string $recipients, $file)
    {
        $data = [
            'campaign' => $campaign_message,
            'recipient' => $recipients,
            'file' => $file,
            'voice_id' => '',
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];
        $response = self::postMediaRequest(self::bindParamsToEndPoint(self::quickVoiceCallURL()), $data);
        return $response;
    }



}
