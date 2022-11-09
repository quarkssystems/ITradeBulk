<?php

namespace App\Models\History;

use App\Models\LogisticCompany;
use App\Models\Support\HistoryModelSupport;

class LogisticCompanysHistory extends LogisticCompany
{
    use HistoryModelSupport;
    protected $table = "logistic_companies_histories";
}
