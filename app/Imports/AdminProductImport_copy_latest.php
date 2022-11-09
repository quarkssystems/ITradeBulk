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

class AdminProductImport_copy_latest implements ToModel, WithHeadingRow
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  //static $str_array = array();
  private $product_add = 0;
  private $product_update = 0;
  public static $error = [];
  public $uploadPath = '/uploads/media/:mediatype/:userId/';
  public function modelOld(array $row)
  {
    // Define varible of  csv colonm
    $barcode = trim($row['stoc_bcode']);
    if (isset($barcode) && !empty($barcode)) {
      $productname  = trim($row['stoc_product_name']);
      $productDesc  = trim($row['stoc_product_desc']);
      $base_price   = trim($row['stoc_sp1']);
      $unit         = trim($row['stoc_unit']);
      $stockPack    = trim($row['stoc_pack']);
      $stockType    = trim($row['stoc_type']);
      $stockGST     = trim($row['stoc_vat']);
      $unitMsr      = trim($row['stoc_unmsr']);
      $stockUnitWt  = trim($row['stoc_unwt']);
      $base_image   = $barcode . '.jpg';
      $search_keyword = trim($row['search_keyword']);
      $meta_title = trim($row['meta_title']);
      $meta_keyword = trim($row['meta_keyword']);
      $meta_description = trim($row['meta_description']);
      $manufacturer = trim($row['brand']);
      // $manufacturer = trim($row['manufacturer']);
      $category = trim($row['category']);
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
      $parentId  = trim($row['parent_id']);
      // $defaultStockType  = trim($row['default_stock_type']);

      //Add Category
      $categoryId = null;
      $subcategory1Id = null;
      $subcategory2Id = null;
      $subcategory3Id = null;
      $subcategory4Id = null;


      $masterProduct = Product::where('variant_id', $variantId)->where('parent_id', '0')->first();

      if (isset($masterProduct)) {

        $checkAvailableorNot = Product::where('variant_id', $variantId)->where('stock_type', $stockType)->first();

        if ($checkAvailableorNot) {

          // $productMatch = Product::whereId($checkAvailableorNot->id)->where('barcode',$barcode)->first();

          // if($productMatch) {

          //   $arrProductRequest = array(
          //     // 'name' => $productname,
          //     // 'slug' => str_slug($productname,'-'),
          //     // 'unit' => $unitIn,
          //     // 'unit_name' => strtoupper($unitMsr),
          //     // 'unit_value'=> $stockUnitWt,
          //     // 'description' => $productDesc, 
          //     // 'short_description' => $productDesc,
          //     'base_price' => $base_price,
          //     // 'search_keyword' => $search_keyword,
          //     // 'meta_title' => $meta_title,
          //     // 'meta_keyword' =>$meta_keyword,
          //     // 'meta_description' => $meta_description,
          //     // 'stock_of' => $stockPack,
          //     // 'stock_type' => $stockType,
          //     'stock_gst' => $stockGST,
          //     // 'stoc_wt' => $stockwt,
          //     // 'status' => 'ACTIVE',
          //   );

          //   $this->product_update++;
          //   Product::whereId($productMatch->id)->update($arrProductRequest);

          // } else {
          self::$error[] = $barcode;
          // }

        } else {
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
            'brand_id' => $brandid,
            'stoc_wt' => $stockwt,
            'status' => 'ACTIVE',
            'barcode' => $barcode,
            'variant_id' => $variantId,
            // 'default_stock_type' => $defaultStockType
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
              $arrProductRequest['parent_id'] = $masterProduct->uuid;
              // $arrProductRequest['default_stock_type'] = $defaultStockType;

              // $variant_product = Product::where('variant_id',$variantId)->where('default_stock_type','=','1')->first();
              // // dd($variant_product); 
              // if(isset($variant_product) && !empty($variant_product)) { 
              //   $arrProductRequest['default_stock_type'] = 0;
              // } else { 
              //   $arrProductRequest['default_stock_type'] = 1;
              // }

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
              // $arrProductRequest['default_stock_type'] = $defaultStockType;
              // $variant_product = Product::where('variant_id',$variantId)->where('default_stock_type','1')->first(); 
              // // dd($variant_product);
              // if(isset($variant_product) && !empty($variant_product)) { 
              //   $arrProductRequest['default_stock_type'] = 0;
              // } else { 
              //   $arrProductRequest['default_stock_type'] = 1;
              // }

              $arrProductRequest['parent_id'] = $masterProduct->uuid;
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
      } else {

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
          'brand_id' => $brandid,
          'stoc_wt' => $stockwt,
          'status' => 'ACTIVE',
          'barcode' => $barcode,
          'variant_id' => $variantId,
          // 'default_stock_type' => $defaultStockType
        );

        $arrMasterProductRequest = array(
          'name' => $productname,
          'slug' => str_slug($productname, '-'),
          'description' => $productDesc,
          'short_description' => $productDesc,
          'base_image' => $base_image,
          'search_keyword' => $search_keyword,
          'meta_title' => $meta_title,
          'meta_keyword' => $meta_keyword,
          'meta_description' => $meta_description,
          'brand_id' => $brandid,
          'status' => 'ACTIVE',
          'variant_id' => $variantId,
          // 'default_stock_type' => $defaultStockType
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
            $arrMasterProductRequest['base_image'] = $base_image;
            // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
            $arrMasterProductRequest['default_stock_type'] = 0;
            $arrMasterProductRequest['parent_id'] = 0;
          }

          $this->product_add++;
          $masterProductModel = Product::create($arrMasterProductRequest);
          $arrProductRequest['base_image'] = $base_image;
          // $arrProductRequest['default_stock_type'] = $defaultStockType;
          $arrProductRequest['default_stock_type'] = 1;
          $arrProductRequest['parent_id'] = $masterProductModel->uuid;
          $productModel = Product::create($arrProductRequest);
          $product_uuid = $masterProductModel->uuid;
          $product_uuid1 = $productModel->uuid;

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

          if (isset($product_uuid1) && !empty($product_uuid1)) {
            ProductCategory::where('product_id', $product_uuid1)->delete();
            $par_cat = array();
            if (isset($category) && !empty($category)) {
              $cat_uuid = $this->checkCategory($category, $product_uuid1);
              $par_cat = $this->getParentCategory($cat_uuid);
              array_push($par_cat,  $cat_uuid);
              foreach ($par_cat as $key => $cat_val) {
                # code...
                ProductCategory::create(['product_id' => $product_uuid1, 'category_id' => $cat_val]);
              }
            }
          }
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
            $arrMasterProductRequest['base_image'] = $base_image;
            // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
            $arrMasterProductRequest['default_stock_type'] = 0;
            $arrMasterProductRequest['parent_id'] = 0;
          }
          $this->product_update++;
          $productModel = $productobj->where('barcode', $barcode)->update($arrMasterProductRequest);
          $product_uuid = $checkproduct->uuid;

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
    }
  }

  public function model(array $row)
  {
    // Define varible of  csv column
    $barcode = trim($row['Barcode']);

    if (isset($barcode) && !empty($barcode)) {
      $hasUPC = trim($row['Has_UPC']);
      $productCode = trim($row['Product_Code']);
      $parentId = trim($row['Parent_ID']);
      $variantId = trim($row['Variant_ID']);
      $unitBarcodeLink = trim($row['Unit_Barcode_Link']);
      $stockType = trim($row['Packing']);
      $stockPack = trim($row['Units_Per_Packing']);
      $size = trim($row['Size']);
      $unitMsr = trim($row['Unit_Of_Measure']);
      $sizeDescription = trim($row['Size_Description']);
      $height = trim($row['Height']);
      $width = trim($row['Width']);
      $depth = trim($row['Depth']);
      $stockwt = trim($row['Weight']);
      // $stockUnitWt = trim($row['stoc_unwt']);
      $productname = trim($row['Description']);
      $productBrand = trim($row['Brand']);
      $manufacturer = trim($row['Brand']);
      // $manufacturer = trim($row['Manufacturer']);
      $colour = trim($row['Colour']);
      $colourVariants = trim($row['Colour_Variants']);
      $sizeVariants = trim($row['Size_Variants']);
      $department = trim($row['Department']);
      $subDepartment = trim($row['SubDepartment']);
      $category = trim($row['Category']);
      $subCategory = trim($row['SubCategory']);
      $segment = trim($row['Segment']);
      $subSegment = trim($row['SubSegment']);
      $categoryGroup = trim($row['Category_Group']);
      // $meta_title = trim($row['meta_title']); 
      // $meta_keyword = trim($row['meta_keyword']); 
      // $meta_description = trim($row['meta_description']); 
      $specSheetUrl = trim($row['Spec_Sheet_Url']);
      $productDesc = trim($row['Product_Specification']);
      $warranty = trim($row['Warranty']);
      $search_keyword = trim($row['Attributes']);
      // $imageFileName = trim($row['Image_File_Name']); 
      // $alternateImage1 = trim($row['Alternate_Image_1']); 
      // $alternateImage2 = trim($row['Alternate_Image_2']); 

      // $base_price   = trim($row['stoc_sp1']); //
      // $unit         = trim($row['stoc_unit']); //
      // $stockGST     = trim($row['stoc_vat']); //
      $base_image   = $barcode . '.jpg';
      $unitMeasure = array('kg', 'gr', 'ea', 's', 'mm', 'mt');

      $unitIn = (in_array(strtolower($unitMsr), $unitMeasure)) ? 'Weight' : 'Unit';
      // $stockGST = (strtolower($stockGST) == 'n') ? 0 : 1;

      if (isset($stockType) && !empty($stockType)) {
        $stockType = strtolower($stockType);
      }

      //Add Category
      $categoryId = null;
      $subcategory1Id = null;
      $subcategory2Id = null;
      $subcategory3Id = null;
      $subcategory4Id = null;

      $masterProduct = Product::where('variant_id', $variantId)->where('parent_id', '0')->first();

      if (isset($masterProduct)) {
        $checkAvailableorNot = Product::where('variant_id', $variantId)->where('stock_type', $stockType)->first();

        if ($checkAvailableorNot) {
          self::$error[] = $barcode;
        } else {
          //Add Brand
          $brandid = '';
          $data = '';
          if (isset($manufacturer) && !empty($manufacturer)) {
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
            // 'unit_value'=> $stockUnitWt,
            'description' => $productDesc,
            'short_description' => $productDesc,
            'base_image' => $base_image,
            // 'base_price' => $base_price,
            'search_keyword' => $search_keyword,
            // 'meta_title' => $meta_title,
            // 'meta_keyword' =>$meta_keyword,
            // 'meta_description' => $meta_description,
            'stock_of' => $stockPack,
            'stock_type' => $stockType,
            // 'stock_gst' => $stockGST,
            'brand_id' => $brandid,
            'stoc_wt' => $stockwt,
            'status' => 'ACTIVE',
            'barcode' => $barcode,
            'variant_id' => $variantId,
            // 'default_stock_type' => $defaultStockType

            //new fields added
            'has_upc' => $hasUPC,
            'product_code' => $productCode,
            'unit_barcode_link' => $unitBarcodeLink,
            'size' => $size,
            'size_description' => $sizeDescription,
            'height' => $height,
            'width' => $width,
            'depth' => $depth,
            'product_brand' => $productBrand,
            'colour' => $colour,
            'colour_variants' => $colourVariants,
            'size_variants' => $sizeVariants,
            'department' => $department,
            'subdepartment' => $subDepartment,
            'category' => $category,
            'subcategory' => $subCategory,
            'segment' => $segment,
            'subsegment' => $subSegment,
            'spec_sheet_url' => $specSheetUrl,
            'warranty' => $warranty,
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
              $arrProductRequest['parent_id'] = $masterProduct->uuid;
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

              $arrProductRequest['parent_id'] = $masterProduct->uuid;
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
      } else {
        //main outer
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
          // 'unit_value'=> $stockUnitWt,
          'description' => $productDesc,
          'short_description' => $productDesc,
          'base_image' => $base_image,
          // 'base_price' => $base_price,
          'search_keyword' => $search_keyword,
          // 'meta_title' => $meta_title,
          // 'meta_keyword' =>$meta_keyword,
          // 'meta_description' => $meta_description,
          'stock_of' => $stockPack,
          'stock_type' => $stockType,
          // 'stock_gst' => $stockGST,
          'brand_id' => $brandid,
          'stoc_wt' => $stockwt,
          'status' => 'ACTIVE',
          'barcode' => $barcode,
          'variant_id' => $variantId,
          // 'default_stock_type' => $defaultStockType

          //new fields added
          'has_upc' => $hasUPC,
          'product_code' => $productCode,
          'unit_barcode_link' => $unitBarcodeLink,
          'size' => $size,
          'size_description' => $sizeDescription,
          'height' => $height,
          'width' => $width,
          'depth' => $depth,
          'product_brand' => $productBrand,
          'colour' => $colour,
          'colour_variants' => $colourVariants,
          'size_variants' => $sizeVariants,
          'department' => $department,
          'subdepartment' => $subDepartment,
          'category' => $category,
          'subcategory' => $subCategory,
          'segment' => $segment,
          'subsegment' => $subSegment,
          'spec_sheet_url' => $specSheetUrl,
          'warranty' => $warranty,
        );

        $arrMasterProductRequest = array(
          'name' => $productname,
          'slug' => str_slug($productname, '-'),
          'description' => $productDesc,
          'short_description' => $productDesc,
          'base_image' => $base_image,
          'search_keyword' => $search_keyword,
          // 'meta_title' => $meta_title,
          // 'meta_keyword' =>$meta_keyword,
          // 'meta_description' => $meta_description,
          'brand_id' => $brandid,
          'status' => 'ACTIVE',
          'variant_id' => $variantId,
          // 'default_stock_type' => $defaultStockType

          //new fields added
          'has_upc' => $hasUPC,
          'product_code' => $productCode,
          'unit_barcode_link' => $unitBarcodeLink,
          'size' => $size,
          'size_description' => $sizeDescription,
          'height' => $height,
          'width' => $width,
          'depth' => $depth,
          'product_brand' => $productBrand,
          'colour' => $colour,
          'colour_variants' => $colourVariants,
          'size_variants' => $sizeVariants,
          'department' => $department,
          'subdepartment' => $subDepartment,
          'category' => $category,
          'subcategory' => $subCategory,
          'segment' => $segment,
          'subsegment' => $subSegment,
          'spec_sheet_url' => $specSheetUrl,
          'warranty' => $warranty,
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
            $arrMasterProductRequest['base_image'] = $base_image;
            // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
            $arrMasterProductRequest['default_stock_type'] = 0;
            $arrMasterProductRequest['parent_id'] = 0;
          }

          $this->product_add++;
          $masterProductModel = Product::create($arrMasterProductRequest);
          $arrProductRequest['base_image'] = $base_image;
          // $arrProductRequest['default_stock_type'] = $defaultStockType;
          $arrProductRequest['default_stock_type'] = 1;
          $arrProductRequest['parent_id'] = $masterProductModel->uuid;
          $productModel = Product::create($arrProductRequest);
          $product_uuid = $masterProductModel->uuid;
          $product_uuid1 = $productModel->uuid;

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

          if (isset($product_uuid1) && !empty($product_uuid1)) {
            ProductCategory::where('product_id', $product_uuid1)->delete();
            $par_cat = array();
            if (isset($category) && !empty($category)) {
              $cat_uuid = $this->checkCategory($category, $product_uuid1);
              $par_cat = $this->getParentCategory($cat_uuid);
              array_push($par_cat,  $cat_uuid);
              foreach ($par_cat as $key => $cat_val) {
                # code...
                ProductCategory::create(['product_id' => $product_uuid1, 'category_id' => $cat_val]);
              }
            }
          }
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
            $arrMasterProductRequest['base_image'] = $base_image;
            // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
            $arrMasterProductRequest['default_stock_type'] = 0;
            $arrMasterProductRequest['parent_id'] = 0;
          }
          $this->product_update++;
          $productModel = $productobj->where('barcode', $barcode)->update($arrMasterProductRequest);
          $product_uuid = $checkproduct->uuid;

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

  public static function getError(): array
  {
    //dd(self::$error);
    return self::$error;
  }
}
