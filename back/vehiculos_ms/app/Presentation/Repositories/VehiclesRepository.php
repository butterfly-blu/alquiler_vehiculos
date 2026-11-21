<?php

namespace App\Presentation\Repositories;

use App\Base\HttpException;
use App\Base\Responses;
use App\Controllers\VehiclesController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VehiclesRepository
{

    function get_all_vehicles(Request $req, Response $resp, $args): Response
    {
        $controller = new VehiclesController();
        $estado = $req->getQueryParams()['estado'] ?? null;
        $rows = $controller->all($estado);
        return Responses::json($resp, $rows->toJson());
    }

    function get_available_vehicles(Request $req, Response $resp, $args): Response
    {
        $fecha = $args["fecha"];
        $controller = new VehiclesController();
        $rows = $controller->available($fecha);
        return Responses::json($resp, $rows->toJson());
    }

    function get_vehicle(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        $controller = new VehiclesController();
        $row = $controller->find_vehicle_by_id($id);
        if (empty($row)) {
            return Responses::error($resp, 'Vehiculo no encontrado', 404);
        }
        return Responses::json($resp, $row->toJson());
    }

    function create_vehicle(Request $req, Response $resp, $args): Response
    {
        $data = json_decode($req->getBody()->getContents(), true) ?? [];
        try {
            $controller = new VehiclesController();
            $row = $controller->create($data);
            return Responses::json($resp, $row->toJson(), 201);
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }

    function update_vehicle(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        $data = json_decode($req->getBody()->getContents(), true) ?? [];
        try {
            $controller = new VehiclesController();
            $row = $controller->update($id, $data);
            return Responses::json($resp, $row->toJson());
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }

    function delete_vehicle(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        try {
            $controller = new VehiclesController();
            $row = $controller->delete($id);
            return Responses::json($resp, $row->toJson());
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }
}
