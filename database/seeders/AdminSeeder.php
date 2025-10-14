<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@valtus.com'],
            [
                'name' => 'Admin Valtus',
                'email' => 'admin@valtus.com',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
