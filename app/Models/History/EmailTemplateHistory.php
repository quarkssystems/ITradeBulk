<?php

namespace App\Models\History;

use App\Models\EmailTemplate;
use App\Models\Support\HistoryModelSupport;

class EmailTemplateHistory extends EmailTemplate
{
    use HistoryModelSupport;
    protected $table = "email_template_histories";
}
