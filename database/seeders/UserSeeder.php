<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Ahmed Benali',   'initials' => 'AB', 'email' => 'ahmed@agrobio.ma',   'role' => 'admin'],
            ['name' => 'Mohammed Amine', 'initials' => 'MA', 'email' => 'amine@agrobio.ma',   'role' => 'manager'],
            ['name' => 'Sara Alaoui',    'initials' => 'SA', 'email' => 'sara@agrobio.ma',    'role' => 'staff'],
            ['name' => 'Fatima Zahra',   'initials' => 'FZ', 'email' => 'fatima@agrobio.ma',  'role' => 'staff'],
            ['name' => 'Anas Karim',     'initials' => 'AK', 'email' => 'anas@agrobio.ma',    'role' => 'staff'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                array_merge($u, ['password' => Hash::make('password')])
            );
        }
    }
}
