<?php

namespace Database\Factories;

use App\Domains\Carriers\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'vat_number' => $this->faker->ean8,
            'email' => $this->faker->companyEmail,
            'payment_terms_days' => 30,
        ];
    }
}
