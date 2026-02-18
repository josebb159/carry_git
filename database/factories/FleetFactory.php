<?php

namespace Database\Factories;

use App\Domains\Fleet\Models\Fleet;
use App\Domains\Carriers\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class FleetFactory extends Factory
{
    protected $model = Fleet::class;

    public function definition()
    {
        return [
            'carrier_id' => Carrier::factory(),
            'truck_type' => 'Van',
            'refrigeration_type' => 'None',
            'capacity_cbm' => 20,
            'double_driver' => false,
        ];
    }
}
