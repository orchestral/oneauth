<?php namespace Orchestra\OneAuth\Contracts\Command;

use Orchestra\OneAuth\Contracts\Listener\ConnectUser;

interface AuthenticateUser
{
    /**
     * Execute user authentication.
     *
     * @param \Orchestra\OneAuth\Contracts\Listener\ConnectUser  $listener
     * @param string  $type
     * @param bool  $hasCode
     * @return mixed
     */
    public function execute(ConnectUser $listener, $type, $hasCode = false);
}