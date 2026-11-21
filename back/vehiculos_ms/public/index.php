<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Base/database.php';

$app = AppFactory::create();

$vehiculos_endpoints = require __DIR__ . '/../app/Presentation/Routers/endpoints.php';

$cors = require __DIR__ . '/../app/Base/cors.php';

$cors($app);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Microservicio de vehiculos activo");
    return $response;
});

$vehiculos_endpoints($app);

$app->run();
