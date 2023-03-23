<?php


namespace Velstack\Mnotify\Traits;


trait Report
{

    public static function checkSMSBalance()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::SMSBalanceURL()));
        return json_decode($response);
    }

    public static function checkVoiceBalance()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::VoiceBalanceURL()));
        return json_decode($response);
    }

    public static function checkSMSDelivery(int $_id)
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::SMSDeliveryReportURL()."/$_id"));
        return json_decode($response);
    }

}
