<?php

use App\Presentation\Repositories\VehiclesRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/vehiculos', function (RouteCollectorProxy $group) {
        $group->get('', [VehiclesRepository::class, 'get_all_vehicles']);
        $group->get('/disponibles/{fecha}', [VehiclesRepository::class, 'get_available_vehicles']);
        $group->get('/{id:[0-9]+}', [VehiclesRepository::class, 'get_vehicle']);
        $group->post('', [VehiclesRepository::class, 'create_vehicle']);
        $group->put('/{id:[0-9]+}', [VehiclesRepository::class, 'update_vehicle']);
        $group->delete('/{id:[0-9]+}', [VehiclesRepository::class, 'delete_vehicle']);
    });
};
