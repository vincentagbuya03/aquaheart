<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refill extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'customer_id',
        'product_id',
        'user_id',
        'receipt_number',
        'quantity',
        'unit_price',
        'amount',
        'payment_status',
        'payment_status_id',
        'service_type',
        'service_type_id',
        'notes',
        'refill_date',
        'paid_amount',
        'partial_amount',
    ];

    protected $casts = [
        'refill_date' => 'date',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class);
    }

    public function getPaymentStatusAttribute()
    {
        return strtolower($this->paymentStatus->name ?? 'paid');
    }
}
