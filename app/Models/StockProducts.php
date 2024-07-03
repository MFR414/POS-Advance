<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockProducts extends Model
{
    use HasFactory;

    protected $table = 'stock_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'product_id',
        'quantity',
        'uom'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}
