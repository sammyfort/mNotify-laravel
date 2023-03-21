<?php
 namespace Velstack\Mnotify;

 use Velstack\Mnotify\Notifications\MnotifyChannel;
 use Illuminate\Support\Facades\Notification;
 use Illuminate\Support\ServiceProvider;

class MnotifyServiceProvider extends ServiceProvider
{

    public function register()
    {
        Notification::extend('mnotify', function ($app) {
            return new MnotifyChannel();
        });
    }


    public function boot()
    {

        $this->mergeConfigFrom( __DIR__ . '/config/mnotify.php', 'mnotify');

        $this->publishes([
            __DIR__ . '/config/mnotify.php' => config_path('mnotify.php'),
        ], 'mnotify');
    }

}
