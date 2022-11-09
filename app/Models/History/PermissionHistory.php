<?php

namespace App\Models\History;

use App\Models\Permission;
use App\Models\Support\HistoryModelSupport;

class PermissionHistory extends Permission
{
    use HistoryModelSupport;
    protected $table = "permission_histories";
}
