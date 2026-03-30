<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'receipt_number',
        'quantity',
        'unit_price',
        'amount',
        'payment_status',
        'service_type',
        'notes',
        'refill_date',
    ];

    protected $casts = [
        'refill_date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
