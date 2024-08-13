<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSegment;
use App\Models\Segment;
use App\Models\WarehouseProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder{

    public $products = [
        ['name' => 'بيبسي 1 لتر', 'purchasing_price' => 100, 'selling_segment_1_price' => 105, 'selling_segment_2_price' => 110, 'selling_segment_3_price' => 115],
        ['name' => 'بسكويت نواعم', 'purchasing_price' => 30, 'selling_segment_1_price' => 32, 'selling_segment_2_price' => 32, 'selling_segment_3_price' => 33],
        ['name' => 'شيبسي 50 جم', 'purchasing_price' => 15, 'selling_segment_1_price' => 16, 'selling_segment_2_price' => 16, 'selling_segment_3_price' => 17],
        ['name' => 'مياه معدنية 1.5 لتر', 'purchasing_price' => 5, 'selling_segment_1_price' => 6, 'selling_segment_2_price' => 6, 'selling_segment_3_price' => 7],
        ['name' => 'شاي العروسة 250 جم', 'purchasing_price' => 50, 'selling_segment_1_price' => 55, 'selling_segment_2_price' => 55, 'selling_segment_3_price' => 60],
        ['name' => 'لبن جهينة 1 لتر', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'جبنة مثلثات 8 قطع', 'purchasing_price' => 35, 'selling_segment_1_price' => 37, 'selling_segment_2_price' => 38, 'selling_segment_3_price' => 40],
        ['name' => 'زيت عباد الشمس 1 لتر', 'purchasing_price' => 60, 'selling_segment_1_price' => 65, 'selling_segment_2_price' => 65, 'selling_segment_3_price' => 70],
        ['name' => 'مكرونة 1 كجم', 'purchasing_price' => 10, 'selling_segment_1_price' => 12, 'selling_segment_2_price' => 12, 'selling_segment_3_price' => 13],
        ['name' => 'أرز 1 كجم', 'purchasing_price' => 15, 'selling_segment_1_price' => 17, 'selling_segment_2_price' => 17, 'selling_segment_3_price' => 18],
        ['name' => 'سكر 1 كجم', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'دقيق 1 كجم', 'purchasing_price' => 8, 'selling_segment_1_price' => 10, 'selling_segment_2_price' => 10, 'selling_segment_3_price' => 11],
        ['name' => 'صلصة طماطم 500 جم', 'purchasing_price' => 25, 'selling_segment_1_price' => 27, 'selling_segment_2_price' => 27, 'selling_segment_3_price' => 29],
        ['name' => 'خل 1 لتر', 'purchasing_price' => 5, 'selling_segment_1_price' => 6, 'selling_segment_2_price' => 6, 'selling_segment_3_price' => 7],
        ['name' => 'مربى فراولة 400 جم', 'purchasing_price' => 40, 'selling_segment_1_price' => 45, 'selling_segment_2_price' => 45, 'selling_segment_3_price' => 48],
        ['name' => 'شوكولاتة كادبوري', 'purchasing_price' => 15, 'selling_segment_1_price' => 17, 'selling_segment_2_price' => 17, 'selling_segment_3_price' => 18],
        ['name' => 'عصير مانجو 1 لتر', 'purchasing_price' => 25, 'selling_segment_1_price' => 28, 'selling_segment_2_price' => 28, 'selling_segment_3_price' => 30],
        ['name' => 'ملح طعام 1 كجم', 'purchasing_price' => 3, 'selling_segment_1_price' => 4, 'selling_segment_2_price' => 4, 'selling_segment_3_price' => 5],
        ['name' => 'عدس 1 كجم', 'purchasing_price' => 18, 'selling_segment_1_price' => 20, 'selling_segment_2_price' => 20, 'selling_segment_3_price' => 22],
        ['name' => 'فول 1 كجم', 'purchasing_price' => 12, 'selling_segment_1_price' => 14, 'selling_segment_2_price' => 14, 'selling_segment_3_price' => 15],
        ['name' => 'حبوب ذرة 400 جم', 'purchasing_price' => 10, 'selling_segment_1_price' => 12, 'selling_segment_2_price' => 12, 'selling_segment_3_price' => 13],
        ['name' => 'مسحوق غسيل 1 كجم', 'purchasing_price' => 50, 'selling_segment_1_price' => 55, 'selling_segment_2_price' => 55, 'selling_segment_3_price' => 60],
        ['name' => 'صابون تواليت', 'purchasing_price' => 5, 'selling_segment_1_price' => 6, 'selling_segment_2_price' => 6, 'selling_segment_3_price' => 7],
        ['name' => 'معجون أسنان 100 مل', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'شامبو 400 مل', 'purchasing_price' => 30, 'selling_segment_1_price' => 33, 'selling_segment_2_price' => 33, 'selling_segment_3_price' => 35],
        ['name' => 'مزيل عرق 50 مل', 'purchasing_price' => 25, 'selling_segment_1_price' => 27, 'selling_segment_2_price' => 27, 'selling_segment_3_price' => 29],
        ['name' => 'كريم بشرة 100 جم', 'purchasing_price' => 35, 'selling_segment_1_price' => 38, 'selling_segment_2_price' => 38, 'selling_segment_3_price' => 40],
        ['name' => 'بودرة أطفال 200 جم', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'حفاضات أطفال عبوة كبيرة', 'purchasing_price' => 100, 'selling_segment_1_price' => 110, 'selling_segment_2_price' => 110, 'selling_segment_3_price' => 120],
        ['name' => 'مناديل ورقية 100 ورقة', 'purchasing_price' => 10, 'selling_segment_1_price' => 12, 'selling_segment_2_price' => 12, 'selling_segment_3_price' => 13],
        ['name' => 'شمع العسل 500 جم', 'purchasing_price' => 40, 'selling_segment_1_price' => 45, 'selling_segment_2_price' => 45, 'selling_segment_3_price' => 48],
        ['name' => 'زيت زيتون 500 مل', 'purchasing_price' => 60, 'selling_segment_1_price' => 65, 'selling_segment_2_price' => 65, 'selling_segment_3_price' => 70],
        ['name' => 'كمون 100 جم', 'purchasing_price' => 15, 'selling_segment_1_price' => 17, 'selling_segment_2_price' => 17, 'selling_segment_3_price' => 18],
        ['name' => 'قهوة 200 جم', 'purchasing_price' => 40, 'selling_segment_1_price' => 45, 'selling_segment_2_price' => 45, 'selling_segment_3_price' => 48],
        ['name' => 'نسكافيه 100 جم', 'purchasing_price' => 50, 'selling_segment_1_price' => 55, 'selling_segment_2_price' => 55, 'selling_segment_3_price' => 60],
        ['name' => 'قرفة 100 جم', 'purchasing_price' => 15, 'selling_segment_1_price' => 17, 'selling_segment_2_price' => 17, 'selling_segment_3_price' => 18],
        ['name' => 'زنجبيل 100 جم', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'لبان دكر 100 جم', 'purchasing_price' => 10, 'selling_segment_1_price' => 12, 'selling_segment_2_price' => 12, 'selling_segment_3_price' => 13],
        ['name' => 'ليمون مجفف 100 جم', 'purchasing_price' => 25, 'selling_segment_1_price' => 28, 'selling_segment_2_price' => 28, 'selling_segment_3_price' => 30],
        ['name' => 'عسل نحل 500 جم', 'purchasing_price' => 80, 'selling_segment_1_price' => 85, 'selling_segment_2_price' => 85, 'selling_segment_3_price' => 90],
        ['name' => 'زبيب 200 جم', 'purchasing_price' => 25, 'selling_segment_1_price' => 28, 'selling_segment_2_price' => 28, 'selling_segment_3_price' => 30],
        ['name' => 'تمر 500 جم', 'purchasing_price' => 30, 'selling_segment_1_price' => 33, 'selling_segment_2_price' => 33, 'selling_segment_3_price' => 35],
        ['name' => 'مكسرات مشكلة 200 جم', 'purchasing_price' => 50, 'selling_segment_1_price' => 55, 'selling_segment_2_price' => 55, 'selling_segment_3_price' => 60],
        ['name' => 'تمر هندي 200 جم', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'حبوب كينوا 200 جم', 'purchasing_price' => 45, 'selling_segment_1_price' => 50, 'selling_segment_2_price' => 50, 'selling_segment_3_price' => 55],
        ['name' => 'لوبيا 1 كجم', 'purchasing_price' => 15, 'selling_segment_1_price' => 17, 'selling_segment_2_price' => 17, 'selling_segment_3_price' => 18],
        ['name' => 'حمص 1 كجم', 'purchasing_price' => 20, 'selling_segment_1_price' => 22, 'selling_segment_2_price' => 22, 'selling_segment_3_price' => 24],
        ['name' => 'فاصوليا بيضاء 1 كجم', 'purchasing_price' => 18, 'selling_segment_1_price' => 20, 'selling_segment_2_price' => 20, 'selling_segment_3_price' => 22],
        ['name' => 'بازلاء 1 كجم', 'purchasing_price' => 22, 'selling_segment_1_price' => 24, 'selling_segment_2_price' => 24, 'selling_segment_3_price' => 26],
    ];

    public function run(): void{
        
        foreach ($this->products as $key => $item) {
            $product = Product::create([
                'name' => $item['name'],
                'purchasing_price' => $item['purchasing_price'],
                'selling_price' => $item['selling_segment_1_price'],
            ]);
            $opening_balance = fake()->numberBetween(100, 200);
            $incoming_balance = fake()->numberBetween(1, 100);
            $outgoing_balance = fake()->numberBetween(1, 100);
            WarehouseProduct::create([
                'product_id' => $product->id,
                'opening_balance' => $opening_balance,
                'incoming_balance' => $incoming_balance,
                'outgoing_balance' => $outgoing_balance,
                'balance' => $opening_balance + $incoming_balance - $outgoing_balance
            ]);
            ProductSegment::create(['product_id' => $product->id,'segment_id' => 1,'segment_price' => $item['selling_segment_1_price']]);
            ProductSegment::create(['product_id' => $product->id,'segment_id' => 2,'segment_price' => $item['selling_segment_2_price']]);
            ProductSegment::create(['product_id' => $product->id,'segment_id' => 3,'segment_price' => $item['selling_segment_3_price']]);
        }
    }
}