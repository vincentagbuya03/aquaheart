<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    public $timestamps = false;
    protected $table = 'service_types';
    protected $fillable = ['id', 'name'];

    public function refills()
    {
        return $this->hasMany(Refill::class);
    }
}
