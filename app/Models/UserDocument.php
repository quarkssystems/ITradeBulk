<?php

namespace App\Models;

use App\Models\History\UserDocumentHistory;
use App\Models\Support\BaseModelSupport;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDocument extends Model
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
        'title',
        'document_file_one',
        'document_file_two',
        'details',
        'approved',
        'comment',
        'approved_at',
        'user_id',
    ];

    /**
     * @var array
     */
    public $casts = [
        'uuid' => 'string',
        'title' => 'string',
        'document_file_one' => 'string',
        'document_file_two' => 'string',
        'details' => 'string',
        'approved' => 'string',
        'comment' => 'string',
        'approved_at' => 'datetime',
        'user_id' => 'string',
    ];

    public $userId = null;

    public $vendorDocuments = [
        [
            'title' => 'Proof of address',
            'required' => 'NO',
        ],
        [
            'title' => 'Proof of company registration or CK documents',
            'required' => 'NO',
        ],
        [
            'title' => 'Certified Copy of identity document (Shareholders, Directors, Members)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original Tax clearance certificate (Valid)',
            'required' => 'NO',
        ],
        [
            'title' => 'VAT registration document (VAT 103)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original/Certified BBBEE certificate (SANAS approved Agency)',
            'required' => 'NO',
        ]
    ];

    public $supplierDocuments = [
        [
            'title' => 'Proof of address',
            'required' => 'NO',
        ],
        [
            'title' => 'Proof of company registration or CK documents',
            'required' => 'NO',
        ],
        [
            'title' => 'Bank confirmation letter',
            'required' => 'NO',
        ],
        [
            'title' => 'Certified Copy of identity document (Shareholders, Directors, Members)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original Tax clearance certificate (Valid)',
            'required' => 'NO',
        ],
        [
            'title' => 'VAT registration document (VAT 103)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original/Certified BBBEE certificate (SANAS approved Agency)',
            'required' => 'NO',
        ]
    ];


      public $companyDocuments = [
        [
            'title' => 'Proof of address',
            'required' => 'NO',
        ],
        [
            'title' => 'Proof of company registration or CK documents',
            'required' => 'NO',
        ],
        [
            'title' => 'Certified Copy of identity document (Shareholders, Directors, Members)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original Tax clearance certificate (Valid)',
            'required' => 'NO',
        ],
        [
            'title' => 'VAT registration document (VAT 103)',
            'required' => 'NO',
        ],
        [
            'title' => 'Original/Certified BBBEE certificate (SANAS approved Agency)',
            'required' => 'NO',
        ],
        [
            'title' => 'Vehicles registration',
            'required' => 'NO',
        ],
        [
            'title' => 'Vehicle insurance',
            'required' => 'NO',
        ],
        [
            'title' => "Driver's Licence",
            'required' => 'NO',
        ],
        [
            'title' => 'Road worthy certificate',
            'required' => 'NO',
        ]
    ];
    public $logisticsDocuments = [
        [
            'title' => 'Proof of address',
            'required' => 'NO',
        ],
        [
            'title' => 'Driving licence',
            'required' => 'NO',
        ],
        [
            'title' => 'PDP permit',
            'required' => 'NO',
        ],
        [
            'title' => 'Licence disk of vehicle',
            'required' => 'NO',
        ],
        [
            'title' => 'Vehicle registration',
            'required' => 'NO',
        ],
        [
            'title' => 'GIT insurance',
            'required' => 'NO',
        ],
        [
            'title' => 'Road worthy certificate',
            'required' => 'NO',
        ]
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
         */
        static::creating(function ($model) {
            $model->addUUID($model);
        });

        /**
         * @see BaseModelSupport::createUserLog()
         */
        static::created(function ($model) {
            $model->createUserLog($model, 'userActivity.userDocument|created');
            $model->generateHistory($model);
        });

        /**
         * @see BaseModelSupport::generateHistory()
         * @see BaseModelSupport::createUserLog()
         */
        static::updated(function($model){
            if(!auth()->guest())
            {
                $model->generateHistory($model);
                $model->createUserLog($model, 'userActivity.userDocument|updated');
            }
        });

        static::deleting(function ($model){
            $model->deleteHistory($model);
        });
    }

    /*==================================================*/
    /* Getter and Setter */
    /*==================================================*/

    /**
     * @return null
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param null $userId
     */
    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    public function getVendorDocuments(): array
    {
        return $this->vendorDocuments;
    }

    /**
     * @param array $vendorDocuments
     */
    public function setVendorDocuments(array $vendorDocuments): void
    {
        $this->vendorDocuments = $vendorDocuments;
    }

    /**
     * @return array
     */
    public function getSupplierDocuments(): array
    {
        return $this->supplierDocuments;
    }

    /**
     * @param array $supplierDocuments
     */
    public function setSupplierDocuments(array $supplierDocuments): void
    {
        $this->supplierDocuments = $supplierDocuments;
    }

    /**
     * @return array
     */
    public function getLogisticsDocuments(): array
    {
        return $this->logisticsDocuments;
    }

      /**
     * @return array
     */
    public function getCompanyDocuments(): array
    {
        return $this->companyDocuments;
    }

    /**
     * @param array $logisticsDocuments
     */
    public function setLogisticsDocuments(array $logisticsDocuments): void
    {
        $this->logisticsDocuments = $logisticsDocuments;
    }

    /*==================================================*/
    /* Relational Model */
    /*==================================================*/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history() : HasMany
    {
        return $this->hasMany(UserDocumentHistory::class, "history_of", 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, "owner_user_id", 'uuid');
    }

    /*==================================================*/
    /* Local Scopes */
    /*==================================================*/

    public function scopeOfUser($query)
    {
        return $query->where('user_id', $this->userId);
    }

    public function scopeOfTitle($query, $title)
    {
        return $query->ofUser()->where('title', $title);
    }

    /*==================================================*/
    /* custom Method */
    /*==================================================*/

    public function getDocumentStatus()
    {
        $document_count = 0;
        switch (auth()->user()->role)
            {
               
                case "SUPPLIER";
                    $document_count = count($this->getSupplierDocuments());
                    break;

                case "VENDOR";
                     $document_count = count($this->getVendorDocuments());
                    break;
                case "COMPANY";
                     $document_count = count($this->getcompanyDocuments());
                    break;
                 case "DRIVER";
                     $document_count = count($this->getLogisticsDocuments());
                    break;       
                case "ADMIN";
                     return 1;
                    break;                   
            }        


         $user_id = auth()->check() ? auth()->user()->uuid : null;
         $count_approve = $this->where('user_id', $user_id)->where('approved' , 'YES')->count() ? $this->where('user_id', $user_id)->where('approved' , 'YES')->count() : 0 ;

         $approved = 0;
         if($count_approve ==  $document_count){ 
            $approved = 1;

          }


        return $approved;
                 
    }
     public function checkApproved()
    {
        return $this->where('approved' , 'YES');
    }

    
    public function getDocumentStatusAPI($user_id,$role)
    {

        switch ($role)
            {
               
                case "SUPPLIER";
                    $document_count = count($this->getSupplierDocuments());
                    break;

                case "VENDOR";
                     $document_count = count($this->getVendorDocuments());
                    break;
                case "COMPANY";
                     $document_count = count($this->getcompanyDocuments());
                    break;
                 case "DRIVER";
                     $document_count = count($this->getLogisticsDocuments());
                    break;       
                case "ADMIN";
                     return 1;
                    break;                   
            }        

         $user_id = $user_id ? $user_id : null;
         $count_approve = $this->where('user_id', $user_id)->where('approved' , 'YES')->count() ? $this->where('user_id', $user_id)->where('approved' , 'YES')->count() : 0 ;
         
         $approved = 0;
         if($count_approve ==  $document_count){ 
            $approved = 1;

          }


        return $approved;
                 
    }

}
