<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'uuid' => 123,
            'first_name' => 'mostafa',
            'last_name' => 'kz',
            'phone' => '0911111111',
            'email' => 'mostafa@gmail.com',
            'password' => Hash::make('1234'),
            'birth' => '2000-12-02',
            'age' => 23,
            'salary' => 500000,
        ]);
        $admin->createToken('My-App')->plainTextToken;
    }
}