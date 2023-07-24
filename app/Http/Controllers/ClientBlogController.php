<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Post;
use App\Models\ProductCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientBlogController extends Controller
{
    public function showBlogs()
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $listPost = Post::where('status', 'public')->paginate(10);
        return view('clients.blogs.listBlogs', compact('listPost', 'productASC', 'menu'));
    }

    public function detailBlogs($slug, $id)
    {
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $productASC = OrderDetail::select('product_id', 'warehouse_id', 'name', 'thumbnail', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'warehouse_id', 'name', 'thumbnail')
            ->orderBy(DB::raw('SUM(quantity)'), 'DESC')
            ->take(6)
            ->get();
        $infoPost = Post::find($id);
        return view('clients.blogs.detailBlog', compact('infoPost', 'productASC', 'menu'));
    }
}
