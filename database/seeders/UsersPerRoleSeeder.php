<?php

namespace Database\Seeders;

use App\Domains\Users\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersPerRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Primero aseguramos que los roles existan
        $this->call(RoleSeeder::class);

        $users = [
            [
                'name'     => 'Admin Principal',
                'email'    => 'admin@carri.com',
                'password' => Hash::make('Admin1234!'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Agente Operativo',
                'email'    => 'agent@carri.com',
                'password' => Hash::make('Agent1234!'),
                'role'     => 'agent',
            ],
            [
                'name'     => 'Comerciante Demo',
                'email'    => 'merchant@carri.com',
                'password' => Hash::make('Merchant1234!'),
                'role'     => 'merchant',
            ],
            [
                'name'     => 'Repartidor Demo',
                'email'    => 'delivery@carri.com',
                'password' => Hash::make('Delivery1234!'),
                'role'     => 'delivery',
            ],
            [
                'name'     => 'Usuario General',
                'email'    => 'user@carri.com',
                'password' => Hash::make('User1234!'),
                'role'     => 'user',
            ],
        ];

        foreach ($users as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $user->syncRoles([$role]);
        }
    }
}
