<?php

namespace App\Controllers;

use App\Base\HttpException;
use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

class VehiclesController
{
    const ESTADOS = ['disponible', 'alquilado', 'mantenimiento'];

    function all(?string $estado = null): Collection
    {
        if (!empty($estado)) {
            return Vehicle::where('estado', $estado)->orderBy('id')->get();
        }
        return Vehicle::orderBy('id')->get();
    }

    function find_vehicle_by_id(int $id): Vehicle|null
    {
        return Vehicle::find($id);
    }

    /**
     * Vehiculos que no estan en mantenimiento ni tienen una reserva activa en la fecha dada.
     */
    function available(string $fecha): Collection
    {
        $ocupados = Reservation::where('estado', 'activa')
            ->where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->pluck('vehiculo_id');

        return Vehicle::where('estado', '!=', 'mantenimiento')
            ->whereNotIn('id', $ocupados)
            ->orderBy('id')
            ->get();
    }

    function create(array $data): Vehicle
    {
        $vehicle = new Vehicle();
        $this->fill($vehicle, $data);
        $vehicle->save();
        return $vehicle;
    }

    function update(int $id, array $data): Vehicle
    {
        $vehicle = Vehicle::find($id);
        if (empty($vehicle)) {
            throw new HttpException("El vehiculo $id no existe", 404);
        }
        $this->fill($vehicle, $data);
        $vehicle->save();
        return $vehicle;
    }

    function delete(int $id): Vehicle
    {
        $vehicle = Vehicle::find($id);
        if (empty($vehicle)) {
            throw new HttpException("El vehiculo $id no existe", 404);
        }
        if (Reservation::where('vehiculo_id', $id)->exists()) {
            throw new HttpException('No se puede borrar, el vehiculo tiene reservas', 409);
        }
        $vehicle->delete();
        return $vehicle;
    }

    private function fill(Vehicle $vehicle, array $data): void
    {
        $marca = trim($data['marca'] ?? '');
        $modelo = trim($data['modelo'] ?? '');
        $anio = $data['anio'] ?? '';
        $estado = $data['estado'] ?? 'disponible';
        if ($marca === '' || $modelo === '' || $anio === '') {
            throw new HttpException('Marca, modelo y anio son obligatorios');
        }
        if (!in_array($estado, self::ESTADOS)) {
            throw new HttpException('Estado no valido');
        }
        $vehicle->marca = $marca;
        $vehicle->modelo = $modelo;
        $vehicle->anio = $anio;
        $vehicle->categoria = $data['categoria'] ?? null;
        $vehicle->estado = $estado;
    }
}
