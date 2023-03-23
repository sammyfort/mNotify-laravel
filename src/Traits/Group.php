<?php


namespace Velstack\Mnotify\Traits;


use Velstack\Mnotify\Notifications\MnotifyMessage;

trait Group
{
    public static function sendGroupQuickSMS(array $group_id, $message_id=null)
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
        return json_decode($response);
    }

    public static function sendGroupVoiceCall(string $campaign_message, array $group_id, $file_path,  string $voice_id)
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
        return json_decode($response);
    }


    public static function getAllGroups()
    {
        $response = self::getRequest(self::getGroupURL()."/?key=". self::apiKey());
        return json_decode($response);
    }

    public static function getASpecificGroup(int $id){
        $response = self::getRequest(self::getGroupURL(). "/$id?key=". self::apiKey());
        return json_decode($response);
    }

    public static function addNewGroup($group_name)
    {
        $data = [
            'group_name' => $group_name
        ];

        $response = self::postRequest(self::bindParamsToEndPoint(self::getGroupURL()),$data);
        return json_decode($response);
    }

    public static  function updateGroup( string $title, int $id)
    {
        $data = [
            'title' => $title,
            'id' => $id
        ];

        $response = self::putRequest(self::getGroupURL()."/$id?key". self::apiKey(), $data);
        return json_decode($response);
    }

    public static function deleteGroup(int $group_id)
    {
        $response = self::deleteRequest(self::getGroupURL()."/$group_id?key". self::apiKey());
        return json_decode($response);
    }


}
