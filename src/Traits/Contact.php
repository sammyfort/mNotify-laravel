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
        $response = self::getRequest(self::contactGroupURL()."/$groupId?key=".self::apiKey());
        return json_decode($response);

    }


    public static function getASpecificContact(int $id)
    {
        $response = self::getRequest(self::getContactURL()."/$id?key=".self::apiKey());
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

        $response = self::postRequest(self::getContactURL()."/$groupId?key=".self::apiKey(), $data);
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

        $response = self::putRequest(self::getContactURL()."/$id?key=".self::apiKey(),$data);

        return json_decode($response);
    }

    public static function deleteContact(int $id, int $groupId)
    {
        $response = self::deleteRequest(self::getContactURL()."/$id/$groupId?key=".self::apiKey());
         return json_decode($response);
    }



}
