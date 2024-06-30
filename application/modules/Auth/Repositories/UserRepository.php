<?php

namespace Auth\Repositories;

use ArrayAccess;
use Auth\Entities\User;
use Auth\Repositories\Contracts\IUserRepository;
use Auth\Repositories\Models\UserModel;
use Illuminate\Support\Arr;
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
        $user = $this
            ->getResource()
            ->execute(function (UserModel $model) use ($attributes) {
                $queryBuilder = $model->newQuery();
                /** @var UserModel $user */
                $user  = $queryBuilder->create($attributes);
                $token = $user->createToken('auth_token');

                $accessToken = Arr::get($token->toArray(), 'accessToken');
                $expiresAt   = Arr::get($token->toArray(), 'token.expires_at');
                $token       = [
                    'access_token' => $accessToken,
                    'expires_at'   => $expiresAt,
                ];
                $user->setAttribute('token', $token);

                return $user;
            })->toEntity();

        return $user;
    }
}
