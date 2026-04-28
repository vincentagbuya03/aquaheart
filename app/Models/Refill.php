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
        'paid_amount' => 'decimal:2',
        'partial_amount' => 'decimal:2',
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

    public function getTotalAmountAttribute(): float
    {
        return (float) (($this->quantity ?? 0) * ($this->unit_price ?? 0));
    }

    public function getComputedPaymentStatusAttribute(): string
    {
        $total = $this->total_amount;
        $paid = $this->paid_amount !== null ? (float) $this->paid_amount : null;
        $balance = $this->partial_amount !== null ? (float) $this->partial_amount : null;
        $epsilon = 0.00001;

        if ($paid !== null || $balance !== null) {
            $paidValue = max(0, $paid ?? 0);
            $balanceValue = max(0, $balance ?? max(0, $total - $paidValue));

            if ($paidValue > $epsilon && $balanceValue > $epsilon) {
                return 'partial';
            }

            if ($balanceValue <= $epsilon && $paidValue + $epsilon >= $total) {
                return 'paid';
            }

            if ($paidValue <= $epsilon && $balanceValue > $epsilon) {
                return 'unpaid';
            }
        }

        return strtolower($this->payment_status ?? $this->paymentStatus?->code ?? 'paid');
    }

}
