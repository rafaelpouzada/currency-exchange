<?php

namespace Shared\Services\Client;

use Shared\Entities\Entity;

class Config extends Entity implements Contracts\IConfig
{
    /**
     * @inheritDoc
     */
    public function getApiUrl(): string
    {
        return $this->getAttribute('api_url') ?? '';
    }

    /**
     * @param string $apiUrl
     */
    protected function setApiUrlAttribute(string $apiUrl): void
    {
        if ($apiUrl && !preg_match('/\/$/', $apiUrl)) {
            $apiUrl .= '/';
        }

        $this->attributes['api_url'] = $apiUrl;
    }
}
