<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\Schema; // Uncomment jika menggunakan pengecekan foreign key
// use Illuminate\Support\Facades\DB;    // Uncomment jika melakukan truncate tabel

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Opsional: Nonaktifkan pengecekan foreign key sebelum menjalankan seeder dan aktifkan kembali setelahnya.
        // Ini bisa berguna jika Anda perlu melakukan truncate tabel atau jika seeder memiliki dependensi yang kompleks.
        // Schema::disableForeignKeyConstraints();

        // Opsional: Truncate tabel sebelum seeding jika Anda ingin memulai dari awal.
        // Hati-hati dengan urutan jika Anda memiliki constraint foreign key.
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Untuk MySQL
        // DB::table('purchase_items')->truncate();
        // DB::table('sale_items')->truncate();
        // DB::table('purchases')->truncate();
        // DB::table('sales')->truncate();
        // DB::table('products')->truncate();
        // DB::table('suppliers')->truncate();
        // DB::table('categories')->truncate();
        // DB::table('users')->truncate();

        $this->call([
            UserSeeder::class,
            CategoriesSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,    // Bergantung pada CategorySeeder
            PurchasesSeeder::class,   // Bergantung pada UserSeeder, SupplierSeeder, ProductSeeder. Membuat Purchases.
            PurchaseItemsSeeder::class, // Bergantung pada PurchasesSeeder, ProductSeeder. Membuat PurchaseItems. (Gunakan PruchaseItemsSeeder::class jika nama file belum diubah)
            SalesSeeder::class,       // Bergantung pada UserSeeder, ProductSeeder. Membuat Sales dan SaleItems.
        ]);

        // Schema::enableForeignKeyConstraints(); // Aktifkan kembali jika dinonaktifkan sebelumnya
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Untuk MySQL
    }
}
