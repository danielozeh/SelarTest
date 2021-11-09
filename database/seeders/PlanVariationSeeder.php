<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan_variations = [
            [
                'id' => 1,
                'plan_id' => 1,
                'name' => 'Monthly',
                'amount' => '8000',
                'days' => '30',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'plan_id' => 1,
                'name' => 'Yearly',
                'amount' => '80000',
                'days' => '365',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'plan_id' => 2,
                'name' => 'Monthly',
                'amount' => '15000',
                'days' => '30',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'plan_id' => 2,
                'name' => 'Yearly',
                'amount' => '150000',
                'days' => '365',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        DB::table('plan_variations')->insert($plan_variations);
    }
}
