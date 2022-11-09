<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class AdminHierarchyImport implements ToModel, WithHeadingRow
{
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  private $product_add = 0;
  private $product_update = 0;
  public static $error = [];
  public $uploadPath = '/uploads/media/:mediatype/:userId/';

  public function model(array $row)
  {
    $barcode = trim($row['Barcode']);
    if (isset($barcode) && !empty($barcode)) {
      // $category_group = trim($row['Category Group']);
      $department = trim($row['Department']);
      $category = trim($row['Category']);
      $sub_category = trim($row['Sub Category']);
      $segment = trim($row['Segment']);
      $sub_segment = trim($row['Sub Segment']);

      if (isset($category) && !empty($category)) {
        $this->checkCategory($category, '');
      }

      if ($department != null) {
        $departmentNew = $department;
      } else {
        $departmentNew = '';
      }
      if ($category != null) {
        $categoryNew = '_' . $category;
      } else {
        $categoryNew = '';
      }
      if ($sub_category != null) {
        $sub_categoryNew = '_' . $sub_category;
      } else {
        $sub_categoryNew = '';
      }
      if ($segment != null) {
        $segmentNew = '_' . $segment;
      } else {
        $segmentNew = '';
      }
      if ($sub_segment != null) {
        $sub_segmentNew = '_' . $sub_segment;
      } else {
        $sub_segmentNew = '';
      }

      $category_group = $departmentNew . '' . $categoryNew . '' . $sub_categoryNew . '' . $segmentNew . '' . $sub_segmentNew;
      $checkAvailableorNot = Product::withoutGlobalScopes()->where('barcode', $barcode)->first();

      $arrHierarchyRequest = array(
        'category_group' => $category_group,
        'department' => $department,
        'category' => $category,
        'subcategory' => $sub_category,
        'segment' => $segment,
        'subsegment' => $sub_segment,
      );

      if ($checkAvailableorNot != null) {
        // $this->product_add++;
        $this->product_update++;
        Product::withoutGlobalScopes()->where('barcode', $barcode)->update($arrHierarchyRequest);
      }


      // $category = Category::where('name', $category)->select('name')->first();
      // if ($category != null) {
      //   Category::create([]);
      // }
      // dd($category, $category);
      // if ($checkAvailableorNot) {
      //   self::$error[] = $barcode;
      // } else {


      //   if ($checkAvailableorNot === null) {
      //   }
      // }
      // dd($row, $barcode);
    }
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
