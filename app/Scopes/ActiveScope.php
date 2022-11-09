<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('published', '=','1');
        
        // if(\Auth::user()->role == 'ADMIN'){
        //    $data = \App\AdminQuickView::where('user_id',\Auth::user()->uuid)->first();
        // // dd($data->admin_fields);
        //    if($data->admin_fields == 0){
        //         $builder->select('audited');

        //    }
        // }
        // dd(\Auth::user()->role);
        // ->where('parent_id','!=','0');
    }
}