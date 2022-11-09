<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;


class CategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row, Category $category)
    {
      $category = trim($row['category_name']);
      if (isset($category) && !empty($category)) {
        $parent_category = trim($row['parent_category']);
        $cat_banner_image = trim($row['cat_banner_image']);
        $cat_thumb_image = trim($row['cat_thumb_image']);
        $cat_desc = trim($row['cat_desc']);
        $cat_short_desc = trim($row['cat_short_desc']);
        $cat_meta_title = trim($row['cat_meta_title']);
        $cat_meta_description = trim($row['cat_meta_description']);
        $cat_meta_keywords = trim($row['cat_meta_keywords']);
        $parentCategoryId = null;
        if (isset($parent_category) && !empty($parent_category)) {
          $checkParentCategory = Category::where('name', $parent_category)->first();
          if (isset($checkParentCategory) && !empty($checkParentCategory)) {
            $parentCategoryId = $checkParentCategory->uuid;
          }  
        }
        $arrCatInput = array('name' => $category,
                              'slug' => str_replace(' ', '-',strtolower($category)),  
                              'parent_category_id' => $parentCategoryId,
                              'banner_image_file' => $cat_banner_image,
                              'thumb_image_file' => $cat_thumb_image,
                              'description' => $cat_desc,
                              'short_description' => $cat_short_desc,
                              'meta_title' => $cat_meta_title,
                              'meta_description' => $cat_meta_description,
                              'meta_keywords' => $cat_meta_keywords,
                              'status' => 'Active',
                            );
        // Category is exist in db if not than insert Category 
        $checkCategory = Category::where('name', $category)->first(); 
        if ($checkCategory === null) {
          $this->category_add++;
          Category::create($arrCatInput);
        } else {
          $this->category_update++;
          Category::update($arrCatInput);
        }
      }
    }
}
