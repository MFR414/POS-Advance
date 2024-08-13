<?php

namespace App\Models;

use Carbon\Carbon;
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

     /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_created_at',
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function stock(){
        return $this->belongsTo(StockProducts::class, 'stock_id');
    }

    /**
     * Get the formatted transaction_date attribute.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        // Format transaction_date attribute to m/d/Y
        $formatted_date = Carbon::parse($this->created_at)->format('d/m/Y');
        // return the formatted transaction_date into appends field
        return $formatted_date;
    }
}
