<?php

namespace Auth\Http\Controllers;

use Auth\Business\UserAuthenticator\Facades\UserAuthenticator;
use Auth\Business\UserCreator\Facades\UserCreator;
use Illuminate\Http\JsonResponse;
use Shared\Http\BaseController;

class AuthController extends BaseController
{
    /**
     * Register a new user.
     *
     * @return JsonResponse
     */
    public function register(): JsonResponse
    {
        return $this->getResponse()->dispatch(function () {
            $attributes = $this->getPostAttributes('name', 'email', 'password', 'password_confirmation');
            return UserCreator::create($attributes);
        });
    }

    /**
     * Authenticate a user.
     *
     * @return JsonResponse
     */
    public function authenticate(): JsonResponse
    {
        return $this->getResponse()->dispatch(function () {
            $attributes = $this->getPostAttributes('email', 'password');
            return UserAuthenticator::auth($attributes);
        });
    }
}
