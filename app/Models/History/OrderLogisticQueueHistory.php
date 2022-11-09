<?php

namespace App\Models\History;

use App\Models\OrderLogisticQueue;
use App\Models\Support\HistoryModelSupport;

class OrderLogisticQueueHistory extends OrderLogisticQueue
{
    use HistoryModelSupport;
    protected $table = "order_logistic_queue_histories";
}
