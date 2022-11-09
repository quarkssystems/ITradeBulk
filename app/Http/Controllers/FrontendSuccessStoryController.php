<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontendContactSubmitRequest;
use App\Http\Requests\FrontUserSuccessStory;
use App\Mail\ContactMail;
use App\Models\SuccessStory;
use App\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\RequestQuote;
use App\Models\ReviewRating;
use App\Http\Requests\AdminRequestQuoteRequest;
use App\Http\Requests\FrontSupplierSuccessStory;
use Illuminate\Support\Facades\Mail;

class FrontendSuccessStoryController extends Controller
{
    public function index(SuccessStory $SuccessStory){
        $pageTitle = 'Success Story';
        $user = \Auth::user();
        $route = 'user.success-story';
        $SuccessStory = SuccessStory::where('user_uuid',$user->uuid)->first();
        if(isset($SuccessStory)){

            $user['title'] = $SuccessStory['title'];
            $user['description'] = $SuccessStory['description'];
        } else {

            $user['title'] = '';
            $user['description'] = '';
        
        }
        return view('supplier.successStory.index',compact('pageTitle','user','route'));
    }

    public function update(FrontSupplierSuccessStory $request){
        
            $input = $request->all();
            $SuccessStory = SuccessStory::firstOrNew(array('user_uuid' => $input['uuid']));
            $SuccessStory->title = $input['title'];
            $SuccessStory->description = $input['description'];
            $SuccessStory->save();
            $route = 'user.success-story';
            return redirect(route("$route.index", $input['uuid']))->with(['status' => 'success', 'message' => trans('success.successStory|added')]);
        
    }

    public function saveReviewRating(FrontUserSuccessStory $request, Product $productModel){
        $input = $request->all();
        $input['status'] = 'active';
        ReviewRating::create($input);
        $product_detail = $productModel->where('uuid', $input['productid'])->first();
        return redirect(route("productdetail", $product_detail['slug']))->with(['status' => 'success', 'message' => trans('success.ratingReview|added')]);
    }

     /**
     * @param SuccessStory $SuccessStory
     */
    public function show(SuccessStory $SuccessStory)
    {
        //
    }
}
