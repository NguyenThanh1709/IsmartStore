<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\OrderDetail;
use App\Models\Page;
use App\Models\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientPageController extends Controller
{
    //
    public function showIntroduce()
    {
        // $banners = Banner::where('status', 'public')->get();
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $pageIntroduce = Page::where('name', 'Giới thiệu')->where('status', 'public')->get();
        return view('clients.page.introduce', compact('pageIntroduce', 'productASC', 'menu'));
    }

    public function showContact()
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $banners = Banner::where('status', 'public')->get();
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $contacts = Page::where('name', 'Liên hệ')->where('status', 'public')->get();
        return view('clients.page.contact', compact('contacts', 'productASC', 'banners','menu'));
    }
}
