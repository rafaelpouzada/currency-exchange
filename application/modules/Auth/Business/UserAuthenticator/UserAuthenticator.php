<?php

namespace Auth\Business\UserAuthenticator;

use Auth\Entities\User;
use Auth\Repositories\Contracts\IUserRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

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
     * @throws AuthenticationException
     */
    public function auth(array $attributes): User
    {
        if (!Auth::attempt($attributes)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $user = Auth::user();
        if (!$user) {
            throw new AuthenticationException('User not found.');
        }

        $tokenResult    = $user->createToken('accessToken');
        $token          = [
            'access_token'  => $tokenResult->accessToken,
            'expires_at'    => $tokenResult->token->expires_at,
        ];

        $user->setAttribute('token', $token);

        return new User($user->toArray());
    }
}
