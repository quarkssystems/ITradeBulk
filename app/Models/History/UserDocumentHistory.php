<?php

namespace App\Models\History;

use App\Models\Support\HistoryModelSupport;
use App\Models\UserDocument;

class UserDocumentHistory extends UserDocument
{
    use HistoryModelSupport;
    protected $table = "user_document_histories";
}
