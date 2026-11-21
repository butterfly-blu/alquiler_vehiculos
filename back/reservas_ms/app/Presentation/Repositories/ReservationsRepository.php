<?php

namespace App\Presentation\Repositories;

use App\Base\HttpException;
use App\Base\Responses;
use App\Controllers\ReservationsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReservationsRepository
{

    function get_all_reservations(Request $req, Response $resp, $args): Response
    {
        $controller = new ReservationsController();
        $rows = $controller->all($req->getQueryParams());
        return Responses::json($resp, $rows->toJson());
    }

    function get_reservation(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        $controller = new ReservationsController();
        $row = $controller->find_reservation_by_id($id);
        if (empty($row)) {
            return Responses::error($resp, 'Reserva no encontrada', 404);
        }
        return Responses::json($resp, $row->toJson());
    }

    function create_reservation(Request $req, Response $resp, $args): Response
    {
        $data = json_decode($req->getBody()->getContents(), true) ?? [];
        try {
            $controller = new ReservationsController();
            $row = $controller->create($data);
            return Responses::json($resp, $row->toJson(), 201);
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }

    function return_reservation(Request $req, Response $resp, $args): Response
    {
        return $this->finish($resp, $args["id"], 'devolver');
    }

    function cancel_reservation(Request $req, Response $resp, $args): Response
    {
        return $this->finish($resp, $args["id"], 'cancelar');
    }

    private function finish(Response $resp, int $id, string $accion): Response
    {
        try {
            $controller = new ReservationsController();
            $row = $controller->finish($id, $accion);
            return Responses::json($resp, $row->toJson());
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }
}
