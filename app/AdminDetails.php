<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Support\BaseModelSupport;

class AdminDetails extends Model
{
    use BaseModelSupport;

    protected $fillable = [
        'address',
        'icon',
    ];
}
