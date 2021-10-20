<?php

declare(strict_types=1);

namespace Shoper\Recruitment\Task\HereapiClient;

/**
 * Fabryka klienta HereApi.
 */
class ClientFactory
{
    public function getHereapiClient(): HereapiClient
    {
            return new HereapiClient(
                getenv("HERE_MAPS_HOST"),
                getenv("HERE_MAPS_APP_KEY")
            );
    }
}
