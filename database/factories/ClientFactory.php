<?php

namespace Database\Factories;

use App\Domains\Clients\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'legal_name' => $this->faker->company,
            'vat_number' => $this->faker->ean8,
            'country' => 'COL',
            'economic_activity' => 'Retail',
            'payment_terms_days' => 30,
            'payment_conditions' => 'Net 30',
        ];
    }
}
