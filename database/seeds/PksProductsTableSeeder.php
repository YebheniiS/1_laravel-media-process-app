<?php

use App\PksProduct;
use Illuminate\Database\Seeder;

class PksProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PksProduct::firstOrCreate([
            'product_name' => 'interactr_basic',
            'product_id' => 11,
            'is_pro' => 0,
            'is_agency' => 0,
            'downgrade_id' => 0,
            'usage_plan_id' => 1
        ]);

        PksProduct::firstOrCreate([
            'product_name' => 'interactr_pro',
            'product_id' => 22,
            'is_pro' => 1,
            'is_agency' => 0,
            'downgrade_id' => 0,
            'usage_plan_id' => 2
        ]);

        PksProduct::firstOrCreate([
            'product_name' => 'interactr_poweruser',
            'product_id' => 33,
            'is_pro' => 1,
            'is_agency' => 0,
            'downgrade_id' => 0,
            'usage_plan_id' => 3
        ]);

        PksProduct::firstOrCreate([
            'product_name' => 'interactr_agency',
            'product_id' => 44,
            'is_pro' => 0,
            'is_agency' => 1,
            'downgrade_id' => 0,
            'usage_plan_id' => 0
        ]);

        PksProduct::firstOrCreate([
            'product_name' => 'interactr_pro_fb',
            'product_id' => 55,
            'is_pro' => 1,
            'is_agency' => 0,
            'downgrade_id' => 11,
            'usage_plan_id' => 2
        ]);

        PksProduct::firstOrCreate([
            'product_name' => 'interactr_poweruser_fb',
            'product_id' => 66,
            'is_pro' => 1,
            'is_agency' => 0,
            'downgrade_id' => 55,
            'usage_plan_id' => 3
        ]);
    }
}
