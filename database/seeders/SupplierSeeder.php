<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder{

    public function run(): void{
        for ($i=0; $i < 30 ; $i++) { 
            $phone = '01'.fake()->randomElement([0,1,2,5]).fake()->numberBetween(10000000, 99999999);
            Supplier::create([
                'name' => fake('ar_EG')->name(),
                'email' => fake()->email,
                'phone' => $phone,
                'address' => fake()->address,
                'status' => fake()->randomElement(['مفعل', "غير مفعل"]),
            ]);
        }
    }
}