<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // GANTI kata sandi ini segera setelah login pertama!
        User::firstOrCreate(['email' => 'admin@minimarket.test'], [
            'name' => 'Administrator', 'role' => 'admin', 'is_active' => true, 'password' => Hash::make('admin12345'),
        ]);
        User::firstOrCreate(['email' => 'kasir@minimarket.test'], [
            'name' => 'Kasir 01', 'role' => 'kasir', 'is_active' => true, 'password' => Hash::make('kasir12345'),
        ]);
    }
}
