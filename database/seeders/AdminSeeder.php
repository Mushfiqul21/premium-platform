<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Traits\HasRoles;

class AdminSeeder extends Seeder
{
    use HasRoles;
    public function run(): void
    {
       $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('admin');
    }
}
