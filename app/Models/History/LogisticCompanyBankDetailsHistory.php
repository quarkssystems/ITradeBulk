<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\LogisticCompanyBankDetails;

class LogisticCompanyBankDetailsHistory extends LogisticCompanyBankDetails
{
    use HistoryModelSupport;
    protected $table = "logistic_company_bank_details_histories";
}
