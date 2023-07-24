<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Warehouse;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientProductController extends Controller
{
    //
    public function show(Request $request)
    {
        $listProduct = Warehouse::where('represent', 1)->paginate(2);
        $productCat = ProductCat::where('status', 'public')->get();
        $banners = Banner::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        return view('clients.product.listProduct', compact('listProduct', 'menu', 'banners'));
    }

    public function searchCat($slug, $id)
    {
        //Get list all ProductCat
        $listProductCat = ProductCat::all();
        $getNameCat = ProductCat::select('name')->where('id', $id)->first();
        //Get id data_tree ProductCat
        $list_cat_id = array_merge(array(0 => $id), data_tree_cat($listProductCat, $id));
        $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
            ->join('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
            ->select('warehouses.*', 'products.*', 'product_cats.name as category_name')
            ->whereIn('products.product_cat_id', $list_cat_id)
            ->where('represent', 1)
            ->paginate(2);
        $menu = render_menu($listProductCat, 0, 0);
        return view('clients.product.searchCatName', compact('listProduct', 'menu', 'getNameCat'));
    }

    public function detail($slug, $id)
    {
        $warehouse = Warehouse::where('product_id', $id)->where('represent', 1)->get();
        $product = Product::find($id);
        $sameItem = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
            ->select('warehouses.*', 'products.*')
            ->where('products.product_cat_id', $product->product_cat_id)->where('represent', 1)->get();
        // return dd($sameItem);
        $banners = Banner::where('status', 'public')->get();
        $images = $product->images;
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        $listImages = explode(',', str_replace(['[', ']', '"'], '', $images));
        $listColor = Warehouse::select('color_id')->distinct()->where('product_id', $id)->get();
        return view('clients.product.detailProduct', compact('warehouse', 'listImages', 'listColor', 'menu', 'banners', 'sameItem'));
    }

    public function arrangeAjax(Request $request)
    {
        $option = $request->input('option');
        switch ($option) {
            case "1":
                $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                    ->select('warehouses.*', 'products.name')
                    ->where('represent', 1)
                    ->orderBy('products.name')
                    ->paginate(2);
                $str = "";
                break;
            case "2":
                $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                    ->select('warehouses.*', 'products.name')
                    ->where('represent', 1)
                    ->orderBy('products.name', 'DESC')
                    ->paginate(2);
                $str = "";
                break;
            case "3":
                $query = Warehouse::where('represent', 1)->orderBy('price', 'DESC')->paginate(2);
                $str = "";
                break;
            case "4":
                $query = Warehouse::where('represent', 1)->orderBy('price', 'ASC')->paginate(2);
                $str = "";
                break;
            case "price-1":
                $query = Warehouse::where('represent', 1)->where('price', '<=', 500000)->where('price', '<=', 500000)->paginate(2);
                $str = "";
                break;
            case "price-2":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 500000)->where('price', '<=', 1000000)->paginate(2);
                $str = "";
                break;
            case "price-3":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 1000000)->where('price', '<=', 5000000)->paginate(2);
                $str = "";
                break;
            case "price-4":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 5000000)->where('price', '<=', 10000000)->paginate(2);
                $str = "";
                break;
            case "price-5":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 10000000)->where('price', '<=', 20000000)->paginate(2);
                $str = "";
                break;
            case "price-6":
                $query = Warehouse::where('represent', 1)->where('price', '>', 20000000)->paginate(2);
                $str = "";
                break;
        }
        foreach ($query as $item) {
            $images = asset($item->Product->thumbnail);
            $name = $item->Product->name;
            $id = $item->product_id;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $url = route('product.detail', ['slug' => $item->Product->slug, 'id' => $id]);
            $str .=
                "<li>
                    <a href='$url' title='' class='thumb'>
                        <img class='img_item_product' src='$images'>
                    </a>
                    <a href='$url' title='' class='product-name'>$name</a>
                    <div class='price'>
                        <span class='new'>$price</span>
                    </div>
                    <div class='action clearfix btn-detail pb-2'>
                        <a href='$url' title='' class='p-1 rounded text-center'>Xem chi tiết <i class='fa-solid fa-eye'></i></a>
                    </div>
                </li>";
        }
        $paging = $query->appends(['sort' => $option])->links()->render();
        $data = array(
            'paging' => $paging,
            'str' => $str
        );
        return json_encode($data, 200);
    }

    public function getData(Request $request)
    {
        $page = $request->input('page');
        $sort = $request->input('sort');
        $str = "";
        switch ($sort) {
            case "1":
                $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')->select('warehouses.*', 'products.name')
                    ->where('represent', 1)
                    ->orderBy('products.name')
                    ->paginate(2, ['*'], 'page', $page);
                break;
            case "2":
                $query = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')->select('warehouses.*', 'products.name')
                    ->where('represent', 1)
                    ->orderBy('products.name', 'DESC')
                    ->paginate(2, ['*'], 'page', $page);
                break;
            case "3":
                $query = Warehouse::where('represent', 1)->orderBy('price', 'DESC')->paginate(1, ['*'], 'page', $page);
                break;
            case "4":
                $query = Warehouse::where('represent', 1)->orderBy('price', 'ASC')->paginate(1, ['*'], 'page', $page);
                break;
            case "price-1":
                $query = Warehouse::where('represent', 1)->where('price', '<=', 500000)->where('price', '<=', 500000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            case "price-2":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 500000)->where('price', '<=', 1000000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            case "price-3":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 1000000)->where('price', '<=', 5000000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            case "price-4":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 5000000)->where('price', '<=', 10000000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            case "price-5":
                $query = Warehouse::where('represent', 1)->where('price', '>=', 10000000)->where('price', '<=', 20000000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            case "price-6":
                $query = Warehouse::where('represent', 1)->where('price', '>', 20000000)->paginate(2, ['*'], 'page', $page);
                $str = "";
                break;
            default:
                $query = Warehouse::where('represent', 1)->paginate(2, ['*'], 'page', $page);
        }
        foreach ($query as $item) {
            $images = asset($item->Product->thumbnail);
            $name = $item->Product->name;
            $id = $item->product_id;
            $price = number_format($item->price, 0, '', ',') . 'đ';
            $url = route('product.detail', ['slug' => $item->Product->slug, 'id' => $id]);
            $str .=
                "<li>
                        <a href='$url' title='' class='thumb'>
                            <img class='img_item_product' src='$images'>
                        </a>
                        <a href='$url' title='' class='product-name'>$name</a>
                        <div class='price'>
                            <span class='new'>$price</span>
                        </div>
                        <div class='action clearfix btn-detail pb-2'>
                            <a href='$url' title='' class='p-1 rounded text-center'>Xem chi tiết <i class='fa-solid fa-eye'></i></a>
                        </div>
                    </li>";
        }
        return json_encode($str, 200);
    }

    public function getDataColor(Request $request)
    {
        $str = "";
        $listConfig = Warehouse::where('color_id', $request->input('color'))->where('product_id', $request->input('id_product'))->get();
        foreach ($listConfig as $key => $item) {
            $active = '';
            if ($key == 0) {
                $active = 'active';
            }
            $url = route('getDataPrice');
            $name = $item->Config->name;
            $config = $item->config_id;
            $str .= "<a href='' data-url='$url' storage_capacity='$config' class='btn border btn-config $active'>$name</a>";
        };
        return json_encode($str, 200);
    }

    public function getDataPrice(Request $request)
    {
        $str = "";
        $prices = Warehouse::select('price', 'sale_off', 'discount')
            ->where('color_id', $request->input('color'))
            ->where('product_id', $request->input('id_product'))
            ->where('config_id', $request->input('config_id'))
            ->get();
        foreach ($prices as $item) {
            if ($item->discount > 0) {
                $price_new = number_format($item->sale_off, 0, '', ',') . 'đ';
                $price_old = number_format($item->price, 0, '', ',') . 'đ';
                $str .= "<span class='price new'>$price_new</span>
                <span class='price old pl-4'>$price_old</span>";
            } else {
                $price = number_format($item->price, 0, '', ',') . 'đ';
                $str .= "<span class='price new'>$price</span>";
            }
        };
        return json_encode($str, 200);
    }

    public function searchHeader(Request $request)
    {
        $key = $request->input('key');
        $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
            ->select('warehouses.*', 'products.name')
            ->where('products.name', 'LIKE', "%{$key}%")
            ->where('represent', 1)
            ->paginate(2);
        $productCat = ProductCat::where('status', 'public')->get();
        $menu = render_menu($productCat, 0, 0);
        return view('clients.product.searchHeader', compact('listProduct', 'menu'));
    }

    public function searchAjax(Request $request)
    {
        if ($request->ajax()) {
            $key = $request->input('key');
            $str = "";
            $listProduct = Warehouse::join('products', 'warehouses.product_id', '=', 'products.id')
                ->select('warehouses.*', 'products.name')
                ->where('products.name', 'LIKE', "%{$key}%")
                ->where('represent', 1)
                ->paginate(2);
            foreach ($listProduct as $item) {
                $images = asset($item->Product->thumbnail) ?? '';
                $url = route('product.detail', ['slug' => $item->Product->slug, 'id' => $item->product->id]);
                $name = $item->name ?? '';
                $str .= "<li>
                            <a href='$url'>
                                <img src='$images' alt='Ảnh sản phẩm'>
                                <p>$name</p>
                            </a>
                        </li>";
            }
        }
        return json_encode($str, 200);
    }
}
