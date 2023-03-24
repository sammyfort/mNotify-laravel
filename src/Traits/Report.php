<?php


namespace Velstack\Mnotify\Traits;


trait Report
{

    protected static function smsBal()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::SMSBalanceURL()));
        return json_decode($response);
    }

    protected static function voiceBal()
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::VoiceBalanceURL()));
        return  $response;
    }

    protected static function smsDelivery(int $sms_id)
    {
        $response = self::getRequest(self::bindParamsToEndPoint(self::SMSDeliveryReportURL()."/$sms_id"));
        return json_decode($response);
    }

}
