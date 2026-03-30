<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'loyalty_points'];

    protected $casts = [
        'loyalty_points' => 'integer',
    ];

    public function refills()
    {
        return $this->hasMany(Refill::class);
    }
}
