<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code', 'product_name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function brands() {
        return $this->hasMany(Brand::class, 'products_id');
    }

    // Mutators
    public function setProductNameAttribute($value) {
        $this->attributes['product_name'] = strtolower($value);
    }

    // Accessors
    public function getProductNameAttribute($value) {
        return ucwords($value);
    }
}
