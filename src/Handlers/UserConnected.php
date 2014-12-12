<?php namespace Orchestra\OneAuth\Handlers;

use Illuminate\Contracts\Auth\Guard;
use Orchestra\OneAuth\User as Eloquent;

class UserConnected
{
    /**
     * Handle user connected via social auth.
     *
     * @param  \Orchestra\OneAuth\User  $model
     * @param  array  $data
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @return void
     */
    public function handle(Eloquent $model, array $data, Guard $auth)
    {
        if (! is_null($id = $this->getAuthenticatedUser($model))) {
            $auth->loginUsingId($id, true);
        }
    }

    /**
     * Get user unique identifier or return null.
     *
     * @param  \Orchestra\OneAuth\User $model
     * @return mixed|null
     */
    protected function getAuthenticatedUser(Eloquent $model)
    {
        if ($this->auth->check()) {
            return null;
        }

        return $model->getAttribute('user_id');
    }
}
