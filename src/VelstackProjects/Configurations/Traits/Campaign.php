<?php


namespace Velstack\Mnotify\VelstackProjects\Configurations\Traits;


use App\Models\User;
use Velstack\Mnotify\Notifications\MnotifyMessage;


trait Campaign
{
    use Requests, Commands;

    public static function sendQuick(string|array $recipients, $message=null)
    {
        $def = new MnotifyMessage;
        if (!is_array($recipients)){
            $recipients = array($recipients);
        }
        $data = [
            'recipient' => $recipients,
            'sender' => static::senderId(),
            'message' => $message ?? $def->message($message),
            'is_schedule' => static::isSchedule(),
            'schedule_date' => static::isSchedule() ?? null,
        ];
        $response = self::postRequest(static::bindParamsToEndPoint(static::quickSMSURL()),$data);
        return json_decode($response);
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
            'sender' => static::senderId(),
            'message' => $message,
            'is_schedule' => static::isSchedule(),
            'schedule_date' => static::isSchedule() ?? null,
        ];
        $response = self::postRequest(static::bindParamsToEndPoint(static::quickSMSURL()),$data);
        return json_decode($response);
    }

    public static function sendGroupSMS(array $group_id, $message_id=null)
    {
        $def = new MnotifyMessage();
        $data = [
            'group_id' => $group_id,
            'sender' => static::senderId(),
            'message_id' => $message_id ?? $def->message($message_id),
            'is_schedule' => static::isSchedule(),
            'schedule_date' => static::isSchedule() ?? null,
        ];
        $response = self::postRequest(static::bindParamsToEndPoint(static::groupSMSURL()),$data);
        return json_decode($response);
    }

    public static function sendVoiceCall(string $campaign_message, array|string $recipients, $file)
    {
        $data = [
            'campaign' => $campaign_message,
            'recipient' => $recipients,
            'file' => $file,
            'voice_id' => '',
            'is_schedule' => static::isSchedule(),
            'schedule_date' => static::isSchedule() ?? null,
        ];
        $response = self::postMediaRequest(static::bindParamsToEndPoint(static::quickVoiceCallURL()), $data);
        return json_decode($response);
    }



}
