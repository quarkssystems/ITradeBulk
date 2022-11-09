<?php

namespace App\Models;

//use App\Models\History\ProductHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use BaseModelSupport;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $perPage = 20;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'status'
    ];

    /**
     * @var array
     */
    public function getDropDown($except = null)
    {
        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');
    }
}
