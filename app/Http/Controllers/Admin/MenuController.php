<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Models\News;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function index()
    {
        $menus = Menu::with('parent')
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.index', compact('menus'));
    }


    public function create()
    {
        $parents = Menu::orderBy('sort_order')->get();

        $pages = Page::with('translations')->get();
        $news = News::with('translations')->get();

        return view('admin.menus.create', compact(
            'parents',
            'pages',
            'news'
        ));
    }


    public function store(Request $request)
    {

        $data = $request->validate([
            'label_id' => ['required','string','max:190'],
            'label_en' => ['nullable','string','max:190'],
            'type' => ['required','in:page,news,url'],
            'parent_id' => ['nullable','exists:menus,id'],
            'page_id' => ['nullable','exists:pages,id'],
            'news_id' => ['nullable','exists:news,id'],
            'url' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable','boolean']
        ]);


        Menu::create([

            'label_id'=>$data['label_id'],
            'label_en'=>$data['label_en'] ?? null,

            'type'=>$data['type'],

            'parent_id'=>$data['parent_id'] ?? null,
            'page_id'=>$data['page_id'] ?? null,
            'news_id'=>$data['news_id'] ?? null,
            'url'=>$data['url'] ?? null,

            'sort_order'=>$data['sort_order'] ?? 0,
            'is_active'=>(bool)($data['is_active'] ?? false),

        ]);


        return redirect()
            ->route('admin.menus.index')
            ->with('success','Menu berhasil dibuat');
    }



    public function edit(Menu $menu)
    {

        $parents = Menu::where('id','!=',$menu->id)
            ->orderBy('sort_order')
            ->get();

        $pages = Page::with('translations')->get();
        $news = News::with('translations')->get();


        return view('admin.menus.edit', compact(
            'menu',
            'parents',
            'pages',
            'news'
        ));
    }



    public function update(Request $request, Menu $menu)
    {

        $data = $request->validate([
            'label_id' => ['required','string','max:190'],
            'label_en' => ['nullable','string','max:190'],
            'type' => ['required','in:page,news,url'],
            'parent_id' => ['nullable','exists:menus,id'],
            'page_id' => ['nullable','exists:pages,id'],
            'news_id' => ['nullable','exists:news,id'],
            'url' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable','boolean']
        ]);


        $menu->update([

            'label_id'=>$data['label_id'],
            'label_en'=>$data['label_en'] ?? null,

            'type'=>$data['type'],

            'parent_id'=>$data['parent_id'] ?? null,
            'page_id'=>$data['page_id'] ?? null,
            'news_id'=>$data['news_id'] ?? null,
            'url'=>$data['url'] ?? null,

            'sort_order'=>$data['sort_order'] ?? 0,
            'is_active'=>(bool)($data['is_active'] ?? false),

        ]);


        return redirect()
            ->route('admin.menus.index')
            ->with('success','Menu berhasil diupdate');
    }



    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('admin.menus.index')
            ->with('success','Menu berhasil dihapus');
    }

}