<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        // Admin User
        DB::table('users')->insert([
            'name' => 'Admin Sembako',
            'email' => 'admin@sembako.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Kasir User
        DB::table('users')->insert([
            'name' => 'Kasir Toko',
            'email' => 'kasir@sembako.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'kasir',
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Gudang User
        DB::table('users')->insert([
            'name' => 'Staff Gudang',
            'email' => 'gudang@sembako.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role' => 'gudang',
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Generate 5 more random users (kasir or gudang)
        for ($i = 0; $i < 5; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => $faker->randomElement(['kasir', 'gudang']),
                'created_at' => now(),
                'updated_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
