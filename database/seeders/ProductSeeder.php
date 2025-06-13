<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $categoryIds = DB::table('categories')->pluck('id', 'name')->toArray();

        if (empty($categoryIds)) {
            $this->command->info('Tidak ada kategori ditemukan, lewati seeder produk. Jalankan CategorySeeder terlebih dahulu.');
            return;
        }

        $productsData = [
            'Beras' => [
                ['name' => 'Beras Pandan Wangi Super', 'price_range' => [60000, 75000], 'unit' => '5kg'],
                ['name' => 'Beras Rojolele Premium', 'price_range' => [120000, 140000], 'unit' => '10kg'],
                ['name' => 'Beras Merah Organik', 'price_range' => [25000, 35000], 'unit' => '1kg'],
            ],
            'Minyak Goreng' => [
                ['name' => 'Minyak Goreng Sania', 'price_range' => [30000, 38000], 'unit' => '2L'],
                ['name' => 'Minyak Goreng Bimoli Klasik', 'price_range' => [18000, 22000], 'unit' => '1L'],
                ['name' => 'Minyak Goreng Tropical Botol', 'price_range' => [32000, 40000], 'unit' => '2L'],
            ],
            'Gula Pasir' => [
                ['name' => 'Gula Pasir Gulaku Premium', 'price_range' => [14000, 17000], 'unit' => '1kg'],
                ['name' => 'Gula Pasir Rose Brand Kuning', 'price_range' => [13500, 16000], 'unit' => '1kg'],
            ],
            'Telur Ayam' => [
                ['name' => 'Telur Ayam Negeri Curah', 'price_range' => [25000, 30000], 'unit' => '1kg'],
                ['name' => 'Telur Ayam Omega 3 Pack', 'price_range' => [28000, 35000], 'unit' => '10 butir'],
            ],
            'Mie Instan' => [
                ['name' => 'Indomie Goreng Spesial', 'price_range' => [3000, 3500], 'unit' => 'pcs'],
                ['name' => 'Mie Sedaap Soto', 'price_range' => [2800, 3300], 'unit' => 'pcs'],
                ['name' => 'Sarimi Isi 2 Ayam Bawang', 'price_range' => [3500, 4000], 'unit' => 'pcs'],
            ],
            'Kopi & Teh' => [
                ['name' => 'Kopi Kapal Api Special Mix', 'price_range' => [15000, 18000], 'unit' => 'renceng'],
                ['name' => 'Teh Celup Sariwangi Asli', 'price_range' => [6000, 8000], 'unit' => 'box 25s'],
            ],
             'Susu' => [
                ['name' => 'Susu Kental Manis Frisian Flag', 'price_range' => [10000, 13000], 'unit' => 'kaleng'],
                ['name' => 'Susu UHT Ultra Milk Coklat', 'price_range' => [18000, 22000], 'unit' => '1L'],
            ],
        ];

        foreach ($productsData as $categoryName => $items) {
            if (isset($categoryIds[$categoryName])) {
                $categoryId = $categoryIds[$categoryName];
                foreach ($items as $item) {
                    $stock = $faker->numberBetween(50, 300);
                    DB::table('products')->insert([
                        'category_id' => $categoryId,
                        'name' => $item['name'] . ' ' . $item['unit'],
                        'price' => $faker->numberBetween($item['price_range'][0], $item['price_range'][1]),
                        'stock' => $stock,
                        'minimal_stock' => $faker->numberBetween(10, min(50, $stock > 10 ? $stock - 5 : 10)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Tambahkan beberapa produk acak jika kategori lain ada
        $otherCategoryIds = array_diff($categoryIds, array_flip(array_keys($productsData)));
        foreach ($otherCategoryIds as $catName => $catId) {
            for ($i = 0; $i < 3; $i++) { // 3 produk acak per kategori lain
                 $stock = $faker->numberBetween(20, 150);
                 DB::table('products')->insert([
                    'category_id' => $catId,
                    'name' => 'Produk ' . $catName . ' ' . $faker->word . ' ' . $faker->randomElement(['Kecil', 'Sedang', 'Besar']),
                    'price' => $faker->numberBetween(5000, 50000),
                    'stock' => $stock,
                    'minimal_stock' => $faker->numberBetween(5, min(20, $stock > 5 ? $stock - 2 : 5)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
