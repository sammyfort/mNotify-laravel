<?php


namespace Velstack\Mnotify\Configurations\Traits;


trait Reports
{
    use Requests, Commands;

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

    public static function checkSMSDelivery()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::SMSDeliveryReportURL()));
        return json_decode($response);
    }

}
