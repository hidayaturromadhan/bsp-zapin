<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsCategory;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Media', 'slug' => 'media', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Publikasi', 'slug' => 'publikasi', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'TJSL', 'slug' => 'tjsl', 'sort_order' => 3, 'is_active' => true],
        ];

        foreach ($items as $it) {
            NewsCategory::updateOrCreate(
                ['slug' => $it['slug']],
                $it
            );
        }
    }
}