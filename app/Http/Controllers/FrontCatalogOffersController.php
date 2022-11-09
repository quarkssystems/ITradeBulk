<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\History\RequestQuoteHistory;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\OfferDeals;
use App\Models\Promotion;
use App\User;
use Carbon\Carbon;
use DB;

class FrontCatalogOffersController extends Controller
{
    public function offerList(Request $request, OfferDeals $offerModel)
    {

        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        // $todayDate = strtotime($todayDate);
        // echo($todayDate);
        // DB::enableQueryLog(); // Enable query log
        // $offers = $offerModel->where('offer_method', 'COUPON CODE')->where('status', 'active')
        //     ->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->get();
        // // dd($offersObj);

        // $productOffers = $offerModel->where('offer_method', 'PRODUCT OFFER')->where('status', 'active')
        //     ->where(DB::raw("date_format(start_date, '%Y-%m-%d %H:%i:%s')"), '<=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->where(DB::raw("date_format(end_date, '%Y-%m-%d %H:%i:%s')"), '>=', date('Y-m-d H:i:s', strtotime($todayDate)))
        //     ->with('product')->get();
        // dd($productOffers);

        $promotion = Promotion::join('products', 'products.uuid', '=', 'promotions.product_id')
            ->whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
            ->where('products.published', '=', '1')
            ->select('promotions.*')
            ->whereNotNull('product_id')->get();

        // $promotion = Promotion::with('products')
        //     ->whereDate('promotions.period_from', '<=', date("Y-m-d"))
        //     ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
        //     ->whereNotNull('product_id')->get();

        $promotions = Promotion::join('products', 'products.uuid', '=', 'promotions.product_id')
            ->whereDate('promotions.period_from', '<=', date("Y-m-d"))
            ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
            ->where('products.published', '=', '1')
            ->select('promotions.*', 'products.price as current_price')
            ->whereNotNull('product_id')->get();

        // $promotions = Promotion::with('products')
        //     ->whereDate('promotions.period_from', '<=', date("Y-m-d"))
        //     ->whereDate('promotions.period_to', '>=', date("Y-m-d"))
        //     ->whereNotNull('product_id')->get();



        // dd($promotion);
        $productOffers = $promotion;
        $pageTitle = "Products";
        $bodyClass = ['about-us'];

        $promotions = $promotions->transform(function ($query) {
            if (isset($query->products)) {
                $user = User::with('company')->where('uuid', $query->products->user_id)->first();
                $query->user = $user;
            } else {
                $query->user = null;
            }
            return $query;
        });

        if ($promotions) {
            foreach ($promotions as $pkey => $product) {

                // dd($product->product_id);
                $promotions[$pkey] = $product;
                $promotions[$pkey]['child'] = Product::where('uuid', $product->product_id)->get();
            }
        }
        $offers = $promotions;
        // dd($offers);
        // $offers = '';
        return view('frontend.catalog.offer.list', compact('pageTitle', 'bodyClass',  'productOffers', 'promotion', 'offers'));
    }

    public function offerdetail($offer_id, Request $request, Promotion $offerModel)
    // public function offerdetail($offer_id, Request $request, OfferDeals $offerModel)
    {
        $offer_detail = $offerModel->with(['products'])->where('uuid', $offer_id)->first();
        if (isset($offer_detail->products)) {
            $user = User::with('company')->where('uuid', $offer_detail->products->user_id)->first();
        }
        // $offer_detail = $offerModel->where('uuid', $offer_id)->with('supplierCompany')->with('product')->first();
        // User::where('uuid', $offer_detail->user_id)->first();
        // dd($offer_detail->supplierCompany);
        $pageTitle = "Offer Detail";
        $bodyClass = ['about-us'];

        // dd($offer_detail);

        return view('frontend.catalog.offer.detail', compact('pageTitle', 'bodyClass', 'offer_detail', 'user'));
    }
}
