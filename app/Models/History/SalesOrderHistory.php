<?php

namespace App\Models\History;

use App\Models\SalesOrder;
use App\Models\Support\HistoryModelSupport;
use Illuminate\Database\Eloquent\Model;

class SalesOrderHistory extends SalesOrder
{
    use HistoryModelSupport;
    protected $table = "sales_order_histories";
}
