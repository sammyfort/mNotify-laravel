<?php


namespace Velstack\Mnotify\Traits;


trait Contact
{


    public static function getAllContact()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::getContactURL()));
        return json_decode($response);
    }

    public static function getAllGroupContacts(int $groupId)
    {
        $response = self::getRequest(static::contactGroupURL()."/$groupId?key=".static::apiKey());
        return json_decode($response);

    }


    public static function getASpecificContact(int $id)
    {
        $response = self::getRequest(static::getContactURL()."/$id?key=".static::apiKey());
        return json_decode($response);
    }

    public static function addNewContact(int $groupId, string $phone, string $title, string $firstname, string $lastname, string $email, $dob)
    {
        $data = [
            'phone' => $phone,
            'title' => $title,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'dob' => $dob
        ];

        $response = self::putRequest(static::getContactURL()."/$groupId?key=".static::apiKey(), $data);
        return json_decode($response);
    }

    public static function updateContact(int $id, int $groupId, string $phone, string $title, string $firstname, string $lastname, string $email, $dob)
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

        $response = self::putRequest(static::getContactURL()."/$id?key=".self::apiKey(),$data);

        return json_decode($response);
    }

    public static function deleteContact(int $id, int $groupId)
    {
        $response = self::deleteRequest(static::getContactURL()."/$id/$groupId?key=".self::apiKey());
        return json_decode($response);
    }



}
