<?php

namespace Shared\Services\Client\Contracts\Auth;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Arrayable;

class Token implements Arrayable
{
    /**
     * @var string|null
     */
    protected ?string $accessToken = null;

    /**
     * @var string|null
     */
    protected ?string $accessTokenExpirationDate = null;

    /**
     * Token constructor.
     *
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->fill($payload);
    }

    /**
     * Fill's the token with the payload given.
     *
     * @param array $payload
     */
    public function fill(array $payload): void
    {
        $this->accessToken                = $payload['token'] ?? null;
        $this->accessTokenExpirationDate  = $payload['token_expiration_date'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'token'                 => $this->accessToken,
            'token_expiration_date' => $this->accessTokenExpirationDate,
        ];
    }

    /**
     * Determines whether the access token has expired or not.
     *
     * @return bool
     */
    public function hasAccessTokenExpired(): bool
    {
        return $this->hasExpired($this->accessTokenExpirationDate);
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Determines whether the given date has expired.
     *
     * @param string|null $date
     *
     * @return bool
     */
    protected function hasExpired(?string $date): bool
    {
        if (!$date) {
            return true;
        }

        try {
            $now       = new Carbon();
            $expiresAt = new Carbon($date);

            $now->addMinutes(5);
            return $now->gt($expiresAt);
        } catch (Exception $e) {
        }

        return true;
    }
}
