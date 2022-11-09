<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\UserBankDetails;

class UserBankDetailsHistory extends UserBankDetails
{
    use HistoryModelSupport;
    protected $table = "user_bank_details_histories";
}
