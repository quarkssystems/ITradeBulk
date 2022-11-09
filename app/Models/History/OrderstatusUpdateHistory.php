<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\OrderstatusUpdate;
use Illuminate\Database\Eloquent\Model;

class OrderstatusUpdateHistory extends OrderstatusUpdate
{
    use HistoryModelSupport;
    protected $table = "orderstatus_update_histories";
}
