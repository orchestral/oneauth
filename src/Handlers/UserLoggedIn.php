<?php namespace Orchestra\OneAuth\Handlers;

use Orchestra\OneAuth\User;
use Illuminate\Session\Store;
use Illuminate\Contracts\Auth\Authenticatable;

class UserLoggedIn
{
    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Construct a new user logged in handler.
     *
     * @param \Illuminate\Session\Store  $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle user logged in.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     *
     * @return bool|null
     */
    public function handle(Authenticatable $user)
    {
        $social = $this->session->get('orchestra.oneauth');

        if (is_null($social)) {
            return ;
        }

        $model = User::where('provider', '=', $social['provider'])
                    ->where('uid', '=', $social['user']->getId())
                    ->first();

        if (is_null($model)) {
            return ;
        }

        $model->setAttribute('user_id', $user->getAuthIdentifier());
        $model->save();

        return true;
    }
}
