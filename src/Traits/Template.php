<?php


namespace Velstack\Mnotify\Traits;


trait Template
{

    protected static function messageTemplates()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::templateURL()));
        return json_decode($response);
    }

    protected static function singleTemplate(int $id)
    {
        $response = self::getRequest(self::templateURL()."/$id?key=".self::apiKey());
        return json_decode($response);
    }

    protected static function addMgsTemplate(string $title, $content)
    {
        $data = [
            'title' => $title,
            'content' => $content
        ];
        $response = self::postRequest(self::bindParamsToEndPoint(self::templateURL()), $data);
        return json_decode($response);
    }

    protected static function putMsgTemplate(int $id, string $title, $content)
    {
        $data = [
            'id' => $id,
            'title' => $title,
            'content' => $content ,
        ];

        $response = self::putRequest(self::templateURL()."/$id?key=".self::apiKey(), $data);
        return json_decode($response);
    }

    protected static function deleteMsgTemplate(int $id)
    {
        $response = self::deleteRequest(self::templateURL()."/$id?key=".self::apiKey());
        return json_decode($response);
    }

}
