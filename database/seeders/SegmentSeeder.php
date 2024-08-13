<?php

namespace Database\Seeders;

use App\Models\Segment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SegmentSeeder extends Seeder{
    public function run(): void{
        $segments = ['الشريحة الأولي', 'الشريحة الثانية', 'الشريحة الثالثة'];
        foreach ($segments as $segment) {
            Segment::create(['name' => $segment]);
        }
    }
}