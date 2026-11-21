<?php

namespace App\Base;

use Exception;

/**
 * Error de negocio con el codigo HTTP que debe devolver el endpoint.
 */
class HttpException extends Exception
{
    public function __construct(string $message, int $status = 400)
    {
        parent::__construct($message, $status);
    }
}
