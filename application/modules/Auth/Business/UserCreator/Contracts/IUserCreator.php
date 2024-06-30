<?php

namespace Auth\Business\UserCreator\Contracts;

use Auth\Entities\User;

interface IUserCreator
{
    /**
     * Register a new user.
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User;
}
