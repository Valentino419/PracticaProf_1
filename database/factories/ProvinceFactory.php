<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = \App\Models\Province::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Buenos Aires', 'Catamarca', 'Chaco', 'Chubut', 'Córdoba', 'Corrientes',
                'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja', 'Mendoza',
                'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
                'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
                'Ciudad Autónoma de Buenos Aires'
            ]),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (\App\Models\Province $province) {
            // Optionally customize after making
        })->afterCreating(function (\App\Models\Province $province) {
            // Optionally customize after creating
        });
    }
}
