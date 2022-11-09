<?php



namespace App\Imports;



use App\Models\Product;

use App\Models\ProductCategory;

use App\Models\Brand;



use App\Models\Category;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\Auth\Guard;

use Carbon\Carbon;

use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;



class AdminProductImport22122020 implements ToModel, WithHeadingRow

{

  /**

   * @param array $row

   *

   * @return \Illuminate\Database\Eloquent\Model|null

   */

  //static $str_array = array();

  private $product_add = 0;

  private $product_update = 0;

  public $uploadPath = '/uploads/media/:mediatype/:userId/';



  public function model(array $row)

  {



    // Define varible of  csv colonm

    $barcode      = trim($row['stoc_bcode']);



    if (isset($barcode) && !empty($barcode)) {

      $productname  = trim($row['stoc_product_name']);

      $productDesc  = trim($row['stoc_product_desc']);

      $base_price   = trim($row['stoc_sp1']);

      $unit         = trim($row['stoc_unit']);

      $stockPack    = trim($row['stoc_pack']);

      $stockType    = trim($row['stoc_type']);

      $stockGST     = trim($row['stoc_gst']);

      $unitMsr      = trim($row['stoc_unmsr']);

      $stockUnitWt  = trim($row['stoc_unwt']);



      $base_image   = $barcode . '.jpg';

      $search_keyword = trim($row['search_keyword']);

      $meta_title = trim($row['meta_title']);

      $meta_keyword = trim($row['meta_keyword']);

      $meta_description = trim($row['meta_description']);

      $manufacturer = trim($row['manufacturer']);

      $category = trim($row['category']);

      // $subcategory1 = trim($row['subcategory1']);

      // $subcategory2 = trim($row['subcategory2']);



      // $subcategory3 = trim($row['subcategory3']);

      //$subcategory4 = trim($row['subcategory4']);

      $unitMeasure = array('kg', 'gr', 'ea', 's', 'mm', 'mt');

      if (in_array(strtolower($unitMsr), $unitMeasure)) {

        $unitIn = 'Weight';
      } else {

        $unitIn = 'Volume';
      }

      if (strtolower($stockGST) == 'n') {

        $stockGST = 0;
      } else if (strtolower($stockGST) == 'y') {

        $stockGST = 1;
      }

      if (isset($stockType) && !empty($stockType)) {

        $stockType = strtolower($stockType);
      }



      $stockwt  = trim($row['stoc_wt']);
      $variantId  = trim($row['variant_id']);

      //Add Category

      $categoryId = null;

      $subcategory1Id = null;

      $subcategory2Id = null;

      $subcategory3Id = null;

      $subcategory4Id = null;





      //Add Brand

      $brandid = '';

      $data = '';

      if (isset($manufacturer) && !empty($manufacturer)) {

        // $checkbrand = Brand::whereRaw('LOWER(name)', $manufacturer)->first();

        $checkbrand = Brand::whereRaw('LOWER(`name`) = ? ', [strtolower($manufacturer)])->first();

        if ($checkbrand === null) {

          $data = Brand::create(['name' => $manufacturer, 'slug' => $manufacturer]);

          $brandid = $data->uuid;
        } else {

          $brandid = $checkbrand->uuid;
        }
      }

      $arrProductRequest = array(

        'name' => $productname,

        'slug' => str_slug($productname, '-'),

        'unit' => $unitIn,

        'unit_name' => strtoupper($unitMsr),

        'unit_value' => $stockUnitWt,

        'description' => $productDesc,

        'short_description' => $productDesc,

        'base_image' => $base_image,

        'base_price' => $base_price,

        'search_keyword' => $search_keyword,

        'meta_title' => $meta_title,

        'meta_keyword' => $meta_keyword,

        'meta_description' => $meta_description,

        'stock_of' => $stockPack,

        'stock_type' => $stockType,

        'stock_gst' => $stockGST,

        /*'min_price',

            'max_price',*/

        'brand_id' => $brandid,

        /*'tax_id',

            'single_qty',

            'single_weight',

            'shrink_qty',

            'shrink_weight',

            'case_qty',

            'case_weight',

            'pallet_qty',

            'pallet_weight',

  

            'single_height',

            */

        'stoc_wt' => $stockwt,

        /*

            'single_width',

            'arrival_type',

            'single_length',

            'shrink_height',

            'shrink_width',

            'shrink_length',

            'case_height',

            'case_width',

            'case_length',

            'pallet_height',

            'pallet_width',

            'pallet_length',

  

            'single_bundle_of',

            'shrink_bundle_of',

            'case_bundle_of',

            'pallet_bundle_of',

  

            'user_id',

            */

        'status' => 'ACTIVE',

        'barcode' => $barcode,

        'variant_id' => $variantId

      );



      $productobj = new Product;  //Create project object 



      // Barcode is exist in db if not than insert product 

      $checkproduct = Product::where('barcode', $barcode)->first();

      if ($checkproduct === null) {

        if (isset($base_image) && !empty($base_image)) {

          $carbon = new Carbon();

          $replacePathArray = [

            ':userId' => auth()->user()->uuid ?? '',

            ':mediatype' => 'product',

          ];

          $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);

          $uploadPath = public_path($uploadLocalPath);



          if (!File::isDirectory($uploadPath)) {

            File::makeDirectory($uploadPath, 0755, true, true);
          }

          $base_image = $uploadLocalPath . $base_image;

          $arrProductRequest['base_image'] = $base_image;
        }



        $this->product_add++;

        $productModel = Product::create($arrProductRequest);

        $product_uuid = $productModel->uuid;
      } else {

        if (isset($base_image) && !empty($base_image)) {

          $carbon = new Carbon();

          $replacePathArray = [

            ':userId' => auth()->user()->uuid ?? '',

            ':mediatype' => 'product',

          ];

          $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);

          $uploadPath = public_path($uploadLocalPath);



          if (!File::isDirectory($uploadPath)) {

            File::makeDirectory($uploadPath, 0755, true, true);
          }

          $base_image = $uploadLocalPath . $base_image;

          $arrProductRequest['base_image'] = $base_image;
        }

        $this->product_update++;

        $productModel = $productobj->where('barcode', $barcode)->update($arrProductRequest);

        $product_uuid = $checkproduct->uuid;
      }



      ProductCategory::where('product_id', $product_uuid)->delete();



      $par_cat = array();

      if (isset($category) && !empty($category)) {

        $cat_uuid = $this->checkCategory($category, $product_uuid);

        $par_cat = $this->getParentCategory($cat_uuid);

        array_push($par_cat,  $cat_uuid);

        foreach ($par_cat as $key => $cat_val) {

          # code...

          ProductCategory::create(['product_id' => $product_uuid, 'category_id' => $cat_val]);
        }
      }
    }
  }





  public function getParentCategory($categoryid)

  {

    $str_array = array();

    $check_category = Category::where('uuid', $categoryid)->first();

    if ($check_category) {

      $pid = $check_category->parent_category_id;

      if ($pid !== null) {

        array_push($str_array,  $pid);

        $this->getParentCategory($pid);
      }
    }
    return $str_array;
  }







  public function checkCategory($category, $product_uuid)

  {

    $check_category = Category::whereRaw('LOWER(name) = ?', [strtolower($category)])->first();

    if ($check_category === null) {

      //Category Create new

      $data = Category::create(['name' => $category, 'slug' => str_slug($category, '-')]);

      $categoryId = $data->uuid;
    } else {

      $categoryId = $check_category->uuid;
    }





    return $categoryId;
  }



  public function getProductAddCount(): int

  {

    return $this->product_add;
  }



  public function getProductUpdateCount(): int

  {

    return $this->product_update;
  }
}
