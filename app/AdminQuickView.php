<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminQuickView extends Model
{
    protected $fillable = [
        'user_id',
        'admin_fields',
        'product_codes',
        'product_links',
        'product_description',
        'data_hierarchy',
        'variants',
        'attributes',
        'image_management',
        'promotions',
        'invoice_splitting',
        'pallet_configuration',
        'fact',
        'barcode',
        'description',
        'front_image',
    ];
}