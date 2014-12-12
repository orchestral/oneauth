<?php namespace Orchestra\OneAuth\Contracts\Listener;

use Illuminate\Contracts\Auth\Guard;

interface ConnectUser
{
    /**
     * Response when user has connected.
     *
     * @param  array  $data
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @return mixed
     */
    public function userHasConnected(array $data, Guard $auth);
}
