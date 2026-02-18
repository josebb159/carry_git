<?php

namespace Database\Seeders;

use App\Domains\Users\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $merchant = User::factory()->create([
            'name' => 'Merchant User',
            'email' => 'merchant@example.com',
        ]);
        $merchant->assignRole('merchant');

        $driver = User::factory()->create([
            'name' => 'Driver User',
            'email' => 'driver@example.com',
        ]);
        $driver->assignRole('delivery');
    }
}
