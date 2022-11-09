<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoType extends Model
{
    protected $fillable = ['type', 'status'];

    public function getDropDown($except = null)
    {
        return $this->where('type', '!=', $except)->where('status', '=', '1')->orderBy('type')->pluck('type', 'type');
    }
}
