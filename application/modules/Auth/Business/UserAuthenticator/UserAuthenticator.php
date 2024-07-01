<?php

namespace Auth\Business\UserAuthenticator;

use Auth\Entities\User;
use Auth\Repositories\Contracts\IUserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAuthenticator implements Contracts\IUserAuthenticator
{
    /**
     * The user repository instance.
     *
     * @var IUserRepository
     */
    protected IUserRepository $userRepository;

    /**
     * Create a new user creator instance.
     *
     * @param IUserRepository $userRepository
     * @return void
     */
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate a user.
     *
     * @param array $attributes
     * @return User
     */
    public function auth(array $attributes): User
    {
        $this->validateAttributes($attributes);
        return $this->userRepository->authenticate($attributes);
    }

    /**
     * Validate the attributes.
     *
     * @param array $attributes
     * @return void
     */
    protected function validateAttributes(array $attributes): void
    {
        $validator = Validator::make($attributes, [
            'email'     => 'required|email|max:50',
            'password'  => 'required|string|min:8',
        ]);

        $validator->validate();
    }
}
