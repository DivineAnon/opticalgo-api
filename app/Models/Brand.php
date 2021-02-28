<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_name', 'products_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'products_id', 'id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class, 'brands_id');
    }

    // Mutators
    public function setBrandNameAttribute($value) {
        $this->attributes['brand_name'] = strtolower($value);
    }

    // Accessors
    public function getBrandNameAttribute($value) {
        return ucwords($value);
    }
}
