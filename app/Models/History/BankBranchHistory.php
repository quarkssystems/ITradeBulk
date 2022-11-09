<?php

namespace App\Models\History;

use App\Models\BankBranch;
use App\Models\Support\HistoryModelSupport;
use Illuminate\Database\Eloquent\Model;

class BankBranchHistory extends BankBranch
{
    use HistoryModelSupport;
    protected $table = "bank_branch_histories";
}
