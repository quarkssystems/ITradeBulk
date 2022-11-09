<?php
namespace App\Models\Support;
/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 21/11/18
 * Time: 12:08 PM
 */

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use NumberFormatter;

trait BaseModelSupport
{
    public $currency = 'INR';

    public $customDateFormat = 'd-m-Y';
    /**
     * Define global upload path
     *
     * @var string
     */
    public $uploadPath = '/uploads/media/:mediatype/:userId/';

    public $pdfOptions = [
        'orientation'   => 'portrait',
        'encoding'      => 'UTF-8',
        'enable_php'    => true,
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true
    ];

    public $statuses = ['ACTIVE', 'INACTIVE'];

    public $transactionsTypes = ['CREDIT', 'DEBIT'];

    /**
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return "uuid";
    }

    /**
     * Use to add uuid in every model creating method
     * @param $model
     */
    public function addUUID($model) : void
    {
        $model->uuid = Str::uuid();
    }

    public function createUserLog($model, $trans) : void
    {
        if(Auth::check())
        {
            $data = [];
            $message = trans($trans, $data);
            foreach( $model->getFillable() as $item)
            {
                $data[$item] = isset($model->$item) ? $model->$item : 'NULL';
                if(is_array($data[$item]) || is_object($data[$item]))
                {
                    $message .= " ,$item - ".json_encode($data[$item]);
                }
                else
                {
                    $message .= " ,$item - ".$data[$item];
                }

            }
            Auth::user()->logActivity($message);
        }
    }

    public function generateHistory($model) : void
    {
        if(Auth::check())
        {
            $data = [];
            foreach( $model->getFillable() as $item)
            {
                $data[$item] = isset($model->$item) ? $model->$item : NULL;
            }
            $data['updated_by'] = Auth::user()->uuid;
            $fields = [];
            if(!empty($model->getDirty()))
            {
                $updatedFields = $model->getDirty();
                foreach ($updatedFields as $fieldName => $field)
                {
                    $fields[$fieldName] = $field;
                }
            }
            $data['update_note'] = json_encode($fields);
            $logModel = $model->history();
            $logModel->create($data);
        }
    }

    public function deleteHistory($model) : void
    {
        $model->history()->delete();
    }

    public function setCreatedById($model) : void
    {
        if(Auth::check())
        {
            $model->created_by = Auth::user()->uuid;
        }
    }

    /**
     * @return array
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }

    /**
     * @param array $statuses
     */
    public function setStatuses(array $statuses): void
    {
        $this->statuses = $statuses;
    }

    public function getStatusesDropDown()
    {
        return array_combine($this->getStatuses(), $this->getStatuses());
    }


    /**
     * @return array
     */
    public function getTransactionsTypes(): array
    {
        return $this->transactionsTypes;
    }

    /**
     * @param array $statuses
     */
    public function setTransactionsTypes(array $transactionTypes): void
    {
        $this->transactionsTypes = $transactionTypes;
    }

    public function getTransactionTypesDropDown()
    {
        return array_combine($this->getTransactionsTypes(), $this->getTransactionsTypes());
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getUploadPath(): string
    {
        return $this->uploadPath;
    }

    /**
     * @param string $uploadPath
     */
    public function setUploadPath(string $uploadPath): void
    {
        $this->uploadPath = $uploadPath;
    }

    public function canDelete()
    {
        return true;
    }

    /**
     * @param $requestFile
     * @return array
     */
    public function uploadMedia(UploadedFile $requestFile, $mediaFrom='common') : array
    {
        $fileName = $requestFile->getClientOriginalName();
        $carbon = new Carbon();
        $replacePathArray = [
            ':userId' => auth()->user()->uuid ?? '',
            ':mediatype' => $mediaFrom,
        ];
        $uploadLocalPath = strtr($this->uploadPath, $replacePathArray);
        
        $uploadPath = public_path($uploadLocalPath);
        $extension = $requestFile->getClientOriginalExtension();

        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true, true);
        }
        $uploadFileName = str_slug($fileName,'-');
        $uploadFileName = $uploadFileName . '.' . $extension;
        $requestFile->move($uploadPath, $uploadFileName);

        $return = [
            'name' => $uploadFileName,
            'path' => $uploadLocalPath
        ];

        return $return;
    }

    public function quickRandom($length = 6)
    {
        return strtoupper(Str::random($length));
    }

   


    public function serialNumber($length = 6)
    {
        return $this->count() == 0 ?
            str_pad('0', $length, '0', STR_PAD_LEFT) :
            str_pad( $this->orderBy('id', 'DESC')->first(['id'])->id, $length, '0', STR_PAD_LEFT);
    }

    public function formatPrice($money, $withSymbol = true)
    {
        $len = strlen($money);
        $m = '';
        $money = strrev($money);
        for($i=0;$i<$len;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$len){
                $m .=',';
            }
            $m .=$money[$i];
        }
        return strrev($m). ($withSymbol ? ' ' . $this->currency : '');
    }

    public function spellOutNumber($number)
    {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($number);
    }

    public function checkSlug($model)
    {
        $slug = $model->slug;
        $slug = str_slug($slug);
        if($this->where('slug', $slug)->exists())
        {
            $counter = 1;
            $mainSlug = $slug;
            while($this->where('slug', $slug)->exists())
            {
                $slug = $mainSlug.'-'.$counter;
                $counter++;
            }
        }
        $model->slug = $slug;
    }

    public function checkSlugUpdate($model)
    {
        $slug = $model->slug;
        $slug = str_slug($slug);
        if($this->where('slug', $slug)->where('uuid', '!=', $model->uuid)->exists())
        {
            $counter = 1;
            $mainSlug = $slug;
            while($this->where('slug', $slug)->exists())
            {
                $slug = $mainSlug.'-'.$counter;
                $counter++;
            }
        }
        $model->slug = $slug;
    }

    public function gmToUnit($weight)
    {
        $kg = $weight / 1000;
        if($kg > 1 && $kg < 1000)
        {
            return ["weight" => $kg, "unit" => "kg"];
        }
        else if($kg > 1000)
        {
            $ton = $kg / 1000;
            return ["weight" => $ton, "unit" => "ton"];
        }
        else
        {
            return ["weight" => $weight, "unit" => "gm"];
        }
    }

    public function kgToUnit($kg)
    {
        if($kg > 1000)
        {
            $ton = $kg / 1000;
            return ["weight" => $ton, "unit" => "ton"];
        }
        else
        {
            return ["weight" => $kg, "unit" => "kg"];
        }
    }
}