<?php

namespace Auth\Entities;

use Illuminate\Support\Carbon;
use Shared\Entities\Entity;

/**
 * @property string $access_token
 * @property Carbon $expires_at
 */
class UserToken extends Entity
{
}
