<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\News;
use App\Models\Menu;

class DashboardController extends Controller
{

    public function index()
    {

        $totalPages = Page::count();
        $totalNews = News::count();
        $totalMenus = Menu::count();

        return view('admin.dashboard',[
            'totalPages'=>$totalPages,
            'totalNews'=>$totalNews,
            'totalMenus'=>$totalMenus
        ]);

    }

}