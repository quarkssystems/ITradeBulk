<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DispatchedDocument extends Model
{
    protected $table = 'dispatched_documents';
    protected $fillable = ['order_id', 'product_id', 'basket_id', 'basket_products_id', 'single_qty', 'old_qnty', 'product_price', 'cart_amount', 'shipment_amount', 'discount_amount', 'tax_amount', 'final_total', 'old_final_total', 'status', 'color', 'size', 'offer_price', 'offer_id'];

    public function products()

    {

        return $this->hasMany(\App\Models\BasketProducts::class, 'basket_id', 'basket_id');
    }
}
