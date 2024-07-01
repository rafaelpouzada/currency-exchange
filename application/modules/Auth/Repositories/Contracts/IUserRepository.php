<?php

namespace Auth\Repositories\Contracts;

use Shared\Repositories\Contracts\Behaviors\{Findable,Storable};

interface IUserRepository extends Findable,Storable
{
    /**
     * Authenticate a user.
     *
     * @param array $attributes
     * @return mixed
     */
    public function authenticate(array $attributes): mixed;
}
