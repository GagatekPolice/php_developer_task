<?php

declare(strict_types=1);

use Shoper\Recruitment\Task\Handler\ApiRequestHandler;
use Shoper\Recruitment\Task\Request\JsonResponse;

require 'vendor/autoload.php';

$requestHandler = new ApiRequestHandler();

try {
    $requestHandler->processRequest();
} catch (\Exception $exception) {
    return new JsonResponse(
        array(
            'error' => array(
                'message' => $exception->getMessage()
            )),
        $exception->getCode(),
    );
}
