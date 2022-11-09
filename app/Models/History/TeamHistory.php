<?php

namespace App\Models\History;

use App\Models\Team;
use App\Models\Support\HistoryModelSupport;

class TeamHistory extends Team
{
    use HistoryModelSupport;
    protected $table = "team_histories";
}
