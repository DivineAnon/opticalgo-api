<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    // Mutators
    public function setPaymentNameAttribute($value) {
        $this->attributes['payment_name'] = strtolower($value);
    }

    // Accessors
    public function getPaymentNameAttribute($value) {
        return ucwords($value);
    }
}
