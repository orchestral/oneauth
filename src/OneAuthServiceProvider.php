<?php namespace Orchestra\OneAuth;

use Orchestra\Support\Providers\ServiceProvider;

class OneAuthServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['events']->listen('auth.login', 'Orchestra\OneAuth\Handlers\UserLoggedIn');
        $this->app['events']->listen('orchestra.oneauth.user: saved', 'Orchestra\OneAuth\Handlers\UserConnected');
        $this->app['events']->listen('auth.logout', 'Orchestra\OneAuth\Handlers\UserLoggedOut');
    }
}
