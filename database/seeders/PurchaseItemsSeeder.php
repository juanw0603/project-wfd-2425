<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PurchaseItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $productRecords = DB::table('products')->select('id', 'price')->get();

        if ($productRecords->isEmpty()) {
            return;
        }

        $purchases = DB::table('purchases')
                        ->where('total_price', '=', 0) // Hanya proses purchase yang belum memiliki item/total
                        ->select('id', 'purchase_date', 'created_at')
                        ->get();

        if ($purchases->isEmpty()) {
            return;
        }

        $this->command->info('Memulai seeding item pembelian untuk ' . $purchases->count() . ' pembelian...');

        foreach ($purchases as $purchase) {
            $totalPurchasePrice = 0;
            $numberOfItemsInPurchase = $faker->numberBetween(1, 4);
            $itemTimestamp = $purchase->created_at ? Carbon::parse($purchase->created_at) : Carbon::parse($purchase->purchase_date)->startOfDay();

            $itemsForCurrentPurchase = [];
            for ($j = 0; $j < $numberOfItemsInPurchase; $j++) {
                if ($productRecords->isEmpty()) {
                    break;
                }
                $product = $productRecords->random();
                $quantity = $faker->numberBetween(10, 100);
                $pricePerUnit = round($product->price * $faker->randomFloat(2, 0.6, 0.8), 2);
                $subtotal = $quantity * $pricePerUnit;

                $itemsForCurrentPurchase[] = [
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price_per_unit' => $pricePerUnit,
                    'subtotal' => $subtotal,
                    'created_at' => $itemTimestamp,
                    'updated_at' => $itemTimestamp,
                ];
                $totalPurchasePrice += $subtotal;
            }

            if (!empty($itemsForCurrentPurchase)) {
                DB::table('purchase_items')->insert($itemsForCurrentPurchase); 
                DB::table('purchases')->where('id', $purchase->id)->update(['total_price' => $totalPurchasePrice]);
            }
        }
        $this->command->info('Proses seeding item pembelian selesai.');
    }
}
