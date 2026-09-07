<?php

namespace Database\Seeders;

use App\Http\Controllers\PageController;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = PageController::seedPages();

        foreach ($pages as $slug => $data) {
            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'heading' => $data['heading'],
                    'lead' => $data['lead'] ?? null,
                    'content' => $data['content'] ?? null,
                    'breadcrumbs' => $data['breadcrumbs'] ?? null,
                    'is_published' => true,
                    'meta_title' => $data['heading'],
                ]
            );
        }
    }
}
