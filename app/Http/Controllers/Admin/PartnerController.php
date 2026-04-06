<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $category = trim((string) $request->query('category'));

        $partners = Partner::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderByRaw("CASE 
                WHEN category = 'customer' THEN 1
                WHEN category = 'business_partner' THEN 2
                ELSE 3
            END")
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        $categories = Partner::categoryOptions();

        return view('admin.partners.index', compact('partners', 'q', 'category', 'categories'));
    }

    public function create()
    {
        $categories = Partner::categoryOptions();

        return view('admin.partners.create', compact('categories'));
    }

    public function store(Request $request, PublicImageUploader $uploader)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'category' => ['required', 'string', Rule::in(array_keys(Partner::categoryOptions()))],
            'website_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $uploader->upload(
                $request->file('logo'),
                'images/partners',
                2
            );
        }

        $nextSortOrder = ((int) Partner::query()
            ->where('category', $data['category'])
            ->max('sort_order')) + 1;

        Partner::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'website_url' => $data['website_url'] ?? null,
            'sort_order' => $nextSortOrder,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'logo_path' => $logoPath,
        ]);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        $categories = Partner::categoryOptions();

        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    public function update(Request $request, Partner $partner, PublicImageUploader $uploader)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'category' => ['required', 'string', Rule::in(array_keys(Partner::categoryOptions()))],
            'website_url' => ['nullable', 'url', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
        ]);

        $oldCategory = $partner->category;

        $payload = [
            'name' => $data['name'],
            'category' => $data['category'],
            'website_url' => $data['website_url'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];

        if ($oldCategory !== $data['category']) {
            $payload['sort_order'] = ((int) Partner::query()
                ->where('category', $data['category'])
                ->max('sort_order')) + 1;
        }

        if ($request->hasFile('logo')) {
            $uploader->delete($partner->logo_path);

            $payload['logo_path'] = $uploader->upload(
                $request->file('logo'),
                'images/partners',
                2
            );
        }

        $partner->update($payload);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Partner $partner, PublicImageUploader $uploader)
    {
        $uploader->delete($partner->logo_path);

        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}