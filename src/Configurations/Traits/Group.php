<?php


namespace Velstack\Mnotify\Configurations\Traits;


trait Group
{
    use Requests;

    public static function getAllGroups()
    {
        $response = self::getRequest(self::getGroupURL()."/?key=". self::apiKey());
        return json_decode($response);
    }

    public static function getASpecificGroup(int $id){
        $response = self::getRequest(self::getGroupURL(). "/$id?key=". self::apiKey());
        return json_decode($response);
    }



}
