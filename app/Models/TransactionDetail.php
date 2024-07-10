<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "transaction_details";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_code',
        'item_name',
        'item_quantity',
        'item_quantity_unit',
        'item_price',
        'item_total_price',
        'disc_percent',
        'product_id',
        'stock_card_id',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_item_price',
        'formatted_item_total_price',
    ];

    /**
     * Get the formatted item_price attribute.
     *
     * @return string
     */
    public function getFormattedItemPriceAttribute()
    { 
        return number_format($this->item_price, 0, ',', '.');
    }

    /**
     * Get the formatted item_total_price attribute.
     *
     * @return string
     */
    public function getFormattedItemTotalPriceAttribute()
    {
        return number_format($this->item_total_price, 0, ',', '.');
    }
}
