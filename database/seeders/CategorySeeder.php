<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Oncology',
                'description' => 'Cancer therapies including solid tumours, haematology and supportive care.',
                'subcategories' => ['Solid tumours', 'Haematology', 'Breast cancer', 'Lung cancer', 'Supportive care', 'Other'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Hepatology',
                'description' => 'Liver disease treatments including hepatitis B and C therapies.',
                'subcategories' => ['Hepatitis B', 'Hepatitis C', 'Liver disease', 'Cirrhosis', 'Other'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Diabetes',
                'description' => 'Blood glucose management products, insulins and complication care.',
                'subcategories' => ['Type 2 diabetes', 'Type 1 diabetes', 'Insulin', 'Diabetic complications', 'Other'],
                'sort_order' => 3,
            ],
            [
                'name' => 'Cardiovascular',
                'description' => 'Heart and circulatory system medicines including anticoagulants and statins.',
                'subcategories' => ['Hypertension', 'Heart failure', 'Anticoagulation', 'Dyslipidaemia', 'Other'],
                'sort_order' => 4,
            ],
            [
                'name' => 'Respiratory',
                'description' => 'Asthma, COPD, allergy and other respiratory condition treatments.',
                'subcategories' => ['Asthma', 'COPD', 'Allergy', 'Cystic fibrosis', 'Other'],
                'sort_order' => 5,
            ],
            [
                'name' => 'Other',
                'description' => 'Products that do not fit into the main therapeutic categories.',
                'subcategories' => ['Other'],
                'sort_order' => 99,
            ],
        ];

        foreach ($categories as $data) {
            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = true;
            Category::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
