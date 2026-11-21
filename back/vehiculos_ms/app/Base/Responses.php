<?php

namespace App\Base;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Utilidades para responder en formato JSON.
 */
class Responses
{
    static function json(Response $resp, mixed $data, int $status = 200): Response
    {
        $resp->getBody()->write(is_string($data) ? $data : json_encode($data));
        return $resp
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
    }

    static function error(Response $resp, string $message, int $status = 400): Response
    {
        return self::json($resp, ['error' => $message], $status);
    }
}
