<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $supplierNames = ['PT. Sinar Jaya Abadi', 'CV. Berkah Tani', 'UD. Sumber Rejeki', 'Agen Grosir Maju Lancar', 'Distributor Pangan Nusantara'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('suppliers')->insert([
                'name' => $faker->randomElement($supplierNames) . ' ' . $faker->companySuffix,
                'contact' => $faker->phoneNumber,
                'address' => $faker->address,
                // Tabel 'suppliers' diasumsikan tidak memiliki timestamps berdasarkan interaksi sebelumnya.
                // Jika ada, tambahkan 'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}
