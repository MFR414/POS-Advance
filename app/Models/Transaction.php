<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'transaction';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'sales_code',
        'customer_name',
        'customer_address',
        'item_total',
        'discount_percentage',
        'discount_total',
        'tax_percentage',
        'tax_total',
        'other_fees',
        'subtotal',
        'final_total',
        'final_total_after_additional',
        'dp_po',
        'cash',
        'credit',
        'debit_card',
        'credit_card',
        'return',
        'transaction_status',
        'creator',
        'invoice_filename'
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_transaction_date',
        'formatted_subtotal',
        'formatted_final_total',
        'formatted_discount_total',
        'formatted_final_total_after_additional',
        'formatted_tax_total',
        'formatted_dp_po',
        'formatted_other_fees',
        'formatted_cash',
        'formatted_return'
    ];

    /**
     * Get the formatted subtotal attribute.
     *
     * @return string
     */
    public function getFormattedSubtotalAttribute()
    { 
        return number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get the formatted final_total attribute.
     *
     * @return string
     */
    public function getFormattedFinalTotalAttribute()
    { 
        return number_format($this->final_total, 0, ',', '.');
    }

    /**
     * Get the formatted tax_total attribute.
     *
     * @return string
     */
    public function getFormattedTaxTotalAttribute()
    { 
        return number_format($this->tax_total, 0, ',', '.');
    }

    /**
     * Get the formatted final_total_after_additional attribute.
     *
     * @return string
     */
    public function getFormattedFinalTotalAfterAdditionalAttribute()
    { 
        return number_format($this->final_total_after_additional, 0, ',', '.');
    }

     /**
     * Get the formatted discount_total attribute.
     *
     * @return string
     */
    public function getFormattedDiscountTotalAttribute()
    { 
        return number_format($this->discount_total, 0, ',', '.');
    }

     /**
     * Get the formatted cash attribute.
     *
     * @return string
     */
    public function getFormattedCashAttribute()
    { 
        return number_format($this->cash, 0, ',', '.');
    }

     /**
     * Get the formatted other_fees attribute.
     *
     * @return string
     */
    public function getFormattedOtherFeesAttribute()
    { 
        return number_format($this->other_fees, 0, ',', '.');
    }

     /**
     * Get the formatted final_total attribute.
     *
     * @return string
     */
    public function getFormattedDpPoAttribute()
    { 
        return number_format($this->dp_po, 0, ',', '.');
    }

     /**
     * Get the formatted return attribute.
     *
     * @return string
     */
    public function getFormattedReturnAttribute()
    { 
        return number_format($this->return, 0, ',', '.');
    }

    /**
     * Get the formatted transaction_date attribute.
     *
     * @return string
     */
    public function getFormattedTransactionDateAttribute()
    {
        // Format transaction_date attribute to m/d/Y
        $formatted_date = Carbon::parse($this->transaction_date)->format('d/m/Y');
        // return the formatted transaction_date into appends field
        return $formatted_date;
    }

    /**
     * Get the sales that owns the Transaction
     */
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_number', 'transaction_number');
    }
}
