<?php

namespace App\Models;

use App\Models\History\RoleHistory;
use App\Models\Support\BaseModelSupport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
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
        'uuid',
        'name',
        'permissions'
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'name' => 'string',
        'permissions' => 'array'
    ];

    protected $appends = [
        'permissions_name'
    ];

    /**
     * Boot Method
     * @return void
     */
    protected static function boot() : void
    {
        parent::boot();

        /**
         * @see BaseModelSupport::addUUID()
         * @see User::setUserType()
         * @see User::setClientId()
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });

        /**
         * @see BaseModelSupport::createUserActivity()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.role|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserActivity()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.role|updated');
            }
        });

        static::deleting(function ($model){
            $model->deleteHistory($model);
        });
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(RoleHistory::class, "history_of", 'uuid');
    }


    /*==================================================*/
    /* Accessor and Mutators */
    /*==================================================*/

    public function getPermissionsNameAttribute()
    {
        $permissionModule = new Permission();
        $permissions = [];
        if(!is_null($this->permissions))
        {
            foreach($this->permissions as $permission)
            {
                if($permissionModule->where('uuid', $permission)->count() > 0)
                {
                    $permissions[] = $permissionModule->where('uuid', $permission)->first(['name'])->name;
                }
            }
        }
        return $permissions;
    }

    /*==================================================*/
    /* Custom Methods */
    /*==================================================*/
}
