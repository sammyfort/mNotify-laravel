<?php


namespace Velstack\Mnotify\Notifications;


use Velstack\Mnotify\Traits\Campaign;
use Velstack\Mnotify\Traits\Commands;
use Velstack\Mnotify\Traits\Contact;
use Velstack\Mnotify\Traits\Group;
use Velstack\Mnotify\Traits\Report;
use Velstack\Mnotify\Traits\Request;


class Notify
{
    use Request, Commands;
    use   Campaign {
        individuals as public sendQuickSMS;
        fromTemplate as public sendSMSFromTemplate;
        toAuth as public notify;
        quickVoice as public sendQuickVoiceCall;
        newId as public registerSenderId;
    }
    use Group {
        sms  as public sendGroupQuickSMS;
        call as public sendGroupVoiceCall;
        get as public getAllGroups;
        specific as public getASpecificGroup;
        add as public addNewGroup;
        update as public updateGroup;
        delete as public deleteGroup;
    }

    use Contact {
        getAll as public getAllContact;
        groupContact as public getAllGroupContacts;
        specificContact as public getASpecificContact;
        newContact as public addNewContact;
        modify as public updateContact;
        del as public deleteContact;
    }

    use Report {
        smsBal as public checkSMSBalance;
        voiceBal as public checkVoiceBalance;
        smsDelivery as public checkSMSDelivery;
    }



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
        return 'https://api.mnotify.com/api/campaign';

    }

    private static function VoiceBalanceURL(): string
    {
        return 'https://api.mnotify.com/api/balance/voice';

    }

    protected static function apiKey()
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
        return false ?? true;
    }

    private static function registerSenderIdURL(): string
    {
        return 'https://api.mnotify.com/api/senderid/register';
    }



}
