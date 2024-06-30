<?php

namespace Auth\Business\UserCreator\Facades;

use Auth\Entities\User;
use Auth\Business\UserCreator\Contracts\IUserCreator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static User create(array $attributes)
 *
 * @see IUserCreator
 */
class UserCreator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return IUserCreator::class;
    }
}
