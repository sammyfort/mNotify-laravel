<?php


namespace Velstack\Mnotify\Traits;


use Velstack\Mnotify\Notifications\MnotifyMessage;

trait Group
{
    protected static function sms(array $group_id, $message_id=null)
    {
        $def = new MnotifyMessage();
        $data = [
            'group_id' => $group_id,
            'sender' => self::senderId(),
            'message_id' => $message_id ?? $def->message($message_id),
            'is_schedule' => self::isSchedule(),
            'schedule_date' => self::isSchedule() ?? null,
        ];

        $response = self::postRequest(self::bindParamsToEndPoint(self::groupSMSURL()),$data);
        return  $response;
    }

    protected static function call(string $campaign_message, array $group_id, $file_path,  string $voice_id)
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


    protected static function get()
    {
        $response = self::getRequest(self::getGroupURL()."/?key=". self::apiKey());
        return json_decode($response);
    }

    protected static function specific(int $id){
        $response = self::getRequest(self::getGroupURL(). "/$id?key=". self::apiKey());
        return json_decode($response);
    }

    protected static function add($group_name)
    {
        $data = [
            'group_name' => $group_name
        ];

        $response = self::postRequest(self::bindParamsToEndPoint(self::getGroupURL()),$data);
        return json_decode($response);
    }

    protected static  function update(string $title, int $id)
    {
        $data = [
            'title' => $title,
            'id' => $id
        ];

        $response = self::putRequest(self::getGroupURL()."/$id?key=".self::apiKey() , $data);
        return   ($response);
    }

    protected static function delete(int $group_id)
    {
        $response = self::deleteRequest(self::getGroupURL()."/$group_id?key=". self::apiKey());
        return  json_decode($response);
    }



}
