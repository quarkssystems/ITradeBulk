<?php

namespace App\Models\History;

use App\Models\CMSBlock;
use App\Models\Support\HistoryModelSupport;

class CMSBlockHistory extends CMSBlock
{
    use HistoryModelSupport;
    protected $table = "cmsblock_histories";
}
