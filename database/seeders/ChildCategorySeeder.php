<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChildCategory;
use App\Models\SubCategory;
use App\Models\Category; // Add the Category model

class ChildCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Define the child categories for each subcategory
        $childcategories = [
            'DataScience' => ['Python', 'R', 'Machine Learning'],
            'Artificial Intelligence and Machine Learning' => ['Neural Networks', 'Deep Learning', 'Computer Vision'],
            'Networking' => ['TCP/IP', 'Cloud Computing', 'Cybersecurity'],

            'Comparative Literature' => [' Cross-Cultural Studies', 'Translation Studies', 'World Literature'],
            'Genre Studies' => [' Poetry', 'Drama', ' Fiction'],

            'Ancient History' => [' Mesopotamian Civilizations', 'Classical Greece', 'Roman Empire'],
            'Modern History' => ['World Wars', 'Cold War', 'Industrial Revolution'],
            'Cultural History' => ['Art History', 'Religion & Belief Systems', 'Food & Daily Life'],

            'Pure Mathematics' => ['Algebra', 'Topology', 'Number Theory'],
            'Applied Mathematics' => ['Numerical Analysis', 'Optimization', 'Mathematical Modelling'],
            'Statistics' => ['Probability Theory', 'Inferential Statistics', 'Bayesian Analysis'],

            // 'Microeconomics' => ['Consumer Theory', 'Market Structures', 'Game Theory'],
            // 'Macroeconomics' => ['Fiscal Policy', 'Monetary Theory', 'Economic Growth'],
            // 'Development Economics' => ['Poverty Studies', 'Education & Health', 'Sustainable Development'],

            // 'Clinical Medicine' => ['Cardiology', 'Neurology', 'Oncology'],
            // 'Public Health' => ['Epidemiology', 'Health Policy', 'Disease Prevention'],
            // 'Biomedical Research' => ['Genetics', 'Immunology', 'Pharmacology'],

            'Earth Sciences' => ['Geology', 'Meteorology', 'Oceanography'],
            'Environmental Science' => ['Climate Change', 'Conservation', 'Pollution Studies'],
            'Biological Sciences' => ['Ecology', 'Zoology', 'Botany']
        ];

        // Loop through each subcategory and create child categories
        foreach ($childcategories as $subCategoryName => $childCategoryNames) {
            // Find the subcategory by its name
            $subCategory = SubCategory::where('name', $subCategoryName)->first();

            if ($subCategory) {
                // Get the associated category for the subcategory (ensure the relationship exists)
                $category = $subCategory->category;

                // Create child categories for the found subcategory
                foreach ($childCategoryNames as $childCategoryName) {
                    ChildCategory::firstOrCreate([
                        'name' => $childCategoryName,
                        'sub_category_id' => $subCategory->id,
                        'category_id' => $category->id
                    ]);
                }
            }
        }
    }
}
