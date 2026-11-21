<?php

use App\Presentation\Repositories\ClientsRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/clientes', function (RouteCollectorProxy $group) {
        $group->get('', [ClientsRepository::class, 'get_all_clients']);
        $group->get('/{id:[0-9]+}', [ClientsRepository::class, 'get_client']);
        $group->post('', [ClientsRepository::class, 'create_client']);
        $group->put('/{id:[0-9]+}', [ClientsRepository::class, 'update_client']);
        $group->delete('/{id:[0-9]+}', [ClientsRepository::class, 'delete_client']);
    });
};
