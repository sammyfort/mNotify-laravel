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
If you're using laravel below v7.0 register the service provider in `'/config/app.php'` providers array

```php
'providers' => 
[
   Velstack\Mnotify\MnotifyServiceProvider::class,
];

 
 
```

## Send a quick SMS

```php

use App\Http\Controllers\Controller;
use App\Models\User;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

  // sending a quick sms
  // recipients number be in array
  
  public function send()
  {
    Notify::sendQuickSMS(['233205550368'], 'First laravel msg with mNotify !');
    return 'Message sent successfully';
  }
  
  // to multiple numbers 
  public function toMany()
  {
    Notify::sendQuickSMS(['23320*******', '23320*******'],  'API messaging is fun. hurray!');
    return 'Message sent';
  }
  
    // OR
  
   public function fromDatabase()
   {
     $users =  User::pluck('phone');
     foreach ($users as $user)
     Notify::sendQuickSMS([$user], 'Good afternoon all users !');
     return 'Message has been to all users successfully';
   }
   
     // you can also call it like this
  
  public function welcomeMessage()
  {
    $sender = new Notify();
    $sender->sendQuickSMS(['233205550368'],   'Thank you for registering on our website !');
    
    return 'Message sent successfully';
  }
  
  
  
  /** you can also call the 'notify'. 
 * This approach will send the message to the authenticated user in your application.
 * So you don't need to pass a recipient. This must only be called on App\Models\User model.
 * NOTE: your Table must contain a 'phone' column. If doesn't, you may set a 'setMnotifyColumnForSMS()'
 * method in your User::class model to return the custom column where you store phone numbers. eg.@after 
 * 
 * public function setMnotifyColumnForSMS(){
     return $this->getAttributeValue('mnotify_phone_column');
    }
 **/
 
 public function toAuthUser()
 {
   Notify::notify('Your subscription is expiring in 3 days.');
       return 'Message sent successfully';
 }
  
  
   public function toAuth()
 {
    $sender = new Notify();
    $sender->notify('Your subscription is expiring in 3 days.');
     return 'Message sent successfully';
 }
  
  
  
}
 
```
#### output
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


## Using the notification channel

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

#### sending in your controller;

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
    $user->notify(new WelcomeNotification);
    return 'Message sent successfully';
  }
  
  
//  Using this notification channel, you must have a 'phone' column on the model fillable.
//  If your target model doesn't have a 'phone' column, set a setMnotifyColumnForSMS() method 
//  in that model and specify the column where you store phone numbers like below;
  
}
 
```

#### In your model:

Make sure your target model uses the `Notifiable` Trait

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

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
 

class NotificationController extends  Controller{

  // sending notification
  
  
//  Sometimes you may need to send a notification to someone who 
//  is not stored as a "user" of your application. Using the 
//  Notification facade's route method, you may specify 'mnotify' 
//  notification driver followed by the recipient before sending the notification.
  
  
  public function onDemandNotification()
  { 
    Notification::route('mnotify', '020***0368')->notify(new WelcomeNotification());
     return 'Message sent successfully';
  }
  
}
 
```


## Send Quick Voice Call

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function voiceCall()
  {
    Notify::sendQuickVoiceCall('First Voice Campaign', ['0249706365', '0203698970'], $path_to_audio_file);
     return 'Message sent successfully';
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



# Group Messaging

### Send Quick Group SMS

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function sendGroupSMS()
  {
    Notify::sendGroupQuickSMS(['1', '2'], 17481);
     return 'Message sent successfully';
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

### Send Group Voice Call

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function groupVoiceCall()
  {
  
    Notify::sendGroupVoiceCall('First Voice Campaign', ['1','2'],  $path_to_audio_file,'20180308134708');
     return 'Voice call sent successfully';
     
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

### Get all Groups 

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


### Get a specific Groups

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


### Add a Group

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function addGroup()
  {
    Notify::addNewGroup('Testing Group'); 
      
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


### Update a Group

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;

class UserController extends  Controller{

 
  
  public function updateGroup()
  {
    Notify::updateGroup('New Group', 3);     
  }
  
  //the first parameter is the New name, the second parameter is the group id
 
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "group updated"
}

```


### Delete a Group

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


# Contacts
### Get all contacts

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


### Get group contacts

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function groupContacts()
  {
    Notify::getAllGroupContacts(1);
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


### Get a contact

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



### Add a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function addNewContact()
  {
    Notify::addNewContact(2,'0205550368','Dr.','Samuel', 'Fort', 'sam@velstack.com','1999-07-07');

  }
  
  // accepts group id, title, firstname, lastname, email, date of birth
  
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


### Update a contact

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function update()
  {
    Notify::updateContact(1, 3,'0205550368','Dr.','Sam', 'Fort', 'sam@velstack.com','2002-07-07');

  }
  
  // accepts contact id, group id, title, firstname, lastname, email, date of birth
  
}
 
```

#### `Response`
```json
{
    "status": "success",
    "message": "contact updated"
}

```



### Delete a contact

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



# Reports
### Check SMS Balance

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

### Check Voice Balance

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

### Check SMS Delivery

```php

use App\Http\Controllers\Controller;
use Velstack\Mnotify\Notifications\Notify;
 

class UserController extends  Controller{

 
  
  public function deliveryStatus()
  {
    Notify::checkSMSDelivery(6071);

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
