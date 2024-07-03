<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockProductsCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_products_cards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'product_id',
        'stock_id',
        'quantity',
        'stock_before',
        'stock_after',
        'uom',
        'description',
        'is_executed',
        'executed_at',
        'type',
        'exec_info',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function stock(){
        return $this->belongsTo(StockProducts::class, 'stock_id');
    }
}
