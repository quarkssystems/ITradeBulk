<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SupplierItemInventory;
use App\Models\Brand;
use App\Models\History\RequestQuoteHistory;
use App\Models\Product;
use App\Models\ReviewRating;
use App\Models\UserCompany;
use App\User;
use Illuminate\Http\Request;
use DB;

class FrontCatalogController extends Controller
{
    public function productList(Request $request, Product $productModel, Category $categoryModel, Brand $brandModel, SupplierItemInventory $supplierItemInventoryModel, User $userModel, $is_search = 0)
    {
        //DB::enableQueryLog(); // Enable query log
        $products = $productModel->whereHas('supplierStock', function ($q) {
            $q->where('single', '>', 0);
            $q->where('single_price', '>', 0);
        });

        if ($request->has("name") && !empty($request->get("name"))) {
            $is_search = 1;
            $products = $products->where("name", "LIKE", "%{$request->get('name')}%");
        }

        if ($request->has("category") && !empty($request->get("category"))) {
            $is_search = 1;
            $products = $products->whereHas('productCategory.category', function ($q) use ($request) {
                $q->where('slug', '=', $request->get('category'));
            });
        }

        if ($request->has("brand") && !empty($request->get("brand"))) {
            $is_search = 1;
            $products = $products->whereHas('brand', function ($q) use ($request) {
                $q->where('slug', '=', $request->get('brand'));
            });
        }
        if ($request->has("supplier") && !empty($request->get("supplier"))) {
            $is_search = 1;
            $products = $productModel->whereHas('supplierStock', function ($q) use ($request) {
                $q->where('user_id', '=', $request->get("supplier"));
            });
        }
        $products = $products->where("published", "1");
        // $products = $products->where("status", "ACTIVE");

        if ($is_search == 0) {
            // $products = $products->where("default_stock_type", "1");
        }

        $products = $products->get();

        $mainProducts = [];

        foreach ($products as $pkey => $product) {

            $mainProducts[$pkey] = $product;
            $mainProducts[$pkey]['child'] = $productModel->where('parent_id', $product->parent_id)->get();
            // new added

            // if ($product->supplierStock['stock_expiry_date'] == 'NA' || $product->supplierStock['stock_expiry_date'] == null) {
            //     $stock_expiry_date = '';
            // } else {
            $stock_expiry_date = $product->supplierStock['stock_expiry_date'];
            // }
            $mainProducts[$pkey]['stock_expiry_date'] = $stock_expiry_date;
        }

        // dd($mainProducts);


        //dd(DB::getQueryLog()); // Show results of log
        $Categoriesall  = $categoryModel->getParentCategories();
        $parentCategories = $categoryModel->getParentCategoriesLimit();
        //dd($parentCategories);

        $subCategories = $categoryModel->whereNotNull('parent_category_id')->has("categoryProduct.product")->orderBy('name')->get();

        $brands = $brandModel->where('status', 'ACTIVE')->orderBy('name')->take(10)->get();

        $suppliers = User::whereNull('deleted_at')->where([['status', 'Active'], ['role', 'SUPPLIER']])->with('company')->get();

        $pageTitle = "Products";
        $bodyClass = ['about-us'];

        $groupedCategories = $categoryModel->getGroupedCategories();

        return view('frontend.catalog.product.list', compact('pageTitle', 'bodyClass', 'products', 'parentCategories', 'subCategories', 'brands', 'Categoriesall', 'groupedCategories', 'suppliers', 'mainProducts'));
    }

    public function productdetail($product_id, Request $request, Product $productModel, Category $categoryModel)
    {
        $product_detail = $productModel->where('slug', $product_id)->first();

        // $childProducts = $productModel->where('parent_id',$product_detail->parent_id)->pluck('slug','stock_type');
        $childProducts = $productModel->where('parent_id', $product_detail->parent_id)->get();
        // dd($childProducts);

        $categories = $categoryModel->getParentCategories();

        $product_detail2 = Product::with('brand')->where('slug', $product_id)->first();
        // dd($product_detail2);

        $selectedCategories = $product_detail2->productCategory()->pluck('category_id')->toArray();
        // dd($selectedCategories);

        $html = '';
        foreach ($selectedCategories as $selectedCategorie) {
            $cats[] = Category::select('name', 'slug')->where('uuid', $selectedCategorie)->first();
        }
        if (count($cats)) {
            $cats = array_values(array_unique($cats));
        }

        $pageTitle = "Products Detail";
        $bodyClass = ['about-us'];
        $user_uuid = \Auth::user()->uuid ?? '';
        $reviewRatings = ReviewRating::with('user')->where('productid', $product_detail->uuid)->get();
        $avrgReviewRatings = ReviewRating::with('user')->where('productid', $product_detail->uuid)->avg('rating');
        $avrgReviewRatings = round($avrgReviewRatings);

        $getColorVariants = [];
        if ($product_detail->colour_variants != null) {
            $getColorVariants = $product_detail->colour_variants;
            $getColorVariants = explode(',', $getColorVariants);
        }
        $getSizeVariants = [];
        if ($product_detail->size_variants != null) {
            $getSizeVariants = $product_detail->size_variants;
            $getSizeVariants = explode(',', $getSizeVariants);
        }


        // $getColorVariants = $productModel->getColorVariants();
        // $getSizeVariants = $productModel->getSizeVariants();

        // dd($product_detail);
        // colour_variants
        // size_variants
        return view('frontend.catalog.product.detail', compact('pageTitle', 'bodyClass', 'product_detail', 'user_uuid', 'reviewRatings', 'cats', 'avrgReviewRatings', 'childProducts', 'getColorVariants', 'getSizeVariants'));
    }

    public function moreDetail($slug, Request $request, Category $categoryModel)
    {
        $data = [];
        switch ($slug) {
            case 'supplier':
                $supplier = User::whereNull('deleted_at')->where([['status', 'Active'], ['role', 'SUPPLIER']])->with('company')->get();
                $data = $supplier->groupBy(function ($item, $key) {
                    return $item->name[0];          // treats the name string as an array
                })
                    ->sortBy(function ($item, $key) {      // sorts A-Z at the top level
                        return $key;
                    });
                $data['type'] = 'supplier';
                break;
            case 'manufacturer':
                $brand = Brand::where('status', 'ACTIVE')->orderBy('name')->get();
                $data = $brand->groupBy(function ($item, $key) {
                    return $item->name[0];          // treats the name string as an array
                })
                    ->sortBy(function ($item, $key) {      // sorts A-Z at the top level
                        return $key;
                    });
                $data['type'] = 'manufacturer';
                break;
            case 'category':
                $categories = Category::whereNull('deleted_at')->orderBy('name')->get();
                $data = $categories->groupBy(function ($item, $key) {
                    return $item->name[0];          // treats the name string as an array
                })
                    ->sortBy(function ($item, $key) {      // sorts A-Z at the top level
                        return $key;
                    });
                $data['type'] = 'category';
                break;
        }
        return view('frontend.catalog.product.more-detail', compact('data'));
    }
}
