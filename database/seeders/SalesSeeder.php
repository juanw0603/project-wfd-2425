<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('id')->toArray();
        $productData = DB::table('products')->select('id', 'price', 'stock')->get();

        if (empty($userIds)) {
            $this->command->info('No users found, skipping sale seeding. Please run UserSeeder first.');
            return;
        }
        if ($productData->isEmpty()) {
            $this->command->info('No products found, skipping sale seeding. Please run ProductSeeder first.');
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            $saleDate = $faker->dateTimeBetween('-1 year', 'now');
            $userId = $faker->randomElement($userIds);

            // Create Sale record
            $saleId = DB::table('sales')->insertGetId([
                'user_id' => $userId,
                'sale_date' => $saleDate, // Gunakan full datetime
                'total_price' => 0,
                'created_at' => $saleDate,
                'updated_at' => $saleDate,
            ]);

            $totalSalePrice = 0;
            $numberOfItemsInSale = $faker->numberBetween(1, 3);
            $availableProducts = $productData->where('stock', '>', 0);

            if ($availableProducts->isEmpty()) continue;

            $selectedProducts = $availableProducts->random(min($numberOfItemsInSale, $availableProducts->count()))->all();

            foreach ($selectedProducts as $product) {
                $quantity = $faker->numberBetween(1, min(5, $product->stock));
                $pricePerUnit = $product->price;
                $subtotal = $quantity * $pricePerUnit;

                DB::table('sale_items')->insert([
                    'sale_id' => $saleId,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal,
                    'created_at' => $saleDate,
                    'updated_at' => $saleDate,
                ]);

                $totalSalePrice += $subtotal;
            }

            DB::table('sales')->where('id', $saleId)->update([
                'total_price' => $totalSalePrice
            ]);
        }
    }
}
