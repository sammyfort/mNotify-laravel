<?php


namespace Velstack\Mnotify\Configurations\Traits;


use Velstack\Mnotify\Notifications\MnotifyMessage;

trait Groups
{
    use Requests, Commands;


    public static function sendSMS(array $group_id, $message_id=null)
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

    public static function sendVoiceCall(string $campaign_message, array $group_id, $file_path,  string $voice_id, )
    {
        $data = [
            'campaign' => $campaign_message,
            'group_id' => $group_id,
            'file' => $file_path,
            'voice_id' => $voice_id,
            'is_schedule' =>static::isSchedule(),
            'schedule_date' => static::isSchedule() ?? null
        ];
        $response = self::postMediaRequest(static::bindParamsToEndPoint(static::groupVoiceCallURL()), $data);
        return json_decode($response);
    }


    public static function getAll()
    {
        $response = self::getRequest(self::getGroupURL()."/?key=". self::apiKey());
        return json_decode($response);
    }

    public static function getSpecific(int $id){
        $response = self::getRequest(self::getGroupURL(). "/$id?key=". self::apiKey());
        return json_decode($response);
    }

    public static function add($group_name)
    {
        $data = [
            'group_name' => $group_name
        ];

        $response = self::postRequest(self::bindParamsToEndPoint(self::getGroupURL()),$data);
        return json_decode($response);
    }

    public static  function update( string $title, int $id)
    {
        $data = [
            'title' => $title,
            'id' => $id
        ];

        $response = self::putRequest(self::getGroupURL()."/$id?key". self::apiKey(), $data);
        return json_decode($response);
    }

    public static function delete(int $group_id)
    {
        $response = self::deleteRequest(self::getGroupURL()."/$group_id?key". self::apiKey());
        return json_decode($response);
    }


}
