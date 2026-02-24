<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Users\Models\User;
use App\Domains\Clients\Models\Client;
use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Models\OrderLocation;
use App\Shared\Enums\OrderStatus;
use App\Shared\Enums\PaymentStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LocalClientSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create User
        $user = User::where('email', 'client@carri.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Cliente de Prueba',
                'email' => 'client@carri.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // 2. Assign Role
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }

        // 3. Create Client Profile
        $client = Client::updateOrCreate(
        ['user_id' => $user->id],
        [
            'legal_name' => 'Transportes Logísticos S.A.',
            'vat_number' => 'B12345678',
            'country' => 'España',
            'city' => 'Madrid',
            'address' => 'Calle Falsa 123',
            'state' => 'Madrid',
            'zip_code' => '28001',
            'activity_category' => 'Distribuidor',
            'payment_terms_days' => 30,
            'filled_by_name' => 'Admin Test',
            'filled_by_role' => 'Manager',
            'filled_by_phone' => '600123456',
            'filled_by_date' => now(),
        ]
        );

        // 4. Create some sample orders for this client
        // Clear existing orders to avoid duplicates if run multiple times
        Order::where('user_id', $user->id)->delete();

        for ($i = 1; $i <= 3; $i++) {
            $status = match ($i) {
                    1 => OrderStatus::PENDING,
                    2 => OrderStatus::IN_TRANSIT,
                    3 => OrderStatus::DELIVERED,
                };

            $order = Order::create([
                'uuid' => (string)Str::uuid(),
                'client_id' => $client->id,
                'user_id' => $user->id,
                'order_number' => 'REQ-' . strtoupper(Str::random(8)),
                'status' => $status,
                'payment_status' => PaymentStatus::PENDING,
                'total_amount' => 150.00 * $i,
                'cargo_type' => 'Paquetería General',
                'notes' => 'Pedido de prueba local #' . $i,
                'request_cmr' => true,
                'request_delivery_note' => true,
            ]);

            // Add locations for the order
            OrderLocation::create([
                'order_id' => $order->id,
                'type' => 'pickup',
                'city' => 'Madrid',
                'address' => 'Almacén Central',
                'state' => 'Madrid',
                'zip_code' => '28001',
                'country' => 'España', // Added country
                'sequence' => 1,
            ]);

            OrderLocation::create([
                'order_id' => $order->id,
                'type' => 'delivery',
                'city' => 'Barcelona',
                'address' => 'Centro Logístico BCN',
                'state' => 'Cataluña',
                'zip_code' => '08001',
                'country' => 'España', // Added country
                'sequence' => 2,
            ]);
        }

        $this->command->info('Usuario cliente@carri.com creado con éxito (pass: password)');
    }
}
