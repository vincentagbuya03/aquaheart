<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'phone', 'street', 'city', 'province', 'zip_code', 'loyalty_points'];

    protected $casts = [
        'loyalty_points' => 'integer',
    ];

    public function getAddressAttribute(): ?string
    {
        $parts = array_filter([
            $this->street,
            $this->city,
            $this->province,
            $this->zip_code,
        ], fn ($value) => filled($value));

        return $parts ? implode(', ', $parts) : null;
    }

    public function refills()
    {
        return $this->hasMany(Refill::class);
    }
}
