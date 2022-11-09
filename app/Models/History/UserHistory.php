<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\User;

class UserHistory extends User
{
    use HistoryModelSupport;
    protected $table = "user_histories";
}
