<?php

use App\UsagePlans;

use Illuminate\Database\Seeder;
class UsagePlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsagePlans::firstOrCreate([
            'name' => 'interactr_basic',
            'streaming_mins' => 1000,
            'upload_gb' => 1            
        ]);

        UsagePlans::firstOrCreate([
            'name' => 'interactr_pro',
            'streaming_mins' => 3000,
            'upload_gb' => 3,
        ]);

        UsagePlans::firstOrCreate([
            'name' => 'interactr_poweruser',
            'streaming_mins' => 5000,
            'upload_gb' => 5
        ]);
    }
}
