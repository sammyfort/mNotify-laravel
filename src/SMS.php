<?php


namespace Velstack\Mnotify;


use Velstack\Mnotify\VelstackProjects\Configurations\Traits\Campaign;
use Velstack\Mnotify\VelstackProjects\Configurations\Traits\Commands;
use Velstack\Mnotify\VelstackProjects\Configurations\Traits\Group;
use Velstack\Mnotify\VelstackProjects\Configurations\Traits\Contact;

class SMS
{
    use Commands, Contact, Campaign, Group;

    private static function quickSMSURL(): string{
        return 'https://api.mnotify.com/api/sms/quick';
    }

    private static function groupSMSURL(): string{
        return  'https://api.mnotify.com/api/sms/group';
    }

    private static function quickVoiceCallURL(): string{
        return  'https://api.mnotify.com/api/voice/quick';
    }

    private static function groupVoiceCallURL(): string{
        return  'https://api.mnotify.com/api/voice/quick';
    }

    private static function getGroupURL(): string{
        return 'https://api.mnotify.com/api/group';
    }

    private static function getContactURL(): string{
        return  'https://api.mnotify.com/api/contact';
    }

    private static function templateURL(): string{
        return  'https://api.mnotify.com/api/template';
    }

    private static function apiKey(){
        return config('mnotify.API_KEY');
    }

    private static function senderId(){
        return config('mnotify.SENDER_ID');
    }

    private static function isSchedule(): bool
    {
        return false;
    }






}
