<?php

namespace Auth\Entities;

use Shared\Entities\Entity;

/**
 * @property int     $id
 * @property string  $name
 * @property string  $email
 * @property string  $ip
 * @property string  $access_token
 */
class User extends Entity
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = [
        'id' => 'int'
    ];

    /**
     * Get the user id.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getKey();
    }

    /**
     * Get the name of the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the email of the user.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get the user ip.
     *
     * @return string
     */
    public function getUserIp(): string
    {
        return $this->ip;
    }
}
