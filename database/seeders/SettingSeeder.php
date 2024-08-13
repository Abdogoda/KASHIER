<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder{
    public function run(): void{
        $settings = [
            [
                'key' => 'اسم_الشركة',
                'value' => 'شركة كاشير لتجارة الجملة'
            ],
            [
                'key' => 'اسم_المدير',
                'value' => 'owner'
            ],
            [
                'key' => 'لوجو_الشركة',
                'value' => ''
            ],
            [
                'key' => 'البريد_الالكتروني_للشركة',
                'value' => 'support@example.com'
            ],
            [
                'key' => 'رقم_الشركة_1',
                'value' => '01234567891'
            ],
            [
                'key' => 'رقم_الشركة_2',
                'value' => '01099652564'
            ],
            [
                'key' => 'عنوان_الشركة',
                'value' => 'عنوان الشركة'
            ],
            [
                'key' => 'موقع_الشركة',
                'value' => 'https://maps.app.goo'
            ],
            // [
            //     'key' => 'الحد_الادني_لعمل_انذار',
            //     'value' => 5
            // ],
            // [
            //     'key' => 'ظبط_التوقيت_الصيفي',
            //     'value' => 'نعم'
            // ],
        ];
        

        DB::table('settings')->insert($settings);
    }
}