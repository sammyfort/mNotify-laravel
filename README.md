<p align="center"><a href="https://mnotify.com" target="_blank"><img src="https://bms.mnotify.com/img/bms-01-text.92497312.png" width="200" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/sammyfort/mNotify-laravel"><img src="https://img.shields.io/badge/%3C%2F%3E-Laravel%20-blue" alt="Build Status"></a>
<a href="https://packagist.org/packages/velstack/mnotify"><img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/velstack/mnotify?color=41aa5e&label=Installations"></a>
<a href="https://packagist.org/packages/velstack/mnotify"><img alt="Packagist Version (custom server)" src="https://img.shields.io/packagist/v/velstack/mnotify?label=Version"></a>
<a href="https://packagist.org/packages/velstack/mnotify"><img src="https://img.shields.io/github/license/sammyfort/mNotify-laravel"></a>

 

</p>
 

## The [mNotify](https://www.mnotify.com/) package for laravel php.

## Installation

install with composer

```bash
  composer require velstack/mnotify
```

## Configuration


Run the command below to publish the `'/config/mnotify.php'` file.   

```bash
php artisan vendor:publish --tag=mnotify
```

Then get your api keys from your [mNotify](https://www.mnotify.com/) client area and set these keys in your mnotify.php file

```php
return  [

'API_KEY'=>  'your_api_key',

'SENDER_ID'=> 'your_sender_id',


//make sure SENDER_ID value is already set on your  mnotify.com dashboard

];

```
Laravel will automatically discover this package after installation but If you're using laravel below v7.0, you have to manually register the service provider in `'/config/app.php'` providers array

```php
'providers' => 
[
   Velstack\Mnotify\MnotifyServiceProvider::class,
];

 
 
```
# Campaign
#### Send quick bulk SMS

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

  
  // recipients number be in array
  
  public function send()
  {
    Notify::sendQuickSMS(['233205550368'], 'First laravel msg with mNotify !');
    return 'success';
  }
  
  // to multiple numbers 
  public function toMany()
  {
    Notify::sendQuickSMS(['23320*******', '23320*******'],  'API messaging is fun. hurray!');
    return 'success';
  }
  
    // OR
  
   public function fromDatabase()
   {
  
     $users = User::pluck('phone')->toArray(); 
      // Dont put the recipients in array again. pass it as the first parameter to the sendQuickSMS() method
     Notify::sendQuickSMS($users, 'Good afternoon all users !');
     return 'success';
   }
   
     // you can also call it like this
  
  public function welcomeMessage()
  {
    $sender = new Notify();
    $sender->sendQuickSMS(['233205550368'],   'Thank you for registering on our website !');
    
     return 'success';
  }
  
  
  
  /**
 you can also call the 'notify method'. 
 This method will send the sms to the authenticated user in your application.
 So you don't need to pass a recipient. This must only be called on App\Models\User model.
 NOTE: your model table must contain a 'phone' column. If doesn't, you may set a 'setMnotifyColumnForSMS()'
 method in your User::class model to return the custom column where you store phone numbers. eg.@after 
  public function setMnotifyColumnForSMS()
 {
   return $this->getAttributeValue('mnotify_phone_column');
 }
 **/
 
 public function toAuthUser()
 {
   Notify::notify('Your subscription is expiring in 3 days.');
       return 'success';
 }
  
  
   public function toAuth()
 {
    $sender = new Notify();
    $sender->notify('Your subscription is expiring in 3 days.');
     return 'success';
 }
  
  
  
}
 
```
#### `Response`
```json
 {
    "status": "success",
    "code": "2000",
    "message": "messages sent successfully",
    "summary": {
        "_id": "A59CCB70-662D-45EF-9976-1EFAD249793D",
        "type": "API QUICK Notify",
        "total_sent": 2,
        "contacts": 2,
        "total_rejected": 0,
        "numbers_sent": [],
        "credit_used": 2,
        "credit_left": 1483
    }
}

```


#### Send Custom SMS With different sender ID

```php

/** 
  Sometimes you may need to send sms to users with a different sender ID instead of 
  the sender ID registered in mnotify.php, Using the customSMS method, you should pass the sender ID
  as the first parameter, followed by array of recipients and then the message.
 **/

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

  
  public function customization()
  {
  
    Notify::sendCustomSMS('mNotify', ['0205550368'], 'API messaging is fun !');
     return 'success';
      // accepts   sender id, recipient and message 
     
 }
  
}
 
```

#### `Response`
```json
 {
    "status": "success",
    "code": "2000",
    "message": "messages sent successfully",
    "summary": {
        "_id": "A59CCB70-662D-45EF-9976-1EFAD249793D",
        "type": "API QUICK Notify",
        "total_sent": 2,
        "contacts": 2,
        "total_rejected": 0,
        "numbers_sent": [],
        "credit_used": 2,
        "credit_left": 1483
    }
}

```




### Using the notification channel

#### In the notification class;


```php

namespace App\Notifications;

use Velstack\Mnotify\Notifications\MnotifyMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;


    public function __construct()
    {
        //
    }


    public function via($notifiable)
    {
        return ['mnotify'];
    }


    public function toMnotify($notifiable)
    {
        return (new MnotifyMessage())->message("Dear $notifiable->firstname, Welcome to laravel !.");

    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

 
```

#### sending notification in your controller;

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;


class NotificationController extends  Controller{

  // sending notification
  
  public function sendNotification()
  {
    $user = User::find(1);
    $user->notify(new WelcomeNotification());
   return 'success';
  }
  
  
//  Using this notification channel, you must have a 'phone' column on the model fillable.
//  If your target model doesn't have a 'phone' column, set a setMnotifyColumnForSMS() method 
//  in that model and specify the column where you store phone numbers like below;
  
}
 
```

#### In your model:

Make sure your target model implements the `Notifiable` Trait

```php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 
 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     
    protected $fillable = [
        'name',
        'email',
        'some_phone_column',
        'password',
    ];
    
    public function setMnotifyColumnForSMS(){
     return $this->getAttributeValue('some_phone_column');
    }


     
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     
} 
```

#### sending on demand notification;

```php
/**
 Sometimes you may need to send a notification to someone who 
 is not stored as a "user" of your application. Using the 
 Notification facade's route method, you may specify 'mnotify' 
  notification driver followed by the recipient before sending the notification.
 **/

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
 

class NotificationController extends  Controller{
  
  public function onDemandNotification()
  { 
    Notification::route('mnotify', '020***0368')->notify(new WelcomeNotification());
      return 'success';
  }
  
}
 
```

#### Send Custom Notification with Different sender ID

```php
  /**  Sometimes you may need to send notification to users with a different sender ID instead of 
  the sender ID registered in mnotify.php. WITH this, In the via method of the notification class, specify 'velstack'
  as the notification driver and then use the "toVelstack" method to send your notification like below.   **/
 

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Velstack\Mnotify\Notifications\VelstackMessage;

class SaleAlert extends Notification
{
    use Queueable;


    public function via($notifiable)
    {
        return ['velstack'];
    }


    public function toVelstack($notifiable)
    {
        return (new VelstackMessage())
            ->message('The introduction to the notification.')
            ->sender('mNotify');
    }

}

 /**  
  After doing this in the notification class, 
  you may now send notification like sending normal notification in laravel   
 **/
                                                                                

 
```


#### Group Bulk SMS

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function sendGroupSMS()
  {
    Notify::sendGroupQuickSMS(['1', '2'], 17481);
      return 'success';
    // the first array parameters are group id, the second parameter is the message id
   
  }
  
}
 
```

#### `Response`
```json
 {  "status": "success",
    "code": "2000",
    "message": "messages sent successfully",
    "summary": {
        "_id": "8C5D1052-9BD6-459A-96FF-5DC1516C05FD",
        "type": "API GROUP Notify",
        "total_sent": 3,
        "contacts": 3,
        "total_rejected": 0,
        "numbers_sent": [],
        "credit_used": 3,
        "credit_left": 1480
    }
    
}

```


#### Quick Bulk Voice Call

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function voiceCall()
  {
    Notify::sendQuickVoiceCall('First Voice Campaign', ['0249706365', '0203698970'], $path_to_audio_file);
     return 'success';
    // the first parameter is campaign message, recipient, path to the voice file.
   
  }
  
}
 
```

#### Response
```json
{
    "status": "success",
    "code": "2000",
    "message": "voice call sent successfully",
    "summary": {
        "_id": "XRSzcFO74eHGCj6TrdZjVut8qDsXVi",
        "voice_id": "20180308134708",
        "type": "QUICK BULK CALL",
        "total_sent": 2,
        "contacts": 2,
        "total_rejected": 0,
        "numbers_sent": [],
        "credit_used": 18
    }
}

```

#### Group Bulk Voice Call

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function groupVoiceCall()
  {
  
    Notify::sendGroupVoiceCall('First Voice Campaign', ['1','2'],  $path_to_audio_file,'20180308134708');
      return 'success';
      //accepts campaign message, group id, path to audio file, or voice id
     
 }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "code": "2000",
    "message": "voice call sent successfully",
    "summary": {
        "_id": "pj85z9u5odXqozOZOFuJwU88SYVN7j",
        "voice_id": "20180308134708",
        "type": "GROUP BULK CALL",
        "total_sent": 2,
        "contacts": 2,
        "total_rejected": 0,
        "numbers_sent": [],
        "credit_used": 18
    }
}

```

#### Sender ID Registration

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function senderId()
  {
  
    Notify::registerSenderId('mNotify', 'For Sending SMS Newsletters');
     return 'success';
      // accepts   sender id, purpose 
     
 }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "code": "2000",
    "message": "Sender ID Successfully Registered.",
    "summary": {
        "sender_name": "mNotify",
        "purpose": "For Sending SMS Newsletters",
        "status": "Pending"
    }
}

```
# Message Template
#### Get all Message Template

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function allMsgTemplate()
  {
    Notify::getAllMessageTemplates();   
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "template_list": [
        {
            "_id": 1,
            "title": "Testing Message",
            "content": "Just trying to test the message"
        },
        {
            "_id": 2,
            "title": "HAPPY BIRTHDAY",
            "content": "This day we would like to wish you a happy birthday."
        }
    ]
}
```

#### Get a Specific Message Template

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function allMsgTemplate()
  {
    Notify::getASpecificMessageTemplate(1);  
     // accepts template id 
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "template_list": {
        "_id": 1,
        "title": "Testing Message",
        "content": "Just trying to test the message"
    }
}
```

#### Add Message Template

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function addTemplate()
  {
    Notify::addNewMessageTemplate('API testing','Best message template'); 
     // accepts title and content  
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "_id": "3"
}
```


#### Update a Message Template

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function addTemplate()
  {
    Notify::updateMessageTemplate(3,'API testing','Best message template');  
    // accepts template id, title and content
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "template updated"
}
```

#### Delete Message Template

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function addTemplate()
  {
    Notify::deleteMessageTemplate(3);  
    // accepts delete template id 
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "template deleted"
}
```


 # Group
#### Get all Groups 

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function retrieveGroups()
  {
    Notify::getAllGroups();   
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "group_list": [
        {
            "_id": 1,
            "group_name": "Test Group",
            "total_contacts": 10
        },
        {
            "_id": 2,
            "group_name": "mNotify Staff",
            "total_contacts": 200
        }
    ]
}

```


#### Get a specific Groups

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function getOneGroup()
  {
    Notify::getASpecificGroup(1); 
    // accepts group id  
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "group": {
        "_id": 1,
        "group_name": "Test Group",
        "total_contacts": 10
    }
}

```


#### Add a Group

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function addGroup()
  {
    Notify::addNewGroup('Testing Group'); 
    //accepts new group name
      
  }
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "_id": "3"
}

```


#### Update a Group

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function updateGroup()
  {
    Notify::updateGroup('New Group', 3);     
  }
  
  //the first parameter is the New name, the second parameter is the id of the group you want to update
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "group updated"
}

```


#### Delete a Group

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function deleteGroup()
  {
    Notify::deleteGroup(3); 
  }
  
   //accepts group id
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "group and associated contacts deleted"
}

```


# Contact
#### Get all contacts

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function getContacts()
  {
    Notify::getAllContact();

  }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "contacts_list": [
        {
            "_id": 1,
            "phone": "0203xxxxxx",
            "title": "Mr",
            "firstname": "Bruce",
            "lastname": "Wayne",
            "email": "bruce.wayne@domain.com",
            "dob": "2017-11-25"
        },
        {
            "_id": 2,
            "phone": "0249xxxxxx",
            "title": "Mr",
            "firstname": "Clark",
            "lastname": "Kent",
            "email": "clark.kent@domain.com",
            "dob": "2017-11-25"
        }
    ]
}

```


#### Get group contacts

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function groupContacts()
  {
    Notify::getGroupContacts(1);
  }
  
  // accepts group id
  
  
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "contact": {
        "_id": 2,
        "group_id": 1,
        "phone": "0249xxxxxx",
        "title": "Mr",
        "firstname": "Clark",
        "lastname": "Kent",
        "email": "clark.kent@domain.com",
        "dob": "2017-11-25"
    }
}

```


#### Get a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function getOneContact()
  {
    Notify::getASpecificContact(1);
  }
  
  // accepts contact id
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "contact": {
        "_id": 1,
        "phone": "0203xxxxxx",
        "title": "Mr",
        "firstname": "Bruce",
        "lastname": "Wayne",
        "email": "bruce.wayne@domain.com",
        "dob": "2017-11-25"
    }
}

```



#### Add a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function addNewContact()
  {
    Notify::addNewContact(2,'0205550368','Dr.','Samuel', 'Fort', 'sam@velstack.com','1999-07-07');

  }
  
  // accepts group id, title, firstname, lastname, email and date of birth in this format YYYY-MM-DD
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "contact": {
        "status": "success",
        "_id": 4,
        "_group_id": "1"
    }
}

```


#### Update a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function update()
  {
    Notify::updateContact(1, 3,'0205550368','Dr.','Sam', 'Fort', 'sam@velstack.com','2002-07-07');

  }
  
  // accepts contact id, group id,phone, title, firstname, lastname, email and date of birth in this format YYYY-MM-DD
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "contact updated"
}

```



#### Delete a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function deleteMethod()
  {
    Notify::deleteContact(1, 3);

  }
  
  // accepts contact id and  group id
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "group and associated contacts deleted"
}

```



# Reports and Stats
#### Check SMS Balance

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function SMSBalance()
  {
    Notify::checkSMSBalance();

  }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "balance": 1000,
    "bonus": 471
}

```

#### Check Voice Balance

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function voiceBalance()
  {
    Notify::checkVoiceBalance();

  }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "balance": 59995
}

```

#### Check SMS Delivery

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function deliveryStatus()
  {
    Notify::checkSMSDelivery("DD808719-2742-478A-85BE-2CF50E455FE8");
    // remember when an sms is sent, an _id is returned in the response. Use that _id to check the delivery status.

  }
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "report": [
        {
            "_id": 60711577,
            "recipient": "233249706365",
            "message": "API messaging is fun!",
            "sender": "mNotify",
            "status": "DELIVERED",
            "date_sent": "2018-03-08 10:19:35",
            "campaign_id": "7FE4A62A-96EB-4755-BC57-000A38C8C6EF",
            "retries": 0
        },
        {
            "_id": 60711578,
            "recipient": "233203698970",
            "message": "API messaging is fun!",
            "sender": "mNotify",
            "status": "DELIVERED",
            "date_sent": "2018-03-08 10:19:35",
            "campaign_id": "7FE4A62A-96EB-4755-BC57-000A38C8C6EF",
            "retries": 0
        }
    ]
}

```

 <p align="center">
 If you find anything that you think needs to be modified or addressed, send me a message on twitter
</p>

<p align="center">
<a href="https://twitter.com/sammy_fort"><img alt="Twitter Follow" src="https://img.shields.io/twitter/follow/sammy_fort?label=@sammy_fort&style=social"></a>
<a href="https://packagist.org/packages/velstack/mnotify"><img alt="Packagist Version (custom server)" src="https://img.shields.io/packagist/v/velstack/mnotify?label=Version"></a>
<a href="https://packagist.org/packages/velstack/mnotify"><img src="https://img.shields.io/github/license/sammyfort/mNotify-laravel"></a>

</p>


  
<p align="center">
  Sammy Fort ©2023. Powered by <a href="https://velstack.com/">Velstack</a>
</p>
