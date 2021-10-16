<?php
declare(strict_types=1);

require 'vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

var_dump($uri[1]);


// phpinfo();

