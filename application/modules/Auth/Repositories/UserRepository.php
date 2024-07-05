<?php

namespace Auth\Repositories;

use ArrayAccess;
use Auth\Entities\User;
use Auth\Entities\UserToken;
use Auth\Repositories\Contracts\IUserRepository;
use Auth\Repositories\Models\UserModel;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Shared\Repositories\Concerns\CanFind;
use Shared\Repositories\Database\DbRepository;

class UserRepository extends DbRepository implements IUserRepository
{
    use CanFind;

    /**
     * @inheritDoc
     */
    public function getResourceClass(): string
    {
        return UserModel::class;
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass(): string
    {
        return User::class;
    }

    /**
     * @inheritDoc
     */
    public function store(array|ArrayAccess $attributes): User
    {
        /** @var User $user */
        $user = $this->getResource()
            ->execute(function (UserModel $model) use ($attributes) {
                return $model->getConnection()->transaction(function () use ($model, $attributes) {
                    $queryBuilder = $model->newQuery();
                    /** @var UserModel $user */
                    $user = $queryBuilder->create($attributes);
                    $user->setAttribute('token', $this->createToken($user));

                    return $user;
                });
            })->toEntity();

        return $user;
    }

    public function authenticate(array $attributes): User
    {
        /** @var User $user */
        $user = $this->getResource()
            ->execute(function (UserModel $model) use ($attributes) {
                $queryBuilder = $model->newQuery();
                /** @var UserModel|null $user */
                $user = $queryBuilder->where('email', $attributes['email'])
                    ->first();

                if (!$user || !Auth::attempt($attributes)) {
                    throw new AuthenticationException('Invalid credentials.');
                }

                $user->setAttribute('token', $this->createToken($user));

                return $user;
        })->toEntity();

        return $user;
    }

    /**
     * Create a token for the user.
     *
     * @param UserModel $user
     * @return UserToken
     */
    protected function createToken(UserModel $user): UserToken
    {
        $tokenResult    = $user->createToken('accessToken')->toArray();
        $token          = [
            'access_token'  => Arr::get($tokenResult, 'accessToken'),
            'expires_at'    => Arr::get($tokenResult, 'token.expires_at'),
        ];

        return new UserToken($token);
    }
}
