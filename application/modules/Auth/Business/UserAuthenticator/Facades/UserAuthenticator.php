<?php

namespace Auth\Business\UserAuthenticator\Facades;

use Auth\Entities\User;
use Auth\Business\UserAuthenticator\Contracts\IUserAuthenticator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static User auth(array $attributes)
 *
 * @see IUserAuthenticator
 */
class UserAuthenticator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return IUserAuthenticator::class;
    }
}
