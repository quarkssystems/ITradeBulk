<?php
namespace App\Models\Support;
use App\User;

/**
 * Created by PhpStorm.
 * User: mayank
 * Date: 21/11/18
 * Time: 12:08 PM
 */

trait HistoryModelSupport
{
    public $fillableAppend = ['history_of', 'updated_by', 'update_note'];

    public function getFillable()
    {
        return array_merge($this->fillable, $this->fillableAppend);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'uuid');
    }

    public function generateHistory($model): void
    {
    }

    public function createUserLog($model, $trans): void
    {
    }

    public function deleteHistory($model) : void
    {

    }

    public function checkSlug($model) {}
    public function checkSlugUpdate($model) {}
}