<?php

namespace App\Presentation\Repositories;

use App\Base\HttpException;
use App\Base\Responses;
use App\Controllers\ClientsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ClientsRepository
{

    function get_all_clients(Request $req, Response $resp, $args): Response
    {
        $controller = new ClientsController();
        $rows = $controller->all();
        return Responses::json($resp, $rows->toJson());
    }

    function get_client(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        $controller = new ClientsController();
        $row = $controller->find_client_by_id($id);
        if (empty($row)) {
            return Responses::error($resp, 'Cliente no encontrado', 404);
        }
        return Responses::json($resp, $row->toJson());
    }

    function create_client(Request $req, Response $resp, $args): Response
    {
        $data = json_decode($req->getBody()->getContents(), true) ?? [];
        try {
            $controller = new ClientsController();
            $row = $controller->create($data);
            return Responses::json($resp, $row->toJson(), 201);
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }

    function update_client(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        $data = json_decode($req->getBody()->getContents(), true) ?? [];
        try {
            $controller = new ClientsController();
            $row = $controller->update($id, $data);
            return Responses::json($resp, $row->toJson());
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }

    function delete_client(Request $req, Response $resp, $args): Response
    {
        $id = $args["id"];
        try {
            $controller = new ClientsController();
            $row = $controller->delete($id);
            return Responses::json($resp, $row->toJson());
        } catch (HttpException $e) {
            return Responses::error($resp, $e->getMessage(), $e->getCode());
        }
    }
}
