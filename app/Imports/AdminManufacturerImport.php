<?php

namespace App\Imports;
use App\Models\Brand;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class AdminManufacturerImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $manufacturer_add = 0;
    private $manufacturer_update = 0;
    public $uploadPath = '/uploads/media/:mediatype/:userId/';

    public function model(array $row)
    {
        // Define varible of  csv colonm
        $manufacturer = trim($row['brand_name']);
        // $manufacturer = trim($row['manufacturer_name']);
        if (isset($manufacturer) && !empty($manufacturer)) {
        
          $maf_icon_file = trim($row['brand_icon_file']);
          $maf_desc = trim($row['brand_desc']);
          $maf_meta_title = trim($row['brand_meta_title']);
          $maf_meta_description = trim($row['brand_meta_description']);
          $maf_meta_keywords = trim($row['brand_meta_keywords']);
        
           $arrMafInput = array('name' => $manufacturer,
                                'slug' => str_replace(' ', '-',strtolower($manufacturer)), 
                                'icon_file' => $maf_icon_file,
                                'description' => $maf_desc,
                                'meta_title' => $maf_meta_title,
                                'meta_description' => $maf_meta_description,
                                'meta_keywords' => $maf_meta_keywords,
                                'status' => 'Active',
                              );
          // Brand is exist in db if not than insert Brand 
          $checkMaf = Brand::whereRaw('LOWER(name) = ?',[strtolower($manufacturer)])->first(); 

          if ($checkMaf === null) {

            if (isset($maf_icon_file) && !empty($maf_icon_file)) {
             
              $replacePathArray = [
                ':userId' => auth()->user()->uuid ?? '',
                ':mediatype' => 'manufacturer',
              ];
              $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);

              $uploadPath = public_path($uploadLocalPath);
             
              if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true, true);
              }
              $maf_icon_file = $uploadLocalPath . $maf_icon_file;
              $arrMafInput['icon_file'] = $maf_icon_file; 

            }

            $this->manufacturer_add++;
            Brand::create($arrMafInput);
          } else {
            $this->manufacturer_update++;
            $brandobj = New Brand;
            $brandobj->update($arrMafInput);
          }
        }           
    }

    public function getManufacturerAddCount(): int
    {
        return $this->manufacturer_add;
    }

    public function getManufacturerUpdateCount(): int
    {
        return $this->manufacturer_update;
    }
}