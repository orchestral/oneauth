<?php namespace Orchestra\OneAuth\Handlers;

use Illuminate\Session\Store;

class UserLoggedOut
{
    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Construct a new user logged out handler.
     *
     * @param \Illuminate\Session\Store  $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle user logged out.
     *
     * @return void
     */
    public function handle()
    {
        $this->session->forget('orchestra.oneauth');
    }
}
