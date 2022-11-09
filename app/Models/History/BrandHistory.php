<?php

namespace App\Models\History;

use App\Models\Brand;
use App\Models\Support\HistoryModelSupport;

class BrandHistory extends Brand
{
    use HistoryModelSupport;
    protected $table = "brand_histories";
}
