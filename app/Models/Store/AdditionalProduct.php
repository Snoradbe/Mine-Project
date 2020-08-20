<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property Product $product
 * @property Product $additional
 * @property int $amount
 */
class AdditionalProduct extends Model
{
    protected $table = 'store_additional_products';

    protected $fillable = [
        'product_id', 'additional_id', 'amount'
    ];

    protected $with = [
        'additional'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function additional()
    {
        return $this->belongsTo(Product::class, 'additional_id');
    }
}
