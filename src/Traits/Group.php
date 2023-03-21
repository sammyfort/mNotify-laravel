<?php


namespace Velstack\Mnotify\Traits;


trait Group
{
    use Requests;

    public static function getAllGroups(){
        $response = self::getRequest(self::getGroupURL());
        return $response;
    }

    public static function getASpecificGroup(int $id){
        $response = self::getRequest(self::getGroupURL(). "/$id?key=". self::apiKey());
        return $response;
    }



}
