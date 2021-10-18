<?php

declare(strict_types=1);

use Shoper\Recruitment\Task\Handler\ApiRequestHandler;
use Shoper\Recruitment\Task\Request\JsonResponse;
use Shoper\Recruitment\Task\Services\EnvLoader;

require 'vendor/autoload.php';

(new EnvLoader(__DIR__ . '/.env'))->load();

try {
    (new ApiRequestHandler())->processRequest();
} catch (\Exception $exception) {
    return new JsonResponse(
        [
            'error' => [
                'message' => $exception->getMessage()
            ]
        ],
        $exception->getCode(),
    );
}
