<?php namespace Orchestra\OneAuth;

use Orchestra\OneAuth\Handlers\UserLoggedIn;
use Orchestra\OneAuth\Handlers\UserConnected;
use Orchestra\OneAuth\Handlers\UserLoggedOut;
use Orchestra\Support\Providers\EventServiceProvider;

class OneAuthServiceProvider extends EventServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'auth.login'  => UserLoggedIn::class,
        'auth.logout' => UserLoggedOut::class,

        'orchestra.oneauth.user: saved' => UserConnected::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
