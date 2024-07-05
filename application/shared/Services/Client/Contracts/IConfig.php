<?php

namespace Shared\Services\Client\Contracts;

/**
 * @author Rodrigo Damasceno <rodrigo.damasceno@tray.net.br>
 */
interface IConfig
{
    /**
     * IConfig constructor.
     *
     * @param array $configs
     */
    public function __construct(array $configs);

    /**
     * Retrieves the hub's api url.
     *
     * @return string
     */
    public function getApiUrl(): string;
}
