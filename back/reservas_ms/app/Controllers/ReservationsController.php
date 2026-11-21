<?php

namespace App\Controllers;

use App\Base\HttpException;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Collection;

class ReservationsController
{
    /**
     * Lista las reservas con el nombre del cliente y del vehiculo.
     * Se puede filtrar por cliente_id y/o vehiculo_id (historial).
     */
    function all(array $filters = []): Collection
    {
        $query = DB::table('reservas as r')
            ->join('clientes as c', 'c.id', '=', 'r.cliente_id')
            ->join('vehiculos as v', 'v.id', '=', 'r.vehiculo_id')
            ->select('r.*', 'c.nombre as cliente', DB::raw("CONCAT(v.marca, ' ', v.modelo) as vehiculo"));
        if (!empty($filters['cliente_id'])) {
            $query->where('r.cliente_id', (int) $filters['cliente_id']);
        }
        if (!empty($filters['vehiculo_id'])) {
            $query->where('r.vehiculo_id', (int) $filters['vehiculo_id']);
        }
        return $query->orderBy('r.id', 'desc')->get();
    }

    function find_reservation_by_id(int $id): Reservation|null
    {
        return Reservation::find($id);
    }

    function create(array $data): Reservation
    {
        $clienteId = (int) ($data['cliente_id'] ?? 0);
        $vehiculoId = (int) ($data['vehiculo_id'] ?? 0);
        $inicio = $data['fecha_inicio'] ?? '';
        $fin = $data['fecha_fin'] ?? '';

        if (!$clienteId || !$vehiculoId || $inicio === '' || $fin === '') {
            throw new HttpException('Faltan datos de la reserva');
        }
        if ($fin < $inicio) {
            throw new HttpException('La fecha fin no puede ser menor a la fecha inicio');
        }
        if (empty(Client::find($clienteId))) {
            throw new HttpException('Cliente no encontrado', 404);
        }
        $vehicle = Vehicle::find($vehiculoId);
        if (empty($vehicle)) {
            throw new HttpException('Vehiculo no encontrado', 404);
        }
        if ($vehicle->estado === 'mantenimiento') {
            throw new HttpException('El vehiculo esta en mantenimiento', 409);
        }
        $cruce = Reservation::where('vehiculo_id', $vehiculoId)
            ->where('estado', 'activa')
            ->where('fecha_inicio', '<=', $fin)
            ->where('fecha_fin', '>=', $inicio)
            ->exists();
        if ($cruce) {
            throw new HttpException('El vehiculo ya tiene una reserva en esas fechas', 409);
        }

        $reservation = new Reservation();
        $reservation->cliente_id = $clienteId;
        $reservation->vehiculo_id = $vehiculoId;
        $reservation->fecha_inicio = $inicio;
        $reservation->fecha_fin = $fin;
        $reservation->estado = 'activa';
        $reservation->save();

        $vehicle->estado = 'alquilado';
        $vehicle->save();

        return $reservation;
    }

    /**
     * Finaliza una reserva activa: 'devolver' => completada, 'cancelar' => cancelada.
     * En ambos casos el vehiculo vuelve a quedar disponible.
     */
    function finish(int $id, string $accion): Reservation
    {
        $reservation = Reservation::find($id);
        if (empty($reservation)) {
            throw new HttpException("La reserva $id no existe", 404);
        }
        if ($reservation->estado !== 'activa') {
            throw new HttpException('La reserva no esta activa');
        }
        $reservation->estado = $accion === 'devolver' ? 'completada' : 'cancelada';
        $reservation->save();

        $vehicle = Vehicle::find($reservation->vehiculo_id);
        if (!empty($vehicle)) {
            $vehicle->estado = 'disponible';
            $vehicle->save();
        }
        return $reservation;
    }
}
