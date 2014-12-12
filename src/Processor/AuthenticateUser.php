<?php namespace Orchestra\OneAuth\Processor;

use Orchestra\OneAuth\Token;
use Illuminate\Session\Store;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\User;
use Orchestra\OneAuth\User as Eloquent;
use Laravel\Socialite\Contracts\Provider;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Orchestra\OneAuth\Contracts\Listener\ConnectUser;
use Orchestra\OneAuth\Contracts\Command\AuthenticateUser as Command;

class AuthenticateUser implements Command
{
    /**
     * The authenticator implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * The events dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The session store implementation.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The socialite implementation.
     *
     * @var \Laravel\Socialite\Contracts\Factory
     */
    protected $socialite;

    /**
     * Construct a new authenticate user processor.
     *
     * @param \Illuminate\Contracts\Auth\Guard  $auth
     * @param \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @param \Illuminate\Session\Store  $session
     * @param \Laravel\Socialite\Contracts\Factory  $socialite
     */
    public function __construct(Guard $auth, Dispatcher $dispatcher, Store $session, Socialite $socialite)
    {
        $this->auth = $auth;
        $this->dispatcher = $dispatcher;
        $this->session = $session;
        $this->socialite = $socialite;
    }

    /**
     * Execute user authentication.
     *
     * @param \Orchestra\OneAuth\Contracts\Listener\ConnectUser  $listener
     * @param string  $type
     * @param bool  $hasCode
     * @return mixed
     */
    public function execute(ConnectUser $listener, $type, $hasCode = false)
    {
        $provider = $this->socialite->with($type);

        if (! $hasCode) {
            return $this->getAuthorizationFirst($provider);
        }

        $data = $this->getUserData($provider, $type);

        $this->session->put('orchestra.oneauth', $data);

        return $listener->userHasConnected($data, $this->auth);
    }

    /**
     * Get authorization first from provider.
     *
     * @param  \Laravel\Socialite\Contracts\Provider  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getAuthorizationFirst(Provider $provider)
    {
        return $provider->redirect();
    }

    /**
     * Get authorization first from provider.
     *
     * @param  \Laravel\Socialite\Contracts\Provider  $provider
     * @param  string  $type
     * @return array
     */
    protected function getUserData(Provider $provider, $type)
    {
        $user = $provider->user();

        $model = $this->attemptToConnectUser($user, $type);

        $data = ['provider' => $type, 'user' => $user];

        $this->dispatcher->fire('orchestra.oneauth.user: saved', [$model, $data, $this->auth]);

        return $data;
    }

    /**
     * Attempt to connect with user authentication.
     *
     * @param  \Laravel\Socialite\Contracts\User  $user
     * @param  string  $type
     * @return \Orchestra\OneAuth\User
     */
    protected function attemptToConnectUser(User $user, $type)
    {
        $model = $this->getClientOrCreate($user, $type);

        if (! is_null($currentUser = $this->auth->user())) {
            $model->setAttribute('user_id', $currentUser->getAuthIdentifier());
        }

        $model->setAttribute('token', new Token(['access' => $user->token]));
        $model->save();

        return $model;
    }

    /**
     * @param  \Laravel\Socialite\Contracts\User  $user
     * @param  string  $type
     * @return \Orchestra\OneAuth\User
     */
    protected function getClientOrCreate(User $user, $type)
    {
        return Eloquent::firstOrNew([
            'provider' => $type,
            'uid' => $user->getId(),
        ]);
    }
}
