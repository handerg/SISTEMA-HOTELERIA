<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Desayuno Buffet',       'precio' => 12.00, 'stock' => 50,  'categoria' => 'restaurante'],
            ['nombre' => 'Almuerzo Ejecutivo',     'precio' => 18.00, 'stock' => 30,  'categoria' => 'restaurante'],
            ['nombre' => 'Cena Gourmet',           'precio' => 25.00, 'stock' => 20,  'categoria' => 'restaurante'],
            ['nombre' => 'Agua Mineral (500ml)',   'precio' => 2.50,  'stock' => 100, 'categoria' => 'minibar'],
            ['nombre' => 'Refresco Lata',          'precio' => 3.00,  'stock' => 80,  'categoria' => 'minibar'],
            ['nombre' => 'Cerveza Premium',        'precio' => 5.00,  'stock' => 60,  'categoria' => 'minibar'],
            ['nombre' => 'Snack / Bocadillo',      'precio' => 4.00,  'stock' => 40,  'categoria' => 'minibar'],
            ['nombre' => 'Lavandería (por prenda)', 'precio' => 3.50, 'stock' => 100, 'categoria' => 'lavandería'],
            ['nombre' => 'Servicio a Habitación',  'precio' => 8.00,  'stock' => 50,  'categoria' => 'otros'],
            ['nombre' => 'Parking (por día)',      'precio' => 10.00, 'stock' => 20,  'categoria' => 'otros'],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create(array_merge($servicio, ['activo' => true]));
        }
    }
}
