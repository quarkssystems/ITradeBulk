<?php

namespace App\Models\History;

use App\Models\OfferDeals;
use App\Models\Support\HistoryModelSupport;

class OfferDealsHistory extends OfferDeals
{
    //
    use HistoryModelSupport;
    protected $table = "offer_histories";
}
