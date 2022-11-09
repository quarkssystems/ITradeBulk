<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\UserTaxDetails;

class UserTaxDetailsHistory extends UserTaxDetails
{
    use HistoryModelSupport;
    protected $table = "user_tax_details_histories";
}
