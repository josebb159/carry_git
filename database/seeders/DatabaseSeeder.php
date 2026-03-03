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

        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('Admin1234!')]
        );
        $admin->syncRoles(['admin']);

        $merchant = User::firstOrCreate(
            ['email' => 'merchant@example.com'],
            ['name' => 'Merchant User', 'password' => bcrypt('Merchant1234!')]
        );
        $merchant->syncRoles(['merchant']);

        $driver = User::firstOrCreate(
            ['email' => 'driver@example.com'],
            ['name' => 'Driver User', 'password' => bcrypt('Driver1234!')]
        );
        $driver->syncRoles(['delivery']);
    }
}
