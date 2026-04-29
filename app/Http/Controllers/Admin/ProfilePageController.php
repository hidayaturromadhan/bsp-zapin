<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProfilePageController extends Controller
{
    public function index()
    {
        $pages = Page::query()
            ->where('menu_group', 'profil')
            ->with(['translations' => function ($q) {
                $q->whereIn('locale', ['id', 'en']);
            }])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.profile_pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        abort_if($page->menu_group !== 'profil', 404);

        $translations = $page->translations()
            ->whereIn('locale', ['id', 'en'])
            ->get()
            ->keyBy('locale');

        $tId = $translations->get('id') ?? new PageTranslation(['locale' => 'id']);
        $tEn = $translations->get('en') ?? new PageTranslation(['locale' => 'en']);

        $templateType = $this->detectTemplateType($tId->slug);

        $aboutData = $this->decodeAboutContent($tId->content);
        $visionMissionData = $this->decodeVisionMissionContent($tId->content);
        $historyData = $this->decodeHistoryContent($tId->content);
        $shareholderData = $this->decodeShareholderContent($tId->content);
        $organizationData = $this->decodeOrganizationContent($tId->content);
        $hseData = $this->decodeHseContent($tId->content);

        return view('admin.profile_pages.edit', compact(
            'page',
            'tId',
            'tEn',
            'templateType',
            'aboutData',
            'visionMissionData',
            'historyData',
            'shareholderData',
            'organizationData',
            'hseData'
        ));
    }

    public function update(Request $request, Page $page, PublicImageUploader $uploader)
    {
        abort_if($page->menu_group !== 'profil', 404);

        $currentIdTranslation = $page->translations()->where('locale', 'id')->first();
        $idSlug = $request->input('id_slug', $currentIdTranslation?->slug);
        $templateType = $this->detectTemplateType($idSlug);

        $rules = [
            'is_active' => ['nullable', 'boolean'],
            'id_title' => ['required', 'string', 'max:190'],
            'id_slug' => ['required', 'string', 'max:190'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];

        if ($templateType === 'about_us') {
            $rules = array_merge($rules, [
                'hero_text' => ['nullable', 'string'],
                'section_1_title' => ['nullable', 'string', 'max:190'],
                'section_1_text' => ['nullable', 'string'],
                'section_1_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'section_2_title' => ['nullable', 'string', 'max:190'],
                'section_2_text' => ['nullable', 'string'],
                'section_2_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        } elseif ($templateType === 'vision_mission') {
            $rules = array_merge($rules, [
                'vision_title' => ['required', 'string', 'max:190'],
                'vision_text' => ['required', 'string'],
                'mission_title' => ['required', 'string', 'max:190'],
                'mission_items' => ['required', 'array', 'min:1'],
                'mission_items.*' => ['nullable', 'string'],
            ]);
        } elseif ($templateType === 'history') {
            $rules = array_merge($rules, [
                'history_intro_title' => ['required', 'string', 'max:190'],
                'history_intro_desc' => ['nullable', 'string', 'max:500'],

                'history_section_1_title' => ['required', 'string', 'max:190'],
                'history_section_1_content' => ['required', 'string'],

                'history_section_2_title' => ['required', 'string', 'max:190'],
                'history_section_2_content' => ['required', 'string'],

                'history_event_1_label' => ['required', 'string', 'max:190'],
                'history_event_1_date' => ['required', 'string', 'max:100'],
                'history_event_1_title' => ['required', 'string', 'max:190'],
                'history_event_1_content' => ['required', 'string'],

                'history_event_2_label' => ['required', 'string', 'max:190'],
                'history_event_2_date' => ['required', 'string', 'max:100'],
                'history_event_2_title' => ['required', 'string', 'max:190'],
                'history_event_2_content' => ['required', 'string'],

                'history_event_3_label' => ['required', 'string', 'max:190'],
                'history_event_3_date' => ['required', 'string', 'max:100'],
                'history_event_3_title' => ['required', 'string', 'max:190'],
                'history_event_3_content' => ['required', 'string'],
            ]);
        } elseif ($templateType === 'shareholder') {
            $rules = array_merge($rules, [
                'shareholder_intro_title' => ['required', 'string', 'max:190'],
                'shareholder_intro_desc' => ['nullable', 'string', 'max:500'],
                'shareholder_chart_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

                'shareholder_1_percentage' => ['required', 'string', 'max:50'],
                'shareholder_1_name' => ['required', 'string', 'max:190'],
                'shareholder_1_desc' => ['nullable', 'string', 'max:255'],
                'shareholder_1_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

                'shareholder_2_percentage' => ['required', 'string', 'max:50'],
                'shareholder_2_name' => ['required', 'string', 'max:190'],
                'shareholder_2_desc' => ['nullable', 'string', 'max:255'],
                'shareholder_2_logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        } elseif ($templateType === 'organization_structure') {
            $rules = array_merge($rules, [
                'organization_intro_title' => ['required', 'string', 'max:190'],
                'organization_intro_desc' => ['nullable', 'string', 'max:500'],

                'director_name' => ['nullable', 'string', 'max:190'],
                'director_position' => ['nullable', 'string', 'max:190'],
                'director_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

                'commissioner_name' => ['nullable', 'string', 'max:190'],
                'commissioner_position' => ['nullable', 'string', 'max:190'],
                'commissioner_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ]);
        } elseif ($templateType === 'hse') {
            $rules = array_merge($rules, [
                'hse_intro_title' => ['required', 'string', 'max:190'],
                'hse_intro_desc' => ['nullable', 'string', 'max:500'],
                'hse_policy_title' => ['required', 'string', 'max:190'],
                'hse_policy_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:6144'],

                'hse_certification_title' => ['required', 'string', 'max:190'],
                'hse_certification_subtitle' => ['nullable', 'string', 'max:255'],

                'hse_cert_1_code' => ['required', 'string', 'max:100'],
                'hse_cert_1_title' => ['required', 'string', 'max:190'],

                'hse_cert_2_code' => ['required', 'string', 'max:100'],
                'hse_cert_2_title' => ['required', 'string', 'max:190'],

                'hse_cert_3_code' => ['required', 'string', 'max:100'],
                'hse_cert_3_title' => ['required', 'string', 'max:190'],
            ]);
        } else {
            $rules['id_content'] = ['nullable', 'string'];
        }

        $data = $request->validate($rules);

        $this->validateUniqueSlug('id', $data['id_slug'], $page->id);

        $enTitle = $this->translateTitle($data['id_title'], $data['id_slug']);
        $enSlug = $this->buildEnglishSlug($data['id_slug'], $enTitle);

        $this->validateUniqueSlug('en', $enSlug, $page->id);

        DB::transaction(function () use ($request, $page, $data, $uploader, $templateType, $enTitle, $enSlug) {
            $pagePayload = [
                'is_active' => (bool) ($data['is_active'] ?? false),
            ];

            if ($request->hasFile('cover_image')) {
                if ($page->cover_image) {
                    $uploader->delete($page->cover_image);
                }

                $pagePayload['cover_image'] = $uploader->upload(
                    $request->file('cover_image'),
                    'images/pages',
                    2
                );
            }

            $page->update($pagePayload);

            if ($templateType === 'about_us') {
                $existingIdTranslation = $page->translations()->where('locale', 'id')->first();
                $oldContent = $this->decodeAboutContent($existingIdTranslation?->content);

                $section1Image = $oldContent['section_1_image'] ?? null;
                $section2Image = $oldContent['section_2_image'] ?? null;

                if ($request->hasFile('section_1_image')) {
                    if ($section1Image) {
                        $uploader->delete($section1Image);
                    }

                    $section1Image = $uploader->upload(
                        $request->file('section_1_image'),
                        'images/pages/profile',
                        2
                    );
                }

                if ($request->hasFile('section_2_image')) {
                    if ($section2Image) {
                        $uploader->delete($section2Image);
                    }

                    $section2Image = $uploader->upload(
                        $request->file('section_2_image'),
                        'images/pages/profile',
                        2
                    );
                }

                $idContent = [
                    'template' => 'about_us',
                    'hero_text' => trim((string) $request->input('hero_text')),
                    'section_1_title' => trim((string) $request->input('section_1_title')),
                    'section_1_text' => trim((string) $request->input('section_1_text')),
                    'section_1_image' => $section1Image,
                    'section_2_title' => trim((string) $request->input('section_2_title')),
                    'section_2_text' => trim((string) $request->input('section_2_text')),
                    'section_2_image' => $section2Image,
                ];

                $enContent = [
                    'template' => 'about_us',
                    'hero_text' => $this->translateText($idContent['hero_text']),
                    'section_1_title' => $this->translateText($idContent['section_1_title']),
                    'section_1_text' => $this->translateText($idContent['section_1_text']),
                    'section_1_image' => $section1Image,
                    'section_2_title' => $this->translateText($idContent['section_2_title']),
                    'section_2_text' => $this->translateText($idContent['section_2_text']),
                    'section_2_image' => $section2Image,
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } elseif ($templateType === 'vision_mission') {
                $missionItems = collect($request->input('mission_items', []))
                    ->map(fn ($item) => trim((string) $item))
                    ->filter()
                    ->values()
                    ->all();

                $idContent = [
                    'template' => 'vision_mission',
                    'vision_title' => trim((string) $request->input('vision_title')),
                    'vision_text' => trim((string) $request->input('vision_text')),
                    'mission_title' => trim((string) $request->input('mission_title')),
                    'mission_items' => $missionItems,
                ];

                $enContent = [
                    'template' => 'vision_mission',
                    'vision_title' => $this->translateText($idContent['vision_title']),
                    'vision_text' => $this->translateText($idContent['vision_text']),
                    'mission_title' => $this->translateText($idContent['mission_title']),
                    'mission_items' => collect($missionItems)->map(fn ($item) => $this->translateText($item))->values()->all(),
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } elseif ($templateType === 'history') {
                $idContent = [
                    'template' => 'history',
                    'intro' => [
                        'title' => trim((string) $request->input('history_intro_title')),
                        'desc' => trim((string) $request->input('history_intro_desc')),
                    ],
                    'sections' => [
                        [
                            'title' => trim((string) $request->input('history_section_1_title')),
                            'content' => trim((string) $request->input('history_section_1_content')),
                        ],
                        [
                            'title' => trim((string) $request->input('history_section_2_title')),
                            'content' => trim((string) $request->input('history_section_2_content')),
                        ],
                    ],
                    'timeline' => [
                        [
                            'label' => trim((string) $request->input('history_event_1_label')),
                            'date' => trim((string) $request->input('history_event_1_date')),
                            'title' => trim((string) $request->input('history_event_1_title')),
                            'content' => trim((string) $request->input('history_event_1_content')),
                        ],
                        [
                            'label' => trim((string) $request->input('history_event_2_label')),
                            'date' => trim((string) $request->input('history_event_2_date')),
                            'title' => trim((string) $request->input('history_event_2_title')),
                            'content' => trim((string) $request->input('history_event_2_content')),
                        ],
                        [
                            'label' => trim((string) $request->input('history_event_3_label')),
                            'date' => trim((string) $request->input('history_event_3_date')),
                            'title' => trim((string) $request->input('history_event_3_title')),
                            'content' => trim((string) $request->input('history_event_3_content')),
                        ],
                    ],
                ];

                $enContent = [
                    'template' => 'history',
                    'intro' => [
                        'title' => $this->translateText($idContent['intro']['title']),
                        'desc' => $this->translateText($idContent['intro']['desc']),
                    ],
                    'sections' => collect($idContent['sections'])->map(function ($section) {
                        return [
                            'title' => $this->translateText($section['title'] ?? ''),
                            'content' => $this->translateText($section['content'] ?? ''),
                        ];
                    })->values()->all(),
                    'timeline' => collect($idContent['timeline'])->map(function ($item) {
                        return [
                            'label' => $this->translateText($item['label'] ?? ''),
                            'date' => $item['date'] ?? '',
                            'title' => $this->translateText($item['title'] ?? ''),
                            'content' => $this->translateText($item['content'] ?? ''),
                        ];
                    })->values()->all(),
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } elseif ($templateType === 'shareholder') {
                $existingIdTranslation = $page->translations()->where('locale', 'id')->first();
                $oldContent = $this->decodeShareholderContent($existingIdTranslation?->content);

                $chartImage = $oldContent['chart_image'] ?? null;
                if ($request->hasFile('shareholder_chart_image')) {
                    if ($chartImage) {
                        $uploader->delete($chartImage);
                    }

                    $chartImage = $uploader->upload(
                        $request->file('shareholder_chart_image'),
                        'images/pages/profile',
                        2
                    );
                }

                $items = [];
                for ($i = 1; $i <= 2; $i++) {
                    $oldLogo = $oldContent['items'][$i - 1]['logo'] ?? null;
                    $logo = $oldLogo;

                    if ($request->hasFile("shareholder_{$i}_logo")) {
                        if ($oldLogo) {
                            $uploader->delete($oldLogo);
                        }

                        $logo = $uploader->upload(
                            $request->file("shareholder_{$i}_logo"),
                            'images/pages/profile',
                            2
                        );
                    }

                    $items[] = [
                        'percentage' => trim((string) $request->input("shareholder_{$i}_percentage")),
                        'name' => trim((string) $request->input("shareholder_{$i}_name")),
                        'desc' => trim((string) $request->input("shareholder_{$i}_desc")),
                        'logo' => $logo,
                    ];
                }

                $idContent = [
                    'template' => 'shareholder',
                    'intro' => [
                        'title' => trim((string) $request->input('shareholder_intro_title')),
                        'desc' => trim((string) $request->input('shareholder_intro_desc')),
                    ],
                    'chart_image' => $chartImage,
                    'items' => $items,
                ];

                $enContent = [
                    'template' => 'shareholder',
                    'intro' => [
                        'title' => $this->translateText($idContent['intro']['title']),
                        'desc' => $this->translateText($idContent['intro']['desc']),
                    ],
                    'chart_image' => $chartImage,
                    'items' => collect($items)->map(function ($item) {
                        return [
                            'percentage' => $item['percentage'] ?? '',
                            'name' => $this->translateText($item['name'] ?? ''),
                            'desc' => $this->translateText($item['desc'] ?? ''),
                            'logo' => $item['logo'] ?? null,
                        ];
                    })->values()->all(),
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } elseif ($templateType === 'organization_structure') {
                $existingIdTranslation = $page->translations()->where('locale', 'id')->first();
                $oldContent = $this->decodeOrganizationContent($existingIdTranslation?->content);

                $directorPhoto = $oldContent['director']['photo'] ?? null;
                if ($request->hasFile('director_photo')) {
                    if ($directorPhoto) {
                        $uploader->delete($directorPhoto);
                    }

                    $directorPhoto = $uploader->upload(
                        $request->file('director_photo'),
                        'images/pages/profile',
                        2
                    );
                }

                $commissionerPhoto = $oldContent['commissioner']['photo'] ?? null;
                if ($request->hasFile('commissioner_photo')) {
                    if ($commissionerPhoto) {
                        $uploader->delete($commissionerPhoto);
                    }

                    $commissionerPhoto = $uploader->upload(
                        $request->file('commissioner_photo'),
                        'images/pages/profile',
                        2
                    );
                }

                $idContent = [
                    'template' => 'organization_structure',
                    'intro' => [
                        'title' => trim((string) $request->input('organization_intro_title')),
                        'desc' => trim((string) $request->input('organization_intro_desc')),
                    ],
                    'director' => [
                        'name' => trim((string) $request->input('director_name')),
                        'position' => trim((string) $request->input('director_position')),
                        'photo' => $directorPhoto,
                    ],
                    'commissioner' => [
                        'name' => trim((string) $request->input('commissioner_name')),
                        'position' => trim((string) $request->input('commissioner_position')),
                        'photo' => $commissionerPhoto,
                    ],
                ];

                $enContent = [
                    'template' => 'organization_structure',
                    'intro' => [
                        'title' => $this->translateText($idContent['intro']['title']),
                        'desc' => $this->translateText($idContent['intro']['desc']),
                    ],
                    'director' => [
                        'name' => $this->translateText($idContent['director']['name'] ?? ''),
                        'position' => $this->translateText($idContent['director']['position'] ?? ''),
                        'photo' => $directorPhoto,
                    ],
                    'commissioner' => [
                        'name' => $this->translateText($idContent['commissioner']['name'] ?? ''),
                        'position' => $this->translateText($idContent['commissioner']['position'] ?? ''),
                        'photo' => $commissionerPhoto,
                    ],
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } elseif ($templateType === 'hse') {
                $existingIdTranslation = $page->translations()->where('locale', 'id')->first();
                $oldContent = $this->decodeHseContent($existingIdTranslation?->content);

                $policyImage = $oldContent['policy_image'] ?? null;
                if ($request->hasFile('hse_policy_image')) {
                    if ($policyImage) {
                        $uploader->delete($policyImage);
                    }

                    $policyImage = $uploader->upload(
                        $request->file('hse_policy_image'),
                        'images/pages/profile',
                        2
                    );
                }

                $certificates = [
                    [
                        'code' => trim((string) $request->input('hse_cert_1_code')),
                        'title' => trim((string) $request->input('hse_cert_1_title')),
                    ],
                    [
                        'code' => trim((string) $request->input('hse_cert_2_code')),
                        'title' => trim((string) $request->input('hse_cert_2_title')),
                    ],
                    [
                        'code' => trim((string) $request->input('hse_cert_3_code')),
                        'title' => trim((string) $request->input('hse_cert_3_title')),
                    ],
                ];

                $idContent = [
                    'template' => 'hse',
                    'intro' => [
                        'title' => trim((string) $request->input('hse_intro_title')),
                        'desc' => trim((string) $request->input('hse_intro_desc')),
                    ],
                    'policy_title' => trim((string) $request->input('hse_policy_title')),
                    'policy_image' => $policyImage,
                    'certification' => [
                        'title' => trim((string) $request->input('hse_certification_title')),
                        'subtitle' => trim((string) $request->input('hse_certification_subtitle')),
                        'items' => $certificates,
                    ],
                ];

                $enContent = [
                    'template' => 'hse',
                    'intro' => [
                        'title' => $this->translateText($idContent['intro']['title']),
                        'desc' => $this->translateText($idContent['intro']['desc']),
                    ],
                    'policy_title' => $this->translateText($idContent['policy_title']),
                    'policy_image' => $policyImage,
                    'certification' => [
                        'title' => $this->translateText($idContent['certification']['title']),
                        'subtitle' => $this->translateText($idContent['certification']['subtitle']),
                        'items' => collect($certificates)->map(function ($item) {
                            return [
                                'code' => $item['code'] ?? '',
                                'title' => $this->translateText($item['title'] ?? ''),
                            ];
                        })->values()->all(),
                    ],
                ];

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            } else {
                $idContent = trim((string) $request->input('id_content'));
                $enContent = $this->translateText($idContent);

                $this->saveProfileTranslations(
                    $page->id,
                    trim((string) $request->input('id_title')),
                    trim((string) $request->input('id_slug')),
                    $idContent,
                    $enTitle,
                    $enSlug,
                    $enContent
                );
            }
        });

        return redirect()
            ->route('admin.profile-pages.edit', $page->id)
            ->with('success', 'Halaman profil berhasil diperbarui.');
    }

    private function saveProfileTranslations(
        int $pageId,
        string $idTitle,
        string $idSlug,
        $idContent,
        string $enTitle,
        string $enSlug,
        $enContent
    ): void {
        PageTranslation::updateOrCreate(
            ['page_id' => $pageId, 'locale' => 'id'],
            [
                'title' => $idTitle,
                'slug' => $idSlug,
                'content' => is_array($idContent)
                    ? json_encode($idContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    : $idContent,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $pageId, 'locale' => 'en'],
            [
                'title' => $enTitle,
                'slug' => $enSlug,
                'content' => is_array($enContent)
                    ? json_encode($enContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    : $enContent,
            ]
        );
    }

    private function detectTemplateType(?string $slug): string
    {
        $slug = trim((string) $slug);

        return match ($slug) {
            'tentang-kami', 'about-us' => 'about_us',
            'visi-misi', 'vision-mission' => 'vision_mission',
            'sejarah', 'history' => 'history',
            'pemegang-saham', 'shareholders' => 'shareholder',
            'struktur-organisasi', 'organization-structure' => 'organization_structure',
            'health-safety-environment', 'hse' => 'hse',
            default => 'generic',
        };
    }

    private function decodeAboutContent(?string $content): array
    {
        $empty = [
            'template' => 'about_us',
            'hero_text' => '',
            'section_1_title' => '',
            'section_1_text' => '',
            'section_1_image' => null,
            'section_2_title' => '',
            'section_2_text' => '',
            'section_2_image' => null,
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'about_us') {
            return $empty;
        }

        return array_merge($empty, $decoded);
    }

    private function decodeVisionMissionContent(?string $content): array
    {
        $empty = [
            'template' => 'vision_mission',
            'vision_title' => 'VISI',
            'vision_text' => '',
            'mission_title' => 'MISI',
            'mission_items' => ['', '', '', '', '', ''],
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'vision_mission') {
            return $empty;
        }

        return array_merge($empty, $decoded);
    }

    private function decodeHistoryContent(?string $content): array
    {
        $empty = [
            'template' => 'history',
            'intro' => [
                'title' => 'Sejarah',
                'desc' => '',
            ],
            'sections' => [
                ['title' => '', 'content' => ''],
                ['title' => '', 'content' => ''],
            ],
            'timeline' => [
                ['label' => '', 'date' => '', 'title' => '', 'content' => ''],
                ['label' => '', 'date' => '', 'title' => '', 'content' => ''],
                ['label' => '', 'date' => '', 'title' => '', 'content' => ''],
            ],
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'history') {
            return $empty;
        }

        $decoded['intro'] = array_merge($empty['intro'], $decoded['intro'] ?? []);
        $decoded['sections'] = array_values($decoded['sections'] ?? []);
        $decoded['timeline'] = array_values($decoded['timeline'] ?? []);

        for ($i = count($decoded['sections']); $i < 2; $i++) {
            $decoded['sections'][] = ['title' => '', 'content' => ''];
        }

        for ($i = count($decoded['timeline']); $i < 3; $i++) {
            $decoded['timeline'][] = ['label' => '', 'date' => '', 'title' => '', 'content' => ''];
        }

        return $decoded;
    }

    private function decodeShareholderContent(?string $content): array
    {
        $empty = [
            'template' => 'shareholder',
            'intro' => [
                'title' => 'Pemegang Saham',
                'desc' => '',
            ],
            'chart_image' => null,
            'items' => [
                [
                    'percentage' => '',
                    'name' => '',
                    'desc' => '',
                    'logo' => null,
                ],
                [
                    'percentage' => '',
                    'name' => '',
                    'desc' => '',
                    'logo' => null,
                ],
            ],
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'shareholder') {
            return $empty;
        }

        $decoded['intro'] = array_merge($empty['intro'], $decoded['intro'] ?? []);
        $decoded['items'] = array_values($decoded['items'] ?? []);

        for ($i = count($decoded['items']); $i < 2; $i++) {
            $decoded['items'][] = [
                'percentage' => '',
                'name' => '',
                'desc' => '',
                'logo' => null,
            ];
        }

        return $decoded;
    }

    private function decodeOrganizationContent(?string $content): array
    {
        $empty = [
            'template' => 'organization_structure',
            'intro' => [
                'title' => 'Struktur Organisasi',
                'desc' => '',
            ],
            'director' => [
                'name' => '',
                'position' => 'Direktur Utama',
                'photo' => null,
            ],
            'commissioner' => [
                'name' => '',
                'position' => 'Komisaris Utama',
                'photo' => null,
            ],
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'organization_structure') {
            return $empty;
        }

        $decoded['intro'] = array_merge($empty['intro'], $decoded['intro'] ?? []);
        $decoded['director'] = array_merge($empty['director'], $decoded['director'] ?? []);
        $decoded['commissioner'] = array_merge($empty['commissioner'], $decoded['commissioner'] ?? []);

        return $decoded;
    }


    private function decodeHseContent(?string $content): array
    {
        $empty = [
            'template' => 'hse',
            'intro' => [
                'title' => 'Health, Safety & Environment',
                'desc' => '',
            ],
            'policy_title' => 'Kebijakan K3LL',
            'policy_image' => null,
            'certification' => [
                'title' => 'Bersertifikat Sistem Manajemen Terintegrasi',
                'subtitle' => 'Ruang Lingkup : Penyediaan Jasa Transportasi Minyak & Gas',
                'items' => [
                    [
                        'code' => 'ISO 9001:2015',
                        'title' => 'Quality Management System',
                    ],
                    [
                        'code' => 'ISO 14001:2015',
                        'title' => 'Environmental Management System',
                    ],
                    [
                        'code' => 'ISO 45001:2018',
                        'title' => 'Occupational Health & Safety Management System',
                    ],
                ],
            ],
        ];

        if (! $content) {
            return $empty;
        }

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ($decoded['template'] ?? null) !== 'hse') {
            return $empty;
        }

        $decoded['intro'] = array_merge($empty['intro'], $decoded['intro'] ?? []);
        $decoded['certification'] = array_merge($empty['certification'], $decoded['certification'] ?? []);
        $decoded['certification']['items'] = array_values($decoded['certification']['items'] ?? []);

        for ($i = count($decoded['certification']['items']); $i < 3; $i++) {
            $decoded['certification']['items'][] = [
                'code' => $empty['certification']['items'][$i]['code'] ?? '',
                'title' => $empty['certification']['items'][$i]['title'] ?? '',
            ];
        }

        $decoded['policy_title'] = $decoded['policy_title'] ?? $empty['policy_title'];
        $decoded['policy_image'] = $decoded['policy_image'] ?? null;

        return $decoded;
    }

    private function translateTitle(string $title, string $slug): string
    {
        return match ($slug) {
            'tentang-kami' => 'About Us',
            'visi-misi' => 'Vision & Mission',
            'sejarah' => 'History',
            'pemegang-saham' => 'Shareholders',
            'struktur-organisasi' => 'Organization Structure',
            'health-safety-environment' => 'Health, Safety & Environment',
            'hse' => 'Health, Safety & Environment',
            default => $title,
        };
    }

    private function buildEnglishSlug(string $idSlug, string $enTitle): string
    {
        return match ($idSlug) {
            'tentang-kami' => 'about-us',
            'visi-misi' => 'vision-mission',
            'sejarah' => 'history',
            'pemegang-saham' => 'shareholders',
            'struktur-organisasi' => 'organization-structure',
            'health-safety-environment' => 'health-safety-environment',
            'hse' => 'health-safety-environment',
            default => Str::slug($enTitle) ?: Str::slug($idSlug . '-en'),
        };
    }

    private function translateText(?string $text): string
    {
        return trim((string) $text);
    }

    private function validateUniqueSlug(string $locale, string $slug, int $pageId): void
    {
        $exists = PageTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('page_id', '!=', $pageId)
            ->exists();

        if ($exists) {
            abort(422, "Slug ({$slug}) sudah dipakai untuk locale {$locale}.");
        }
    }
}
