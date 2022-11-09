<?php



namespace App\Models;



use App\Models\History\TeamHistory;

use App\Models\Support\BaseModelSupport;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\SoftDeletes;



class Team extends Model

{

    use BaseModelSupport;

    use SoftDeletes;



    protected $perPage = 20;

    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $table = 'team';

    protected $fillable = [

        'uuid',

        'first_name',

        'last_name',

        'designation',

        'description',

        'coloured_image',

        'black_white_image',

        'status'

    ];



    /**

     * @var array

     */

    public $casts = [

        'uuid' => 'string',

        'first_name' => 'string',

        'last_name' => 'string',

        'designation' => 'string',

        'description' => 'string',

        'coloured_image' => 'string',

        'black_white_image' => 'string',

        'status' => 'string',

    ];



    // protected $appends = [

    //     'grid_image',

    // ];



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

            // $model->checkSlug($model);

        });



        /**

         * @see BaseModelSupport::createUserActivity()

         */

        static::created(function ($model) {

            $model->createUserLog($model, 'userActivity.team|created');

            $model->generateHistory($model);

        });



        static::updating(function ($model) {

            // $model->checkSlugUpdate($model);

        });



        /**

         * @see BaseModelSupport::generateHistory()

         * @see BaseModelSupport::createUserActivity()

         */

        static::updated(function($model){

            if(!auth()->guest())

            {

                $model->generateHistory($model);

                $model->createUserLog($model, 'userActivity.team|updated');

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

        return $this->hasMany(TeamHistory::class, "history_of", 'uuid');

    }





    /*==================================================*/

    /* Custom Methods */

    /*==================================================*/



    public function getDropDown($except = null)

    {

        return $this->where('uuid', '!=', $except)->orderBy('name')->pluck('name', 'uuid');

    }





    public function getGridImageAttribute()

    {

        if(is_null($this->icon_file))

        {

            return NULL;

        }



        return "<a href='{$this->icon_file}' data-fancybox='gallery' title='{$this->name}' class='grid-thumb-image'><img src='{$this->icon_file}' style='max-width: 100px' /></a>";

    }

}

