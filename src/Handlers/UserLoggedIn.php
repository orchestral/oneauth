<?php namespace Orchestra\OneAuth\Handlers;

use Illuminate\Session\Store;
use Illuminate\Contracts\Auth\Authenticatable;

class UserLoggedIn
{
    protected $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Handle user logged in.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return bool|null
     */
    public function handle(Authenticatable $user)
    {
        $social = $this->session->get('orchestra.oneauth');

        if (! is_null($social)) {
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
