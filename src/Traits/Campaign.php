<?php


namespace Velstack\Mnotify\Traits;


use App\Models\User;
use Velstack\Mnotify\Notifications\MnotifyMessage;


trait Campaign
{
    protected  static function quickSMS(string|array $recipients, $message=null)
    {
        $def = new MnotifyMessage;
        if (!is_array($recipients)){
            $recipients = array($recipients);
        }
        $data = [
            'recipient' => $recipients,
            'sender' => self::senderId(),
            'message' => $message ?? $def->message($message),
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];
        $response = self::postRequest(self::bindParamsToEndPoint(self::quickSMSURL()),$data);
        return json_decode($response);
    }


    protected static function toAuth(string $message)
    {
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
        return  ($response);
    }



    protected static function quickVoice(string $campaign_message, array $recipients, $file)
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
        return  ($response);
    }

    protected static function groupSMS(array|string $group_id, int $message_id)
    {
        $data = [
            'group_id' => $group_id,
            'sender' => self::senderId(),
            'message_id' => $message_id ,
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];

        $response = self::postRequest(self::bindParamsToEndPoint(self::groupSMSURL()),$data);
        return  $response;
    }

    protected static function groupCall(string $campaign_message, array $group_id, $file_path,  string $voice_id)
    {
        $data = [
            'campaign' => $campaign_message,
            'group_id' => $group_id,
            'file' => $file_path,
            'voice_id' => $voice_id,
            'is_schedule' =>self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null
        ];
        $response = self::postMediaRequest(self::bindParamsToEndPoint(self::groupVoiceCallURL()), $data);
        return  $response;
    }



    protected static function newId(string $sender_name, string $purpose)
    {
        $data = [
            'sender_name' => $sender_name,
            'purpose' => $purpose
        ];
        $response = self::postRequest(self::bindParamsToEndPoint(self::registerSenderIdURL()), $data);
        return json_decode($response);

    }



}
