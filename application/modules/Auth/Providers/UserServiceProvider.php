<?php

namespace Auth\Providers;

use Auth\Business\UserAuthenticator\{Contracts\IUserAuthenticator, UserAuthenticator};
use Auth\Repositories\{Contracts\IUserRepository, UserRepository};
use Auth\Business\UserCreator\{Contracts\IUserCreator, UserCreator};
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register any application Business.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(IUserCreator::class, UserCreator::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IUserAuthenticator::class, UserAuthenticator::class);
    }
}
