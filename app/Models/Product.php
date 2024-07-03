<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'price',
        'description',
        'uom',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_price',
    ];

    /**
     * Get the formatted subtotal attribute.
     *
     * @return string
     */
    public function getFormattedPriceAttribute()
    { 
        return number_format($this->price, 0, ',', '.');
    }

    public function stock(){
        return $this->hasOne(StockProducts::class, 'product_id');
    }
}
