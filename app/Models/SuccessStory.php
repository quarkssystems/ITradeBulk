<?php

namespace App\Models;

// use App\Models\History\CategoryHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuccessStory extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;

    protected $table = 'success_story';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uuid',
        'title',
        'description'
    ];
}
