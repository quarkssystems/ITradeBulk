<?php

namespace App\Imports;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class AdminCategoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $category_add = 0;
    private $category_update = 0;
    public $uploadPath = '/uploads/media/:mediatype/:userId/';

    public function model(array $row)
    {
        // Define varible of  csv colonm
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
            $checkParentCategory = Category::whereRaw('LOWER(`name`) = ? ',[strtolower($parent_category)])->first();
            
            if (isset($checkParentCategory) && !empty($checkParentCategory)) {
              $parentCategoryId = $checkParentCategory->uuid;
            }else{

                $arrpCatInput = array('name' => $parent_category,
                                'slug' => str_replace(' ', '-',strtolower($parent_category)),
                                'status' => 'Active',
                              );
                $chekParent = Category::create($arrpCatInput);

                if($chekParent){
                  $parentCategoryId = $chekParent->uuid;
                }
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
          $checkCategory = Category::whereRaw('LOWER(`name`) = ? ',[strtolower($category)])->first(); 
          if ($checkCategory === null) {

            if (isset($cat_banner_image) && !empty($cat_banner_image)) {
             
              $replacePathArray = [
                ':userId' => auth()->user()->uuid ?? '',
                ':mediatype' => 'category',
              ];
              $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);

              $uploadPath = public_path($uploadLocalPath);
             
              if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
              }
              $cat_banner_image = $uploadLocalPath . $cat_banner_image;
              $arrCatInput['banner_image_file'] = $cat_banner_image; 


            }

              if (isset($cat_thumb_image) && !empty($cat_thumb_image)) {
              $carbon = new Carbon();
              $replacePathArray = [
                ':userId' => auth()->user()->uuid ?? '',
                ':mediatype' => 'category',
              ];
              $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);

              $uploadPath = public_path($uploadLocalPath);
              

              if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
              }
              $cat_thumb_image = $uploadLocalPath . $cat_thumb_image;
              $arrCatInput['thumb_image_file'] = $cat_thumb_image; 


            }      

            $this->category_add++;
            Category::create($arrCatInput);
          } else {
            $this->category_update++;
            $category = New Category;
            $category->update($arrCatInput);
          }
        }           
    }

    public function getCategoryAddCount(): int
    {
        return $this->category_add;
    }

    public function getCategoryUpdateCount(): int
    {
        return $this->category_update;
    }
}
