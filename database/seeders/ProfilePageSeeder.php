<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class ProfilePageSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Parent Page: Profil
        $profil = Page::updateOrCreate(
            ['slug' => 'profil'],
            [
                'title' => 'Profil',
                'parent_id' => null,
                'menu_group' => 'profil',
                'sort_order' => 1,
                'content' => null,
                'is_active' => true,
            ]
        );

        // 2️⃣ Sub Pages Profil
        $subPages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'sort_order' => 1,
                'content' => '<p>Isi halaman Tentang Kami...</p>',
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'sort_order' => 2,
                'content' => '<p>Isi halaman Visi & Misi...</p>',
            ],
            [
                'title' => 'Sejarah',
                'slug' => 'sejarah',
                'sort_order' => 3,
                'content' => '<p>Isi halaman Sejarah...</p>',
            ],
            [
                'title' => 'Pemegang Saham',
                'slug' => 'pemegang-saham',
                'sort_order' => 4,
                'content' => '<p>Isi halaman Pemegang Saham...</p>',
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'sort_order' => 5,
                'content' => '<p>Isi halaman Struktur Organisasi...</p>',
            ],
            [
                'title' => 'Health, Safety & Environment',
                'slug' => 'hse',
                'sort_order' => 6,
                'content' => '<p>Isi halaman HSE...</p>',
            ],
        ];

        foreach ($subPages as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                [
                    'title' => $page['title'],
                    'parent_id' => $profil->id,
                    'menu_group' => 'profil',
                    'sort_order' => $page['sort_order'],
                    'content' => $page['content'],
                    'is_active' => true,
                ]
            );
        }
    }
}
