<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\Tax;
use Illuminate\Database\Eloquent\Model;

class TaxHistory extends Tax
{
    use HistoryModelSupport;
    protected $table = "tax_histories";
}
