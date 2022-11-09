<?php

namespace App\Models\History;

use App\Models\LogisticDetails;
use App\Models\Support\HistoryModelSupport;

class LogisticDetailsHistory extends LogisticDetails
{
    use HistoryModelSupport;
    protected $table = "logistic_details_histories";
}
