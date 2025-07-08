<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Visible to all
            ['title' => 'How do I reset my password?', 'description' => 'Click on "Forgot Password" at login.', 'role' => null],

            // Researcher FAQs
            ['title' => 'How to submit a research paper?', 'description' => 'Go to Dashboard > Submit Paper.', 'role' => 'researcher'],
            ['title' => 'How to track my paper status?', 'description' => 'Check the paper status in your submissions.', 'role' => 'researcher'],

            // Reviewer FAQs
            ['title' => 'How to access assigned reviews?', 'description' => 'Navigate to Review Assignments.', 'role' => 'reviewer'],

            // Institution Admin FAQs
            ['title' => 'How to add researchers?', 'description' => 'Go to Manage Users and assign role: Researcher.', 'role' => 'institution'],

            // Department User FAQs
            ['title' => 'What permissions do department users have?', 'description' => 'They can view reports and oversee submissions.', 'role' => 'department'],
        ];

        foreach ($data as $faq) {
            Faq::create($faq);
        }
    }
}
