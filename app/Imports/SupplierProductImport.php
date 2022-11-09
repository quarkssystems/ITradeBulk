<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OfferDeals;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\SupplierItemInventory;
use App\Models\Promotion;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SupplierProductImport implements ToModel, WithHeadingRow
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

  // // set the preferred date format
  // private $date_format = 'Y-m-d';

  // // set the columns to be formatted as dates
  // private $date_columns = ['Period_From', 'Period_To', 'Stock_Expiry_Date'];

  // // bind date formats to column defined above
  // public function bindValue(Cell $cell, $value)
  // {
  //   if (in_array($cell->getColumn(), $this->date_columns)) {
  //     $cell->setValueExplicit(Date::excelToDateTimeObject($value)->format($this->date_format), DataType::TYPE_STRING);

  //     return true;
  //   }

  //   // else return default behavior
  //   return parent::bindValue($cell, $value);
  // }
  public function transformDate($value, $format = 'Y-m-d')
  {
    try {
      return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
    } catch (\ErrorException $e) {
      if (is_int($value)) {
        return \Carbon\Carbon::createFromFormat($format, $value);
      } else {
        return \Carbon\Carbon::parse($value)->format($format);
      }
    }
  }

  public function model(array $row)
  {
    // Define varible of  csv column
    $barcode = trim($row['Barcode']);

    if (isset($barcode) && !empty($barcode)) {
      $audited = '0';
      // $audited = trim($row['Audited']);
      $published = '0';
      // $published = trim($row['Published']);

      $store_id = trim($row['Store_ID']);
      $hasUPC = trim($row['Has_UPC']);
      $productCode = trim($row['Product_Code']);
      $store_item_code = trim($row['Store_Item_Code']);
      $parentId = trim($row['Parent_ID']);
      $variantId = trim($row['Variant_ID']);
      $unitBarcodeLink = trim($row['Unit_Barcode_Link']);
      $productname = trim($row['Description']);
      $productBrand = trim($row['Brand']);
      $manufacturer = trim($row['Manufacturer']);
      $categoryGroup = trim($row['Category_Group']);
      $department = trim($row['Department']);
      $category = trim($row['Category']);
      $subCategory = trim($row['SubCategory']);
      $segment = trim($row['Segment']);
      $subSegment = trim($row['SubSegment']);
      $vat = trim($row['Vat']);
      $cost = trim($row['Cost']);
      $markup = trim($row['Markup']);
      $autoprice = trim($row['Autoprice']);
      $price = trim($row['Price']);
      $quantity = trim($row['Quantity']);
      $min_order_quantity = trim($row['Min_Order_Quantity']);
      // $stock_expiry_date = trim($row['Stock_Expiry_Date']);
      $stockType = trim($row['Packing']);
      $stockPack = trim($row['Units_Per_Packing']);
      $size = trim($row['Size']);
      $unitMsr = trim($row['Unit_Of_Measure']);
      $sizeDescription = trim($row['Size_Description']);
      $height = trim($row['Height']);
      $width = trim($row['Width']);
      $depth = trim($row['Depth']);
      $stockwt = trim($row['Weight']);
      $colour = trim($row['Colour']);
      $colourVariants = trim($row['Colour_Variants']);
      $sizeVariants = trim($row['Size_Variants']);
      $specSheetUrl = trim($row['Spec_Sheet_Url']);
      $productDesc = trim($row['Product_Specification']);
      $warranty = trim($row['Warranty']);
      $attributes = trim($row['Attributes']);
      $image_file_name = trim($row['Image_File_Name']);
      $alternate_image_1 = trim($row['Alternate_Image_1']);
      $alternate_image_2 = trim($row['Alternate_Image_2']);
      $promotion_type = trim($row['Promotion_Type']);
      $promotion_id = trim($row['Promotion_ID']);
      // $period_from = trim($row['Period_From']);
      // $period_to = trim($row['Period_To']);
      $promotion_price = trim($row['Promotion_Price']);
      $courier_safe = trim($row['Courier safe']);
      $out_of_stock_lead_time = trim($row['Out Of Stock Lead Time']);
      $product_delivery_type = trim($row['Product Delivery Type']);
      $search_keyword = trim($row['Attributes']);
      // $stock_expiry_date =  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Stock_Expiry_Date']))->format('Y-m-d');
      // $period_from = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Period_From']))->format('Y-m-d');
      // $period_to = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Period_To']))->format('Y-m-d');
      // if (isset($row['Period_From']) && trim($row['Period_From']) != "") {
      //   $period_from = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Period_From']))->format('Y-m-d');
      // } else {
      //   $period_from = null;
      // }
      // if (isset($row['Period_To']) && trim($row['Period_To']) != "") {
      //   $period_to = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Period_To']))->format('Y-m-d');
      // } else {
      //   $period_to = null;
      // }
      // if (isset($row['Stock_Expiry_Date']) && trim($row['Stock_Expiry_Date']) != "") {
      //   $stock_expiry_date =  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($row['Stock_Expiry_Date']))->format('Y-m-d');
      // } else {
      //   $stock_expiry_date = null;
      // }
      $period_from = $row['Period_From'] ? $this->transformDate($row['Period_From']) : null;
      $period_to = $row['Period_To'] ? $this->transformDate($row['Period_To']) : null;
      $stock_expiry_date = $row['Stock_Expiry_Date'] ? $this->transformDate($row['Stock_Expiry_Date']) : null;



      // dd($promotion_id, $promotion_type, $period_from, $period_to, $promotion_price);

      // $stockUnitWt = trim($row['stoc_unwt']);

      // $manufacturer = trim($row['Manufacturer']);
      // $subDepartment = trim($row['SubDepartment']);

      // $meta_title = trim($row['meta_title']); 
      // $meta_keyword = trim($row['meta_keyword']); 
      // $meta_description = trim($row['meta_description']); 
      // $search_keyword = trim($row['Attributes']); 
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

      // $user = User::where('role','ADMIN')->select('uuid')->first();

      $masterProduct = Product::withoutGlobalScopes()->where('variant_id', $variantId)->where('parent_id', '0')->first();

      // if (isset($masterProduct)) {
      $checkAvailableorNot = Product::withoutGlobalScopes()->where('variant_id', $variantId)->where('stock_type', $stockType)->first();

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
          'user_id' => auth()->user()->uuid,
          'store_id' => $store_id,
          'name' => $productname,
          'slug' => str_slug($productname, '-'),
          'unit' => $unitIn,
          'unit_name' => strtoupper($unitMsr),
          // 'unit_value' => $stockPack,
          'stock_of' => $stockPack,
          'stock_type' => $stockType,

          'audited' => $audited,
          'published' => $published,
          'has_upc' => $hasUPC,
          'product_code' => $productCode,
          'store_item_code' => $store_item_code,
          'parent_id' => $parentId,
          'variant_id' => $variantId,
          'unit_barcode_link' => $unitBarcodeLink,
          'name' => $productname,
          'brand_id' => $brandid,
          'manufacturer' => $manufacturer,
          'category_group' => $categoryGroup,
          'department' => $department,
          'category' => $category,
          'subcategory' => $subCategory,
          'segment' => $segment,
          'subsegment' => $subSegment,
          'vat' => $vat,
          'cost' => $cost,
          'markup' => $markup,
          'autoprice' => $autoprice,
          'price' => $price,
          'quantity' => $quantity,
          'min_order_quantity' => $min_order_quantity,
          'stock_expiry_date' => $stock_expiry_date,
          'packing' => $stockType,
          'units_per_packing' => $stockPack,
          // 'unit' => 
          // 'unit' => $unitMsr,
          // 'unit_name' => $size,
          // 'unit_value' => $size,
          'size' => $size,
          'unit_of_measure' => $unitMsr,
          'size_description' => $sizeDescription,
          'height' => $height,
          'width' => $width,
          'depth' => $depth,
          'weight' => $stockwt,
          'colour' => $colour,
          'colour_variants' => $colourVariants,
          'size_variants' => $sizeVariants,
          'spec_sheet_url' => $specSheetUrl,
          'product_specification' => $productDesc,
          'warranty' => $warranty,
          'attributes' => $attributes,
          'base_image' => $image_file_name,
          'alternate_image_1' => $alternate_image_1,
          'alternate_image_2' => $alternate_image_2,
          'promotion_type' => $promotion_type,
          'promotion_id' => $promotion_id,
          'period_from' => $period_from,
          'period_to' => $period_to,
          'promotion_price' => $promotion_price,
          'courier_safe' => $courier_safe,
          'out_of_stock_lead_time' => $out_of_stock_lead_time,
          'product_delivery_type' => $product_delivery_type,
          'stoc_wt' => $stockwt,
          'unit_value' => $size,
          'barcode' => $barcode
        );


        $arrProductRequestforupdate = array(
          'user_id' => auth()->user()->uuid,
          'store_id' => $store_id,
          'name' => $productname,
          'slug' => str_slug($productname, '-'),
          'unit' => $unitIn,
          'unit_name' => strtoupper($unitMsr),
          // 'unit_value' => $stockPack,
          'stock_of' => $stockPack,
          'stock_type' => $stockType,

          // 'audited' => $audited,
          // 'published' => $published,
          'has_upc' => $hasUPC,
          'product_code' => $productCode,
          'store_item_code' => $store_item_code,
          'parent_id' => $parentId,
          'variant_id' => $variantId,
          'unit_barcode_link' => $unitBarcodeLink,
          'name' => $productname,
          'brand_id' => $brandid,
          'manufacturer' => $manufacturer,

          // 'category_group' => $categoryGroup,
          // 'department' => $department,
          // 'category' => $category,
          // 'subcategory' => $subCategory,
          // 'segment' => $segment,
          // 'subsegment' => $subSegment,

          'vat' => $vat,
          'cost' => $cost,
          'markup' => $markup,
          'autoprice' => $autoprice,
          'price' => $price,
          'quantity' => $quantity,
          'min_order_quantity' => $min_order_quantity,
          'stock_expiry_date' => $stock_expiry_date,
          'packing' => $stockType,
          'units_per_packing' => $stockPack,
          // 'unit' => 
          // 'unit' => $unitMsr,
          // 'unit_name' => $size,
          // 'unit_value' => $size,
          'size' => $size,
          'unit_of_measure' => $unitMsr,
          'size_description' => $sizeDescription,
          'height' => $height,
          'width' => $width,
          'depth' => $depth,
          'weight' => $stockwt,
          'colour' => $colour,
          'colour_variants' => $colourVariants,
          'size_variants' => $sizeVariants,
          'spec_sheet_url' => $specSheetUrl,
          'product_specification' => $productDesc,
          'warranty' => $warranty,
          'attributes' => $attributes,
          'base_image' => $image_file_name,
          'alternate_image_1' => $alternate_image_1,
          'alternate_image_2' => $alternate_image_2,
          'promotion_type' => $promotion_type,
          'promotion_id' => $promotion_id,
          'period_from' => $period_from,
          'period_to' => $period_to,
          'promotion_price' => $promotion_price,
          'courier_safe' => $courier_safe,
          'out_of_stock_lead_time' => $out_of_stock_lead_time,
          'product_delivery_type' => $product_delivery_type,
          'stoc_wt' => $stockwt,
          'unit_value' => $size,
          'barcode' => $barcode
        );

        $productobj = new Product;  //Create project object 
        // Barcode is exist in db if not than insert product 
        $checkproduct = Product::withoutGlobalScopes()->where('barcode', $barcode)->first();

        if ($checkproduct === null) {
          if (isset($base_image) && !empty($base_image)) {
            // $carbon = new Carbon();
            $replacePathArray = [
              // ':userId' => $user->uuid ?? '',
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
            // $arrProductRequest['parent_id'] = $masterProduct->uuid;
          }

          $this->product_add++;
          $productModel = Product::create($arrProductRequest);
          $product_uuid =  (string)$productModel->uuid;

          $promotionuuId = null;

          if ($promotion_id && $promotion_type && $period_from && $period_to && $promotion_price) {

            $promotionReqData = [
              'promotion_id' => $promotion_id,
              'promotion_type' => $promotion_type,
              'period_from' => date('Y-m-d', strtotime($period_from)),
              'period_to' => date('Y-m-d', strtotime($period_to)),
              'promotion_price' => $promotion_price,
              'current_price' => $price,
              'status' => 'active',
              'user_id' => auth()->user()->uuid,
              'product_id' => $product_uuid
            ];

            OfferDeals::create([
              'user_id' => auth()->user()->uuid,
              'title' => '',
              'start_date' => date('Y-m-d', strtotime($period_from)),
              'end_date' => date('Y-m-d', strtotime($period_to)),
              'brands_id' => $brandid,
              'categories_id' => $category,
              'products_id' => $product_uuid,
              'offer_method' => '',
              'offer_type' => $promotion_type,
              'offer_value' => $promotion_price,
              'description' => '',
              'image' => '',
              'offercode' => '',
              'status' => 'active'
            ]);

            $checkPromotion = Promotion::where('promotion_id', $promotion_id)->first();

            if ($checkPromotion) {
              $promotionuuId = $checkPromotion->uuid;
            } else {
              $promotionData = Promotion::create($promotionReqData);
              $promotionuuId = $promotionData->uuid;
            }
          }

          $inventoryReqData = [
            'stoc_vat' => $vat,
            'cost' => $cost,
            'markup' => $markup,
            'autoprice' => $autoprice,
            'single_price' => $price,
            'single' => $quantity,
            'min_order_quantity' => $min_order_quantity,
            'stock_expiry_date' => ($stock_expiry_date != null) ? date('Y-m-d', strtotime($stock_expiry_date)) : '',
            // 'audited' => $audited,
            'promotion_id' => $promotionuuId
          ];

          $productid =  (string)$productModel->uuid;
          $supplier_id = auth()->user()->uuid;
          // $supplier_id = $user->uuid ?? '';
          $inventoryModel = new SupplierItemInventory;
          $data_inventory = $inventoryModel->where('product_id', $productid)->where('store_id', $store_id)->where('user_id', $supplier_id)->select('uuid')->first();

          if ($data_inventory) {
            $inventoryModel->where('uuid', $data_inventory->uuid)->update($inventoryReqData);
          } else {
            $inventoryReqData['store_id'] = $store_id;
            $inventoryReqData['user_id'] = $supplier_id;
            $inventoryReqData['product_id'] = $productid;
            $inventoryModel->create($inventoryReqData);
          }

          // 'promotion_type' => $promotion_type,
          // 'promotion_id' => $promotion_id,
          // 'period_from' => $period_from,
          // 'period_to' => $period_to,
          // 'promotion_price' => $promotion_price,

        } else {
          if (isset($base_image) && !empty($base_image)) {
            // $carbon = new Carbon();
            $replacePathArray = [
              // ':userId' => $user->uuid ?? '',
              ':userId' => auth()->user()->uuid ?? '',
              ':mediatype' => 'product',
            ];

            $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);
            $uploadPath = public_path($uploadLocalPath);

            if (!File::isDirectory($uploadPath)) {
              File::makeDirectory($uploadPath, 0755, true, true);
            }

            $base_image = $uploadLocalPath . $base_image;
            $arrProductRequestforupdate['base_image'] = $base_image;

            $arrProductRequestforupdate['parent_id'] = $masterProduct->uuid;
          }
          $this->product_update++;
          $productModel = $productobj->withoutGlobalScopes()->where('barcode', $barcode)->update($arrProductRequestforupdate);
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
      // } else {
      //   //main outer
      //   //Add Brand
      //   $brandid = '';
      //   $data = '';
      //   if (isset($manufacturer) && !empty($manufacturer)) {
      //     // $checkbrand = Brand::whereRaw('LOWER(name)', $manufacturer)->first();
      //     $checkbrand = Brand::whereRaw('LOWER(`name`) = ? ', [strtolower($manufacturer)])->first();
      //     if ($checkbrand === null) {
      //       $data = Brand::create(['name' => $manufacturer, 'slug' => $manufacturer]);
      //       $brandid = $data->uuid;
      //     } else {
      //       $brandid = $checkbrand->uuid;
      //     }
      //   }

      //   $arrProductRequest = array(
      //     'user_id' => auth()->user()->uuid,
      //     'store_id' => $store_id,
      //     'name' => $productname,
      //     'slug' => str_slug($productname, '-'),
      //     'unit' => $unitIn,
      //     'unit_name' => strtoupper($unitMsr),
      //     // 'unit_value' => $stockPack,
      //     'stock_of' => $stockPack,
      //     'stock_type' => $stockType,

      //     'audited' => $audited,
      //     'published' => $published,
      //     'has_upc' => $hasUPC,
      //     'product_code' => $productCode,
      //     'store_item_code' => $store_item_code,
      //     'parent_id' => $parentId,
      //     'variant_id' => $variantId,
      //     'unit_barcode_link' => $unitBarcodeLink,
      //     'name' => $productname,
      //     'slug' => str_slug($productname, '-'),
      //     'brand_id' => $brandid,
      //     'manufacturer' => $manufacturer,
      //     'category_group' => $categoryGroup,
      //     'department' => $department,
      //     'category' => $category,
      //     'subcategory' => $subCategory,
      //     'segment' => $segment,
      //     'subsegment' => $subSegment,
      //     'vat' => $vat,
      //     'cost' => $cost,
      //     'markup' => $markup,
      //     'autoprice' => $autoprice,
      //     'price' => $price,
      //     'quantity' => $quantity,
      //     'min_order_quantity' => $min_order_quantity,
      //     'stock_expiry_date' => $stock_expiry_date,
      //     'packing' => $stockType,
      //     'units_per_packing' => $stockPack,
      //     'size' => $size,
      //     'unit_of_measure' => $unitMsr,
      //     'size_description' => $sizeDescription,
      //     'height' => $height,
      //     'width' => $width,
      //     'depth' => $depth,
      //     'weight' => $stockwt,
      //     'colour' => $colour,
      //     'colour_variants' => $colourVariants,
      //     'size_variants' => $sizeVariants,
      //     'spec_sheet_url' => $specSheetUrl,
      //     'product_specification' => $productDesc,
      //     'warranty' => $warranty,
      //     'attributes' => $attributes,
      //     'base_image' => $image_file_name,
      //     'alternate_image_1' => $alternate_image_1,
      //     'alternate_image_2' => $alternate_image_2,
      //     'promotion_type' => $promotion_type,
      //     'promotion_id' => $promotion_id,
      //     'period_from' => $period_from,
      //     'period_to' => $period_to,
      //     'promotion_price' => $promotion_price,
      //     'courier_safe' => $courier_safe,
      //     'out_of_stock_lead_time' => $out_of_stock_lead_time,
      //     'product_delivery_type' => $product_delivery_type,
      //     'search_keyword' => $search_keyword,
      //     'status' => 'ACTIVE',
      //     'barcode' => $barcode,
      //     'base_price' => $price
      //   );

      //   $arrMasterProductRequest = $arrProductRequest;


      //   $productobj = new Product;  //Create project object 
      //   // Barcode is exist in db if not than insert product 
      //   $checkproduct = Product::withoutGlobalScopes()->where('barcode', $barcode)->first();
      //   if ($checkproduct === null) {
      //     if (isset($base_image) && !empty($base_image)) {
      //       $carbon = new Carbon();
      //       $replacePathArray = [
      //         // ':userId' => $user->uuid ?? '',
      //         ':userId' => auth()->user()->uuid ?? '',
      //         ':mediatype' => 'product',
      //       ];

      //       $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);
      //       $uploadPath = public_path($uploadLocalPath);

      //       if (!File::isDirectory($uploadPath)) {
      //         File::makeDirectory($uploadPath, 0755, true, true);
      //       }

      //       $base_image = $uploadLocalPath . $base_image;
      //       $arrMasterProductRequest['base_image'] = $base_image;
      //       // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
      //       $arrMasterProductRequest['default_stock_type'] = 0;
      //       $arrMasterProductRequest['parent_id'] = 0;
      //     }

      //     $this->product_add++;
      //     $masterProductModel = Product::create($arrMasterProductRequest);
      //     $arrProductRequest['base_image'] = $base_image;
      //     // $arrProductRequest['default_stock_type'] = $defaultStockType;
      //     $arrProductRequest['default_stock_type'] = 1;
      //     $arrProductRequest['parent_id'] = $masterProductModel->uuid;
      //     $productModel = Product::create($arrProductRequest);
      //     $product_uuid = $masterProductModel->uuid;
      //     $product_uuid1 =  (string)$productModel->uuid;

      //     $promotionuuId = null;
      //     if ($promotion_id && $promotion_type && $period_from && $period_to && $promotion_price) {
      //       $promotionReqData = [
      //         'promotion_id' => $promotion_id,
      //         'promotion_type' => $promotion_type,
      //         'period_from' => date('Y-m-d', strtotime($period_from)),
      //         'period_to' => date('Y-m-d', strtotime($period_to)),
      //         'promotion_price' => $promotion_price,
      //       ];

      //       $checkPromotion = Promotion::where('promotion_id', $promotion_id)->first();

      //       if ($checkPromotion) {
      //         $promotionuuId = $checkPromotion->uuid;
      //       } else {
      //         $promotionData = Promotion::create($promotionReqData);
      //         $promotionuuId = $promotionData->uuid;
      //       }
      //     }

      //     $inventoryReqData = [
      //       'stoc_vat' => $vat,
      //       'cost' => $cost,
      //       'markup' => $markup,
      //       'autoprice' => $autoprice,
      //       'single_price' => $price,
      //       'single' => $quantity,
      //       'min_order_quantity' => $min_order_quantity,
      //       'stock_expiry_date' => date('Y-m-d', strtotime($stock_expiry_date)),
      //       // 'audited' => $audited,
      //       'promotion_id' => $promotionuuId
      //     ];

      //     // $uuid = (string)$product_uuid;
      //     // dd($uuid,$product_uuid);
      //     $productid = (string)$productModel->uuid;
      //     $supplier_id = auth()->user()->uuid;
      //     // $supplier_id = $user->uuid ?? '';
      //     $inventoryModel = new SupplierItemInventory;
      //     $data_inventory = $inventoryModel->where('product_id', $productid)->where('store_id', $store_id)->where('user_id', $supplier_id)->select('uuid')->first();
      //     if ($data_inventory) {
      //       $inventoryModel->where('uuid', $data_inventory->uuid)->update($inventoryReqData);
      //     } else {
      //       $inventoryReqData['store_id'] = $store_id;
      //       $inventoryReqData['user_id'] = $supplier_id;
      //       $inventoryReqData['product_id'] = $productid;
      //       // dd($inventoryReqData);
      //       $inventoryModel->create($inventoryReqData);
      //     }

      //     ProductCategory::where('product_id', $product_uuid)->delete();
      //     $par_cat = array();
      //     if (isset($category) && !empty($category)) {
      //       $cat_uuid = $this->checkCategory($category, $product_uuid);
      //       $par_cat = $this->getParentCategory($cat_uuid);
      //       array_push($par_cat,  $cat_uuid);
      //       foreach ($par_cat as $key => $cat_val) {
      //         # code...
      //         ProductCategory::create(['product_id' => $product_uuid, 'category_id' => $cat_val]);
      //       }
      //     }

      //     if (isset($product_uuid1) && !empty($product_uuid1)) {
      //       ProductCategory::where('product_id', $product_uuid1)->delete();
      //       $par_cat = array();
      //       if (isset($category) && !empty($category)) {
      //         $cat_uuid = $this->checkCategory($category, $product_uuid1);
      //         $par_cat = $this->getParentCategory($cat_uuid);
      //         array_push($par_cat,  $cat_uuid);
      //         foreach ($par_cat as $key => $cat_val) {
      //           # code...
      //           ProductCategory::create(['product_id' => $product_uuid1, 'category_id' => $cat_val]);
      //         }
      //       }
      //     }
      //   } else {
      //     if (isset($base_image) && !empty($base_image)) {
      //       $carbon = new Carbon();
      //       $replacePathArray = [
      //         // ':userId' => $user->uuid ?? '',
      //         ':userId' => auth()->user()->uuid ?? '',
      //         ':mediatype' => 'product',
      //       ];

      //       $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);
      //       $uploadPath = public_path($uploadLocalPath);

      //       if (!File::isDirectory($uploadPath)) {
      //         File::makeDirectory($uploadPath, 0755, true, true);
      //       }

      //       $base_image = $uploadLocalPath . $base_image;
      //       $arrMasterProductRequest['base_image'] = $base_image;
      //       // $arrMasterProductRequest['default_stock_type'] = $defaultStockType;
      //       $arrMasterProductRequest['default_stock_type'] = 0;
      //       $arrMasterProductRequest['parent_id'] = 0;
      //     }
      //     $this->product_update++;
      //     $productModel = $productobj->withoutGlobalScopes()->where('barcode', $barcode)->update($arrMasterProductRequest);
      //     $product_uuid = $checkproduct->uuid;

      //     ProductCategory::where('product_id', $product_uuid)->delete();
      //     $par_cat = array();
      //     if (isset($category) && !empty($category)) {
      //       $cat_uuid = $this->checkCategory($category, $product_uuid);
      //       $par_cat = $this->getParentCategory($cat_uuid);
      //       array_push($par_cat,  $cat_uuid);
      //       foreach ($par_cat as $key => $cat_val) {
      //         # code...
      //         ProductCategory::create(['product_id' => $product_uuid, 'category_id' => $cat_val]);
      //       }
      //     }
      //   }
      // }
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
