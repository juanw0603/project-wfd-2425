<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Beras'],
            ['name' => 'Minyak Goreng'],
            ['name' => 'Gula Pasir'],
            ['name' => 'Telur Ayam'],
            ['name' => 'Mie Instan'],
            ['name' => 'Kopi & Teh'],
            ['name' => 'Susu'],
            ['name' => 'Bumbu Dapur'],
            ['name' => 'Makanan Kaleng'],
            ['name' => 'Sabun & Deterjen'],
            ['name' => 'Perlengkapan Bayi'],
            ['name' => 'Makanan Ringan'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                // Tabel 'categories' diasumsikan tidak memiliki timestamps berdasarkan interaksi sebelumnya.
                // Jika ada, tambahkan 'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}