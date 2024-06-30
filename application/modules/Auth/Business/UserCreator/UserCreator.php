<?php

namespace Auth\Business\UserCreator;

use Auth\Entities\User;
use Auth\FillableAttributeSets\UserCreatorFillableAttributeSet;
use Auth\Repositories\Contracts\IUserRepository;
use Illuminate\Support\Facades\Validator;

class UserCreator implements Contracts\IUserCreator
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
     * Register a new user.
     *
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User
    {
        $attributes = $this->filterAttributes($attributes);
        $this->validateAttributes($attributes);

        $attributes['password'] = bcrypt($attributes['password']);

        /** @var User $user */
        $user = $this->userRepository->store($attributes);
        return $user;
    }

    /**
     * Return the filtered attributes.
     *
     * @param array $attributes
     * @return array
     */
    protected function filterAttributes(array $attributes): array
    {
        return (new UserCreatorFillableAttributeSet())->filter($attributes);
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
            'name'                  => 'required|string|max:50',
            'email'                 => 'required|string|max:50|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);

        $validator->validate();
    }
}
