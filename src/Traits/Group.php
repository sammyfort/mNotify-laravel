<?php


namespace Velstack\Mnotify\Traits;


use Velstack\Mnotify\Notifications\MnotifyMessage;

trait Group
{



    protected static function groups()
    {
        $response = self::getRequest(self::getGroupURL()."/?key=". self::apiKey());
        return json_decode($response);
    }

    protected static function singleGroup(int $id){
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
