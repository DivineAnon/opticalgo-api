<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'stock_code', 'stock_type', 'stock_color', 
        'stock_quantity', 'brands_id', 'categories_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function brand() {
        return $this->belongsTo(Brand::class, 'brands_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }

    // Mutators
    public function setStockTypeAttribute($value) {
        $this->attributes['stock_type'] = strtolower($value);
    }

    public function setStockColorAttribute($value) {
        $this->attributes['stock_color'] = strtolower($value);
    }

    // Accessors
    public function getStockTypeAttribute($value) {
        return ucwords($value);
    }

    public function getStockColorAttribute($value) {
        return ucwords($value);
    }
}
