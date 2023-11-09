<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Truncate subscription_plans table
        DB::table('subscription_plans')->truncate();

        DB::table('subscription_plans')->insert([
            'type' => 'free',
            'image_path' => 'assets/images/products/page-pricing-basic.png',
            'max_project' => 1,
            'max_character' => 100000,
            'max_message' => 40,
            'price' => 0
        ]);

        DB::table('subscription_plans')->insert([
            'type' => 'standard',
            'image_path' => 'assets/images/products/page-pricing-standard.png',
            'max_project' => 5,
            'max_character' => 400000,
            'max_message' => 4000,
            'price' => 39
        ]);

        DB::table('subscription_plans')->insert([
            'type' => 'premium',
            'image_path' => 'assets/images/products/page-pricing-enterprise.png',
            'max_project' => 10,
            'max_character' => 1000000,
            'max_message' => 10000,
            'price' => 99
        ]);
    }
}
