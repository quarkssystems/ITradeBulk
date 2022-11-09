<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\LogisticCompanyTaxDetails;

class LogisticCompanyTaxDetailsHistory extends LogisticCompanyTaxDetails
{
    use HistoryModelSupport;
    protected $table = "logistic_company_tax_details_histories";
}
