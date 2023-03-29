<?php
 namespace Velstack\Mnotify;

 use Velstack\Mnotify\Notifications\MnotifyChannel;
 use Illuminate\Support\Facades\Notification;
 use Illuminate\Support\ServiceProvider;
 use Velstack\Mnotify\Notifications\VelstackChannel;

 class MnotifyServiceProvider extends ServiceProvider
{

    public function register()
    {
        Notification::extend('mnotify', function ($app) {
            return new MnotifyChannel();
        });

        Notification::extend('velstack', function ($app) {
            return new VelstackChannel();
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
