<?php


namespace Velstack\Mnotify\Traits;


trait Contact
{


    protected static function getAll()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::getContactURL()));
        return json_decode($response);
    }

    protected static function groupContact(int $groupId)
    {
        $response = self::getRequest(self::contactGroupURL()."/$groupId?key=".self::apiKey());
        return json_decode($response);

    }


    protected static function specificContact(int $id)
    {
        $response = self::getRequest(self::getContactURL()."/$id?key=".self::apiKey());
        return json_decode($response);
    }

    protected static function newContact(int $groupId, string $phone, string $title, string $firstname, string $lastname, string $email, string $dob)
    {
        $data = [
            'phone' => $phone,
            'title' => $title,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'dob' => $dob
        ];

        $response = self::postRequest( self::getContactURL()."/$groupId?key=".self::apiKey(), $data);
        return  $response;
    }

    protected static function modify(int $id, int $groupId, string $phone, string $title, string $firstname, string $lastname, string $email, $dob)
    {
        $data = [
            'id' => $id,
            'group_id' => $groupId,
            'phone' => $phone,
            'title' => $title,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'dob' => $dob
        ];

        $response = self::putRequest(self::getContactURL()."/$id?key=".self::apiKey(),$data);

        return json_decode($response);
    }

    protected static function del(int $id, int $groupId)
    {
        $response = self::deleteRequest(self::getContactURL()."/$id/$groupId?key=".self::apiKey());
        return json_decode($response);
    }


}
