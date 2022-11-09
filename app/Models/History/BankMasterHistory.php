<?php

namespace App\Models\History;

use App\Models\BankMaster;
use App\Models\Support\HistoryModelSupport;
use Illuminate\Database\Eloquent\Model;

class BankMasterHistory extends BankMaster
{
    use HistoryModelSupport;
    protected $table = "bank_master_histories";
}
