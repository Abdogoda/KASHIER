<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Segment;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder{

    public function run(): void{
        $segments = Segment::pluck('id')->toArray();
        for ($i=0; $i < 30 ; $i++) { 
            $phone = '01'.fake()->randomElement([0,1,2,5]).fake()->numberBetween(10000000, 99999999);
            Customer::create([
                'name' => fake('ar_EG')->name(),
                'email' => fake()->email,
                'phone' => $phone,
                'address' => fake()->address,
                'segment_id' => fake()->randomElement($segments),
                'status' => fake()->randomElement(['مفعل', "غير مفعل"]),
            ]);
        }
    }
}