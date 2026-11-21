<?php

namespace App\Controllers;

use App\Base\HttpException;
use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class ClientsController
{
    function all(): Collection
    {
        return Client::orderBy('id')->get();
    }

    function find_client_by_id(int $id): Client|null
    {
        return Client::find($id);
    }

    function create(array $data): Client
    {
        $client = new Client();
        $this->fill($client, $data);
        $client->save();
        return $client;
    }

    function update(int $id, array $data): Client
    {
        $client = Client::find($id);
        if (empty($client)) {
            throw new HttpException("El cliente $id no existe", 404);
        }
        $this->fill($client, $data);
        $client->save();
        return $client;
    }

    function delete(int $id): Client
    {
        $client = Client::find($id);
        if (empty($client)) {
            throw new HttpException("El cliente $id no existe", 404);
        }
        if (Reservation::where('cliente_id', $id)->exists()) {
            throw new HttpException('No se puede borrar, el cliente tiene reservas', 409);
        }
        $client->delete();
        return $client;
    }

    private function fill(Client $client, array $data): void
    {
        $nombre = trim($data['nombre'] ?? '');
        if ($nombre === '') {
            throw new HttpException('El nombre es obligatorio');
        }
        $client->nombre = $nombre;
        $client->telefono = $data['telefono'] ?? null;
        $client->correo = $data['correo'] ?? null;
        $client->numero_licencia = $data['numero_licencia'] ?? null;
    }
}
