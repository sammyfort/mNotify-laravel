<?php

namespace Velstack\Mnotify\Configurations\Resources;

use Velstack\Mnotify\Configurations\Traits\Campaign;
use Velstack\Mnotify\Configurations\Traits\Commands;
use Velstack\Mnotify\Configurations\Traits\Contacts;
use Velstack\Mnotify\Configurations\Traits\Groups;
use Velstack\Mnotify\Configurations\Traits\Reports;

class Setters
{
    use Commands, Contacts, Campaign, Groups, Reports;

    private static function quickSMSURL(): string
    {
        return 'https://api.mnotify.com/api/sms/quick';
    }

    private static function groupSMSURL(): string
    {
        return 'https://api.mnotify.com/api/sms/group';
    }

    private static function quickVoiceCallURL(): string
    {
        return 'https://api.mnotify.com/api/voice/quick';
    }

    private static function groupVoiceCallURL(): string
    {
        return 'https://api.mnotify.com/api/voice/quick';
    }

    private static function getGroupURL(): string
    {
        return 'https://api.mnotify.com/api/group';
    }

    private static function getContactURL(): string
    {
        return 'https://api.mnotify.com/api/contact';
    }

    private static function templateURL(): string
    {
        return 'https://api.mnotify.com/api/template';
    }

    private static function SMSBalanceURL(): string
    {
        return 'https://api.mnotify.com/api/balance/sms';

    }

    private static function SMSDeliveryReportURL(): string
    {
        return 'https://api.mnotify.com/api/campaign/7FE4A62A-96EB-4755-BC57-000A38C8C6EF';

    }


    private static function VoiceBalanceURL(): string
    {
        return 'https://api.mnotify.com/api/balance/voice';

    }


    private static function apiKey()
    {
        return config('mnotify.API_KEY');
    }

    private static function senderId()
    {
        return config('mnotify.SENDER_ID');
    }

    private static function contactGroupURL(): string
    {
        return 'https://api.mnotify.com/api/contact/group';
    }

    private static function isSchedule(): bool
    {
        return false;
    }

    private static function registerSenderIdURL(): string
    {
        return 'https://api.mnotify.com/api/senderid/register';
    }


}
