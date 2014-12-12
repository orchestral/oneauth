<?php namespace Orchestra\OneAuth;

use Illuminate\Support\Facades\Session;
use Orchestra\Model\Eloquent;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_oneauth';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider', 'uid'];

    /**
     * Get token attribute using accessor.
     *
     * @param  mixed  $value
     * @return \Orchestra\OneAuth\Token
     */
    public function getTokenAttribute($value)
    {
        if (! is_null($value)) {
            $value = json_decode($value, true);
        }

        return new Token($value);
    }

    /**
     * Set token attribute using mutator.
     *
     * @param  \Orchestra\OneAuth\Token  $token
     * @return void
     */
    public function setTokenAttribute(Token $token)
    {
        $value = null;

        if (! $token->isValid()) {
            $value = $token->toJson();
        }

        $this->attributes['token'] = $value;
    }
}
