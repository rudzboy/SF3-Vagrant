<?php

use Symfony\Component\HttpFoundation\Request;

// require Composer's autoloader
require __DIR__.'/../vendor/autoload.php';

$kernel = new MicroframeworkAppKernel('prod', false);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
