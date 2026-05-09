<?php

namespace Database\Seeders;

use App\Models\Superadmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Psy\Sudo;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Superadmin::create([
            'name' => 'MBK',
            'email' => 'mbk47@gmail.com',
            'password'=>Hash::make('mbk990218'),
            //'role_id' => '1',
        ]);
    }
}
  