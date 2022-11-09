<?php

namespace App\Models\History;

use App\Models\CMSModule;
use App\Models\Support\HistoryModelSupport;

class CMSModuleHistory extends CMSModule
{
    use HistoryModelSupport;
    protected $table = "cmsmodule_histories";
}
