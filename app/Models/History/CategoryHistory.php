<?php

namespace App\Models\History;

use App\Models\Category;
use App\Models\Support\HistoryModelSupport;

class CategoryHistory extends Category
{
    use HistoryModelSupport;
    protected $table = "category_histories";
}
