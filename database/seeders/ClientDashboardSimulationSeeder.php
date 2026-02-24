<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Domains\Users\Models\User;
use App\Domains\Clients\Models\Client;
use App\Domains\Orders\Models\Order;
use Illuminate\Support\Str;

class ClientDashboardSimulationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        // 1. Create a Test Client profile for the user
        $client = Client::updateOrCreate(
        ['user_id' => $user->id],
        [
            'legal_name' => 'Transportes Carri Road SL',
            'vat_number' => 'B00000000',
            'country' => 'España',
            'economic_activity' => 'Logística y Distribución',
            'logistics_contact_name' => 'Jose Logistics',
            'logistics_contact_email' => $user->email,
            'logistics_contact_phone' => '600123456',
            'address' => 'Calle Principal 123',
            'city' => 'Madrid',
            'state' => 'Madrid',
            'zip_code' => '28001',
        ]
        );

        // 2. Create a "Pending" order to verify the counter
        $order = Order::create([
            'uuid' => Str::uuid(),
            'client_id' => $client->id,
            'user_id' => $user->id,
            'order_number' => 'SIM-REQ-' . strtoupper(Str::random(6)),
            'cargo_type' => 'Refrigerada',
            'temperature' => '+4ºC',
            'status' => 'pending',
            'payment_status' => 'pending',
            'total_amount' => 0,
            'request_cmr' => true,
            'request_delivery_note' => true,
            'notes' => 'Carga de prueba para simulación de panel cliente.',
        ]);

        // Locations for the order
        $order->locations()->createMany([
            [
                'type' => 'pickup',
                'sequence' => 1,
                'address' => 'Calle Origen 45',
                'city' => 'Valencia',
                'state' => 'Valencia',
                'zip_code' => '46001',
                'country' => 'España',
                'scheduled_at' => now()->addDays(1),
            ],
            [
                'type' => 'delivery',
                'sequence' => 2,
                'address' => 'Calle Destino 89',
                'city' => 'Barcelona',
                'state' => 'Barcelona',
                'zip_code' => '08001',
                'country' => 'España',
                'scheduled_at' => now()->addDays(2),
            ]
        ]);

        $this->command->info('Simulación completada con éxito.');
        $this->command->info('Cliente vinculado: ' . $client->legal_name);
        $this->command->info('Órden pendiente creada: ' . $order->order_number);
    }
}
