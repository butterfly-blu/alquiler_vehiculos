<?php

use App\Presentation\Repositories\ReservationsRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/reservas', function (RouteCollectorProxy $group) {
        $group->get('', [ReservationsRepository::class, 'get_all_reservations']);
        $group->get('/{id:[0-9]+}', [ReservationsRepository::class, 'get_reservation']);
        $group->post('', [ReservationsRepository::class, 'create_reservation']);
        $group->put('/{id:[0-9]+}/devolver', [ReservationsRepository::class, 'return_reservation']);
        $group->put('/{id:[0-9]+}/cancelar', [ReservationsRepository::class, 'cancel_reservation']);
    });
};
