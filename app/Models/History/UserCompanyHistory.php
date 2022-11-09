<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\UserCompany;

class UserCompanyHistory extends UserCompany
{
    use HistoryModelSupport;
    protected $table = "user_company_histories";
}
