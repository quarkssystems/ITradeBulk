<?php

namespace App\Models\History;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Model;
use App\Models\Support\HistoryModelSupport;

class PromotionHitory extends Promotion
{
    use HistoryModelSupport;
    protected $table = "promotions_histories";
}
