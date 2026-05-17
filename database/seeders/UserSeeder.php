<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Ahmad Khalil',   'phone' => '0911111111'],
            ['name' => 'Sara Hamdan',    'phone' => '0922222222'],
            ['name' => 'Omar Farouk',    'phone' => '0933333333'],
            ['name' => 'Layla Nasser',   'phone' => '0944444444'],
            ['name' => 'Khaled Mansour', 'phone' => '0955555555'],
            ['name' => 'Rania Zayed',    'phone' => '0966666666'],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['phone' => $data['phone']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
        }
    }
}
