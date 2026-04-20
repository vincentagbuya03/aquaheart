<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    public $timestamps = false;
    protected $table = 'payment_statuses';
    protected $fillable = ['id', 'name'];

    public function refills()
    {
        return $this->hasMany(Refill::class);
    }
}
