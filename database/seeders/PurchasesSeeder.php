<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('id')->toArray();
        $supplierIds = DB::table('suppliers')->pluck('id')->toArray();
        $productRecords = DB::table('products')->select('id', 'price')->get();

        if (empty($userIds)) {
            $this->command->info('No users found, skipping purchase seeding. Please run UserSeeder first.');
            return;
        }
        if (empty($supplierIds)) {
            $this->command->info('No suppliers found, skipping purchase seeding. Please run SupplierSeeder first.');
            return;
        }
        if ($productRecords->isEmpty()) {
            $this->command->info('No products found, skipping purchase seeding. Please run ProductSeeder first.');
            return;
        }

        for ($i = 0; $i < 20; $i++) { // Create 20 purchases
            $purchaseDate = $faker->dateTimeBetween('-1 year', 'now');
            $userId = $faker->randomElement($userIds);
            $supplierId = $faker->randomElement($supplierIds);

            $purchaseId = DB::table('purchases')->insertGetId([
                'user_id' => $userId,
                'supplier_id' => $supplierId,
                'purchase_date' => Carbon::instance($purchaseDate)->toDateString(),
                'total_price' => 0, // Akan diupdate oleh PurchaseItemsSeeder
                'created_at' => $purchaseDate, // purchases table has timestamps
                'updated_at' => $purchaseDate,
            ]);

            // Item pembelian akan ditambahkan oleh PurchaseItemsSeeder
        }
        $this->command->info('Seeding pembelian selesai (tanpa item). Jalankan PurchaseItemsSeeder untuk menambahkan item.');
    }
}