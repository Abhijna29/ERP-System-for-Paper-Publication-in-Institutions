<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Plan',
                'duration' => '1 Month',
                'price' => 999,
                'paper_limit' => 10,
                'download_limit' => 20,
                'summary' => 'Submit up to 10 papers and download 20 publications within 1 month.',
                'objective' => 'Ideal for small teams or trial usage.',
            ],
            [
                'name' => 'Professional Plan',
                'duration' => '3 Months',
                'price' => 2499,
                'paper_limit' => 20,
                'download_limit' => 40,
                'summary' => 'Submit up to 20 papers and download 40 publications over 3 months.',
                'objective' => 'Best for medium-sized research institutions.',
            ],
            [
                'name' => 'Advanced Plan',
                'duration' => '6 Months',
                'price' => 4499,
                'paper_limit' => 30,
                'download_limit' => 60,
                'summary' => 'Submit up to 30 papers and download 60 publications over 6 months.',
                'objective' => 'Great for active research institutions.',
            ],
            [
                'name' => 'Premium Plan',
                'duration' => '12 Months',
                'price' => 7999,
                'paper_limit' => 60,
                'download_limit' => 120,
                'summary' => 'Submit up to 60 papers and download 120 publications annually.',
                'objective' => 'Perfect for large academic institutions.',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
