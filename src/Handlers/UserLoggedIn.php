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
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $authenticatable
     * @return bool|null
     */
    public function handle(Authenticatable $authenticatable)
    {
        if (! $this->session->has('orchestra.oneauth')) {
            return ;
        }

        list($provider, $user) = $this->session->get('orchestra.oneauth', ['provider' => null, 'user' => null]);

        $model = User::where('provider', '=', $provider)
                    ->where('uid', '=', $user->getId())
                    ->first();

        if (is_null($model)) {
            return ;
        }

        $model->setAttribute('user_id', $authenticatable->getAuthIdentifier());
        $model->save();

        return true;
    }
}
