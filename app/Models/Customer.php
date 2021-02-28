<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_code', 'customer_name', 'customer_phone',
        'customer_email', 'customer_address'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function prescriptions() {
        return $this->hasMany(Prescription::class, 'customers_id');
    }

    // Mutators
    public function setCustomerNameAttribute($value) {
        $this->attributes['customer_name'] = strtolower($value);
    }

    public function setCustomerAddressAttribute($value) {
        $this->attributes['customer_address'] = strtolower($value);
    }

    // Accessors
    public function getCustomerNameAttribute($value) {
        return ucwords($value);
    }

    public function getCustomerAddressAttribute($value) {
        return ucwords($value);
    }
}
