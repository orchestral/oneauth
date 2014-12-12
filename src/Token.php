<?php namespace Orchestra\OneAuth;

use Illuminate\Support\Fluent;

class Token extends Fluent
{
    /**
     * Check if token is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return false;
    }
}
