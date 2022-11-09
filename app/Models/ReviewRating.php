<?php

namespace App\Models;

// use App\Models\History\CategoryHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewRating extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;

    protected $table = 'review_rating';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uuid',
        'productid',
        'title',
        'rating',
        'review',
        'status'
    ];

    public function user()
    {
        return $this->hasOne('App\User','uuid','user_uuid');
    }
}
