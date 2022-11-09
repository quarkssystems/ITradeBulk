<?php

namespace App\Models\History;

use App\Models\Banner;
use App\Models\Support\HistoryModelSupport;

class BannerHistory extends Banner
{
    use HistoryModelSupport;
    protected $table = "banner_histories";
}
