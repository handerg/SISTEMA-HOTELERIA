<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use Illuminate\Database\Seeder;

class HabitacionSeeder extends Seeder
{
    public function run(): void
    {
        $habitaciones = [
            ['nombre' => 'Habitación 101', 'tipo' => 'simple',  'precio_base' => 50.00,  'descripcion' => 'Habitación simple con cama individual, baño privado y TV.'],
            ['nombre' => 'Habitación 102', 'tipo' => 'simple',  'precio_base' => 50.00,  'descripcion' => 'Habitación simple con vista al jardín.'],
            ['nombre' => 'Habitación 103', 'tipo' => 'simple',  'precio_base' => 55.00,  'descripcion' => 'Habitación simple premium con escritorio de trabajo.'],
            ['nombre' => 'Habitación 201', 'tipo' => 'doble',   'precio_base' => 85.00,  'descripcion' => 'Habitación doble con dos camas, minibar y balcón.'],
            ['nombre' => 'Habitación 202', 'tipo' => 'doble',   'precio_base' => 85.00,  'descripcion' => 'Habitación doble con cama matrimonial y sala de estar.'],
            ['nombre' => 'Habitación 203', 'tipo' => 'doble',   'precio_base' => 90.00,  'descripcion' => 'Habitación doble superior con vista panorámica.'],
            ['nombre' => 'Habitación 204', 'tipo' => 'doble',   'precio_base' => 90.00,  'descripcion' => 'Habitación doble deluxe con jacuzzi.'],
            ['nombre' => 'Suite 301',      'tipo' => 'suite',   'precio_base' => 150.00, 'descripcion' => 'Suite ejecutiva con sala de estar, minibar premium y terraza.'],
            ['nombre' => 'Suite 302',      'tipo' => 'suite',   'precio_base' => 180.00, 'descripcion' => 'Suite presidencial con dos habitaciones y sala de conferencias.'],
            ['nombre' => 'Suite 303',      'tipo' => 'suite',   'precio_base' => 200.00, 'descripcion' => 'Gran suite con vista al mar, jacuzzi privado y servicio de mayordomo.'],
        ];

        foreach ($habitaciones as $hab) {
            Habitacion::create(array_merge($hab, ['estado' => 'disponible']));
        }
    }
}
