<?php



namespace App\Repositories;



use App\Exceptions\CustomeException;

use App\General\General;

use App\Models\Product;

use App\Models\Category;

use App\Models\Brand;

use App\Models\Notification;

use App\Models\OfferDeals;

use App\Models\ReviewRating;

use Auth;

use App\Repositories\BaseRepository;

use App\User;

use DB;

use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Hashing\BcryptHasher;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Password;

use Carbon\Carbon;

/**

 * Class ProductRepository.

 */

class ProductRepository extends BaseRepository

{

    use ResetsPasswords;



    public function model()

    {

          return Product::class;

    }



    public function getUnreadNotification($data)

    {

          $user_uuid = $data['user_uuid'];

          $notifyModel = new Notification;

          $result = array();

          $unread_notification = $notifyModel->where('user_id',$user_uuid)->where('status','UNREAD')->count();



         if($unread_notification > 0)

         {

            $result['status'] ="true";

            $result['trader_unread_notification'] = $unread_notification;

         }

         else

         {

            $result['status'] ="false";

            $result['trader_unread_notification'] = 0;

         }

              

        return $result;

    

    }  





    public function productResponse($productscount,$products_arr)

    {

        $products_arr = $products_arr->get();

          $result = array();

          $data_product = array();

          

          if(count($products_arr))

          {    

            // $products_arr = $products_arr->get();

            //$arrProducts = $products_arr->all();

            //echo '<pre>'; print_r($products_arr); die;

            foreach($products_arr as $key => $product) 

              {

                $data_product[$key]['uuid'] = $product->uuid;

                $data_product[$key]['name'] = $product->name;

                $data_product[$key]['single_qty'] = $product->name;

                $data_product[$key]['base_image'] = url('/').$product->base_image;

                $data_product[$key]['stock_type'] = $product->stock_type;

                $data_product[$key]['unit_name'] = $product->unit_name;

                $data_product[$key]['unit_value'] = $product->unit_value;

               } 

            }

            



         if(count($data_product) > 0)

         {

            $result['status'] ="true";

            $result['data'] = $data_product;

         }

         else

         {

            $result['status'] ="false";

            $result['data'] = [];

         }

              

        return $result;

    

    }  





    public function searchProduct($data)

    {

        $productname = $data['searchtext'];

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10; 

        //get total product  

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->where("name", "LIKE", "{$productname}%");

        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product

        

       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $products = $products->where("name", "LIKE", "{$productname}%");

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        $products = $products->skip($offset)->take($limit);

        $products_arr = $products; 

      

       return $this->productResponse($productscount,$products_arr);  

   }





    



   public function arrivalsProduct($data)

    {

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10; 

        // dd($limit);

        //get total product  

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->where("arrival_type",1);

        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product
        // dd($productscount);


       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $products = $products->where("arrival_type",1);

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }

        // $products = $products->skip($offset)->take($limit);
        // DB::enableQueryLog(); // Enable query log

       $products_arr = $products;
       // dd($products_arr);
       // dd(DB::getQueryLog()); // Show results of log

        

       return $this->productResponse($productscount,$products_arr);  

   }





    public function bestSalesProduct($data)

    {

         $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10; 



        //get total product  

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->where("arrival_type",2);

        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product



       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $products = $products->where("arrival_type",2);

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }
        

        $products_arr = $products;

        

       return $this->productResponse($productscount,$products_arr);  

   }





   public function dealsOfDayProduct($data)

    {

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10;



        //get total product  

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->where("arrival_type",3);

        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product



       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $products = $products->where("arrival_type",3);

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }
        

        $products_arr = $products;

        

       return $this->productResponse($productscount,$products_arr);  

   }





    public function bestOfWeekProduct($data)

    {

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10;

         

        //get total product  

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->where("arrival_type",4);

        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product

        

       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

        });

        $products = $products->where("arrival_type",4);

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }
        

        $products =  $products;

        //$products =  $products->get();

        //echo '<pre>'; print_r($products->toArray()); die;  

        

       // echo '<pre>'; print_r($products); die;  

        

       return $this->productResponse($productscount,$products);  

   }



    public function getProductByCat($data)

    {

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10;

        $slug = $data['slug']; 

        //get total product  



        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->whereHas('productCategory.category', function($q1) use ($slug){

                $q1->where('slug', '=', $slug);

            });



        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product

     

       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

        });

       $products = $products->whereHas('productCategory.category', function($q) use ($slug){

                $q->where('slug', '=',  $slug);

            });

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }
        
        $products =  $products;
        

        //$products =  $products->get();

        //echo '<pre>'; print_r($products->toArray()); die;  

        

       // echo '<pre>'; print_r($products); die;  

        

       return $this->productResponse($productscount,$products);  

   }





   public function getProductByMan($data)

    {

        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

        $limit = isset($data['limit']) ? $data['limit'] : 10;

        $slug = $data['slug']; 

        //get total product  



       

        $productscount = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

          });

        $productscount = $productscount->whereHas('brand', function($q) use ($slug){

                $q->where('slug', '=', $slug);

            });



        $productscount = $productscount->where("status", "ACTIVE");

        $productscount = $productscount->where("default_stock_type","1");

        $productscount = $productscount->count();

        //get total product

     

       //get product 10 product each call

        $products = $this->model->whereHas('supplierStock', function($q){

              $q->where('single', '>', 0);

              $q->where('single_price', '>', 0);

        });

        

        $products = $products->whereHas('brand', function($q) use ($slug){

                $q->where('slug', '=', $slug);

            });

      

        $products = $products->where("status", "ACTIVE");

        $products = $products->where("default_stock_type","1");

        if($limit) {
          $products = $products->skip($offset)->take($limit);
        }
        
        $products =  $products;
        

        //$products =  $products->get();

        //echo '<pre>'; print_r($products->toArray()); die;  

        

       // echo '<pre>'; print_r($products); die;  

        

       return $this->productResponse($productscount,$products);  

   }









   public function getCategories($data)

    {

      // dd($data);die();
        $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

       $limit = isset($data['limit']) ? $data['limit'] : 20; 

       

       $category = new Category;

       $categories_count = $category->whereNull('parent_category_id')->where('status','ACTIVE')->count();

      // print_r($categories_count);die();

       $categories = $category->whereNull('parent_category_id')->where('status','ACTIVE');

       // dd($limit);
       // die();
       if($limit) {
         $categories = $categories->skip($offset)->take($limit);
       }
       // DB::enableQueryLog(); // Enable query log

       $categories_arr = $categories->get();

       // dd(DB::getQueryLog()); // Show results of log

       //echo '<pre>'; print_r($categories_arr); die;



       $result = array();

       $data_category = array();

       if(count($categories_arr)){

            foreach($categories_arr as $kcat => $cat) {  

                $data_category[$kcat]['uuid'] = $cat['uuid'];

                $data_category[$kcat]['name'] = $cat['name'];

                $data_category[$kcat]['slug'] = $cat['slug'];

                if (!empty($cat['banner_image_file'])) {

                  $data_category[$kcat]['banner_image_file'] = url('/'). $cat['banner_image_file'];

                } else {

                  $data_category[$kcat]['banner_image_file'] = '';

                }

                if (!empty($cat['banner_image_file'])) {

                  $data_category[$kcat]['thumb_image_file'] = url('/'). $cat['thumb_image_file'];

                } else {

                  $data_category[$kcat]['thumb_image_file'] = '';

                }

            }

         }

         //echo "<pre>"; print_r($data_category); exit;

         if(count($data_category) > 0){

            $result['status'] ="true";

            $result['data'] = $data_category;

         } else {

           $result['status'] ="false";

           $result['data'] = [];

         }

              

        return $result;

   }





   public function getManufacture($data)

    {

       $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

       $limit = isset($data['limit']) ? $data['limit'] : 10;



       $brands = new Brand;

       $brand_count = $brands->where('status','ACTIVE')->count();

       

       $brands = $brands->where('status','ACTIVE');

       if($limit) {
          $brands = $brands->skip($offset)->take($limit);

       }

       // DB::enableQueryLog(); // Enable query log
       $brands_array = $brands->get();
       // dd(DB::getQueryLog()); // Show results of log


       $result = array();

       $data_brand = array();

        if($brands_array){

          foreach($brands_array as $bkey => $brand) { 

              $data_brand[$bkey]['uuid'] = $brand['uuid'];

              $data_brand[$bkey]['name'] = $brand['name'];

              $data_brand[$bkey]['slug'] = $brand['slug'];

              if (!empty($brand['icon_file'])) {

                $data_brand[$bkey]['icon_file'] = url('/'). $brand['icon_file'];

              } else {

                $data_brand[$bkey]['icon_file'] = '';

              }

          }

            // $data_brand['manufacture_total'] = $brand_count;  

        }

         if(count($data_brand)){ 

            $result['status'] ="true";

            $result['data'] = $data_brand;

         } else {  

            $result['status'] ="false";

            $result['data'] = [];

         }

              

        return $result;

   }



   

    public function getSupplierOffers($data)
    {
       // $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;
       // $limit = isset($data['limit']) ? $data['limit'] : 10; 
       $offers = new OfferDeals;
       $todayDate = Carbon::now()->format('Y-m-d');
       $offers_data = array();
       $offer_count = $offers->where('status','active')->whereDate('end_date' , '>=', $todayDate)->count();
       $offers = $offers->whereDate('end_date' , '>=', $todayDate);
       $offers = $offers->where('status','active');
       $offers_data = $offers->get()->toArray(); 
       $result = array();
       $data_offers = array();
      /* if(count($offers_data))
          {
            $k = 0 ; 
            foreach($offers_data as $k1 => $offer) { 
                $data_offers[$k]['uuid'] = $offer['uuid'];
                $data_offers[$k]['title'] = $offer['title'];
                $data_offers[$k]['start_date'] = $offer['start_date'];
                $data_offers[$k]['end_date'] = $offer['end_date'];
                $data_offers[$k]['offer_type'] = $offer['offer_type'];
                $data_offers[$k]['offer_value'] = $offer['offer_value'];
                $data_offers[$k]['user_id'] = $offer['user_id'];
                $data_offers[$k]['offercode'] = $offer['offercode'];
                if (!empty($offer['image'])) {
                  $data_offers[$k]['image'] = url('/'). $offer['image'];
                } else {
                  $data_offers[$k]['image'] = '';
                }
                 $k++;
               }
              //$data_offers['offer_total'] = $offer_count;  
         } */
         if(count($offers_data))
         {   
            $result['status'] ="true";
            $result['data'] =  $offers_data;
         }
         else
         {
            $result['status'] ="false";
            $result['data'] = [];
        }

        return $result;
   }





   public function getProductDetails($data)

    {

        $arr_product_details =[];

        $product_uuid = $data['product_uuid'] ? $data['product_uuid'] : null;

        //get total product  

        $products = $this->model->where("uuid", $product_uuid);

        // $products = $this->model->where("status", "ACTIVE");

        $products = $products->first();

        $stockVariants = Product::where('parent_id',$products->parent_id)
        ->select(DB::raw("CONCAT(UPPER(SUBSTRING(stock_type,1,1)),LOWER(SUBSTRING(stock_type,2))) AS stock_type"),'uuid')->pluck('uuid','stock_type');
      

        // echo "<pre>";
        // print_r($stockVariants);
        // die();

          $arr_product_details['uuid'] = $products->uuid;

          $arr_product_details['name'] = $products->name;

          $arr_product_details['slug'] = $products->slug;

          $arr_product_details['unit'] = $products->unit;

          $arr_product_details['unit_name'] = $products->unit_name;

          $arr_product_details['unit_value'] = $products->unit_value;

          $arr_product_details ['description'] = $products->description;

          $arr_product_details['short_description'] = $products->short_description;

          $arr_product_details['base_image'] = url('/').$products->base_image;

          $arr_product_details['volume'] = $products->unit_value .' '.$products->unit_name;

          $arr_product_details['manufacturer'] =$products->brand_name;

          $arr_product_details ['stock_of'] = $products->stock_of;

          $arr_product_details['stock_type'] = ucfirst($products->stock_type);

          $arr_product_details['default_stock_type'] = $products->default_stock_type;

          $arr_product_details['stock_gst'] = $products->stock_gst;

          $arr_product_details['stoc_wt'] = $products->stoc_wt;





        $category = new Category;

        $category_array =array();



        $selectedCategories = $products->productCategory()->pluck('category_id')->toArray();

        $html = '';

        foreach($selectedCategories as $selectedCategorie){

            $cats[] = Category::select('name','slug')->where('uuid',$selectedCategorie)->first();

        }

        if (count($cats)) {

            $cats = array_values(array_unique($cats));

        }



        if (count($cats)) {

            $cats = array_values(array_unique($cats));

        }

       

        foreach ($cats as $key => $value) {

       

          $category_array[] = $value->name;

       }



      //  $cats = $category->whereIn('uuid',$selectedCategories)->orderBy('created_at','ASC')->pluck('name','slug')->toArray();

        $arr_product_details['categories'] = $category_array;

        

        $reviewRatings_array = [];

        $reviewRatings_cnt = ReviewRating::with('user')->where('productid', $products->uuid)->count();

        $reviewRatings = ReviewRating::with('user')->where('productid', $products->uuid)->limit(2)->orderBy('created_at','DESC')->get()->toArray();

        $avrgReviewRatings = ReviewRating::with('user')->where('productid', $products->uuid)->avg('rating');

        $avrgReviewRatings = round($avrgReviewRatings);

        if ($avrgReviewRatings){

          $arr_product_details['avgrating'] = $avrgReviewRatings;

        }

        

        foreach($reviewRatings as $reviewRating){

         

          $image_data="";

          if($reviewRating['user']['image'] !='')

          {

            $image_data = url('/').$reviewRating['user']['image'];

          }

          $reviewRatings_array[] = array('title'=> $reviewRating['title'], 'rating' =>$reviewRating['rating'],

          'review' =>$reviewRating['rating'], 'created_at' => $reviewRating['created_at'],'user_id'=>$reviewRating['user']['uuid'], 'ratingbyuser' =>$reviewRating['user']['name'] ,'ratingbyuser_image' => $image_data);

        }

        if (count($reviewRatings_array)) {

          $arr_product_details['all_ratings_reviews'] = $reviewRatings_array;

        }



       $arr_product_details['count_reviewrating'] = $reviewRatings_cnt;

       $arr_product_details['stock_variants'] = $stockVariants;



        if(count($arr_product_details))

         {   

            $result['status'] ="true";

            $result['data'] =  $arr_product_details;

         }

         else

         {

            $result['status'] ="false";

            $result['data'] = [];

         }

        return $result; 

        /* echo "<pre>";

        print_r($reviewRatings_array);

        die; */

        /* echo "<pre>";

        print_r($arr_product_details);

        die; */

       

       

       //return $this->productResponse($arr_product_details,$arr_product_details);  

   }



  public function givingrating($data)

  {

    $data = array_merge($data,array('status'=>'active'));

     return ReviewRating::create($data);

  }



  public function getRatingReview($data)

  {



       $offset = isset($data['start_offset']) ? $data['start_offset'] : 0;

       $limit = isset($data['limit']) ? $data['limit'] : 10; 

       $product_id = $data['productid']; 

         

       $review = new ReviewRating;

       

       $reviews = $review->with('user')->where('productid' , $product_id);

       $reviews = $reviews->where('status','active');

       $reviews = $reviews->skip($offset)->take($limit)->orderBy('created_at','DESC');

       $review_data = $reviews->get()->toArray(); 

      

         $array_review = array();

       foreach ($review_data as $key_review => $review) {



         

          $array_review[$key_review]['productid'] =  $review['productid'];

          $array_review[$key_review]['title'] =  $review['title'];

          $array_review[$key_review]['rating'] =  $review['rating'];

          $array_review[$key_review]['review'] =  $review['review'];

          $array_review[$key_review]['created_at'] =  $review['created_at'];

          $array_review[$key_review]['user_uuid'] =  $review['user_uuid'];



              $image_data="";

              if($review['user']['image'] != '')

              {

                  $image_data = url('/').$review['user']['image'];

              }

              $array_review[$key_review]['ratingbyuser'] =  $review['user']['name'];

              $array_review[$key_review]['ratingbyuser_image'] = $image_data;



                      

        }

        

         if(count($array_review))

         {   

            $result['status'] ="true";

            $result['data'] =  $array_review;

         }

         else

         {

            $result['status'] ="false";

            $result['data'] = [];

         }

   

        return $result;

   }



}

