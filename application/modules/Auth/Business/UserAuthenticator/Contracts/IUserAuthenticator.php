<?php

namespace Auth\Business\UserAuthenticator\Contracts;

use Auth\Entities\User;

interface IUserAuthenticator
{
    /**
     * Authenticate a user.
     *
     * @param array $attributes
     * @return User
     */
    public function auth(array $attributes): User;
}
