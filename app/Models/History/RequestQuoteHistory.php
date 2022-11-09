<?php

namespace App\Models\History;

use App\Models\RequestQuote;
use App\Models\Support\HistoryModelSupport;

class RequestQuoteHistory extends RequestQuote
{
    use HistoryModelSupport;
    protected $table = "request_quote_histories";
}
