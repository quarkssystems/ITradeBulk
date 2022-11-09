<?php

namespace App\Models\History;

use App\Models\Shortcode;
use App\Models\Support\HistoryModelSupport;

class ShortcodeHistory extends Shortcode
{
    use HistoryModelSupport;
    protected $table = "shortcode_histories";
}
