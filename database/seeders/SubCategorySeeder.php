<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the subcategories under each category
        $subcategories = [
            'Computer Science' => [
                'DataScience',
                'Artificial Intelligence and Machine Learning',
                'Networking'
            ],
            'Literature' => [
                'Comparative Literature',
                'Genre Studies'
            ],
            'History' => [
                'Ancient History',
                'Modern History',
                'Cultural History'
            ],
            'Mathematics' => [
                'Pure Mathematics',
                'Applied Mathematics',
                'Statistics'
            ],
            // 'Economics' => [
            //     'Microeconomics',
            //     'Macroeconomics',
            //     'Development Economics'
            // ],
            // 'Medical Sciences' => [
            //     'Clinical Medicine',
            //     'Public Health',
            //     'Biomedical Research'
            // ],
            'Natural Sciences' => [
                'Earth Sciences',
                'Environmental Science',
                'Biological Sciences'
            ],
        ];

        // Loop through each category and create subcategories
        foreach ($subcategories as $categoryName => $subCategoryNames) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($subCategoryNames as $subCategoryName) {
                SubCategory::firstOrCreate([
                    'name' => $subCategoryName,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}
