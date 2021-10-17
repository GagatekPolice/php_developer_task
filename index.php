<?php

declare(strict_types=1);

use Shoper\Recruitment\Task\Handler\ApiRequestHandler;
use Shoper\Recruitment\Task\Request\JsonResponse;

require 'vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$requestHandler = new ApiRequestHandler($uri);

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
