<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageTranslation;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // halaman utama yg dipakai route whitelist
        $pages = [
            [
                'menu_group' => 'main',
                'sort_order' => 1,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Profil',
                        'slug' => 'profil',
                        'content' => '<p>Konten profil (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Profile',
                        'slug' => 'profil', // aman untuk sekarang, supaya whitelist route tidak perlu diubah
                        'content' => '<p>Profile content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 2,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Layanan',
                        'slug' => 'layanan',
                        'content' => '<p>Konten layanan (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Services',
                        'slug' => 'layanan',
                        'content' => '<p>Services content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 3,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Operasional',
                        'slug' => 'operasional',
                        'content' => '<p>Konten operasional (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Operations',
                        'slug' => 'operasional',
                        'content' => '<p>Operations content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 4,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'TJSL',
                        'slug' => 'tjsl',
                        'content' => '<p>Konten TJSL (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'CSR (TJSL)',
                        'slug' => 'tjsl',
                        'content' => '<p>CSR/TJSL content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 5,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Publikasi',
                        'slug' => 'publikasi',
                        'content' => '<p>Konten publikasi (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Publications',
                        'slug' => 'publikasi',
                        'content' => '<p>Publications content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 6,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Hubungan Investor',
                        'slug' => 'hubungan-investor',
                        'content' => '<p>Konten hubungan investor (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Investor Relations',
                        'slug' => 'hubungan-investor',
                        'content' => '<p>Investor relations content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
            [
                'menu_group' => 'main',
                'sort_order' => 7,
                'is_active' => true,
                'cover_image' => null,
                'translations' => [
                    'id' => [
                        'title' => 'Kontak',
                        'slug' => 'kontak',
                        'content' => '<p>Konten kontak (ID) - silakan isi dari admin.</p>',
                    ],
                    'en' => [
                        'title' => 'Contact',
                        'slug' => 'kontak',
                        'content' => '<p>Contact content (EN) - fill from admin.</p>',
                    ],
                ],
            ],
        ];

        foreach ($pages as $p) {
            $page = Page::create([
                'parent_id' => null,
                'sort_order' => $p['sort_order'],
                'menu_group' => $p['menu_group'],
                'cover_image' => $p['cover_image'],
                'is_active' => $p['is_active'],
            ]);

            foreach ($p['translations'] as $locale => $t) {
                PageTranslation::create([
                    'page_id' => $page->id,
                    'locale' => $locale,
                    'title' => $t['title'],
                    'slug' => $t['slug'],
                    'content' => $t['content'],
                ]);
            }
        }
    }
}