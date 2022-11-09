<?php

namespace App\Models\History;

use App\Models\ProductUnit;
use App\Models\Support\HistoryModelSupport;
use Illuminate\Database\Eloquent\Model;

class ProductUnitHistory extends ProductUnit
{
    use HistoryModelSupport;
    protected $table = "product_unit_histories";
}
