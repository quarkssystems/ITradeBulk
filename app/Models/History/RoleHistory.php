<?php

namespace App\Models\History;

use App\Models\Role;
use App\Models\Support\HistoryModelSupport;

class RoleHistory extends Role
{
    use HistoryModelSupport;
    protected $table = "role_histories";
}
