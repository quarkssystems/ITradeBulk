<?php

namespace App\Models;

use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    use BaseModelSupport;
    protected $perPage = 20;

    protected $fillable = [
        'user_id',
        'uuid',
        'log'
    ];

    protected $casts = [
        'uuid' => 'string',
        'user_id' => 'string',
        'log' => 'string'
    ];

    protected static function boot()
    {
        parent::boot();
        /**
         * @see BaseModelSupport::addUUID()
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
