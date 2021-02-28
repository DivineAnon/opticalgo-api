<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'category_code', 'category_name', 'category_description', 'category_price'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function stocks() {
        return $this->hasMany(Stock::class, 'categories_id');
    }

    // Mutators
    public function setCategoryNameAttribute($value) {
        $this->attributes['category_name'] = strtolower($value);
    }

    public function setCategoryDescriptionAttribute($value) {
        $this->attributes['category_description'] = strtolower($value);
    }

    // Accessors
    public function getCategoryNameAttribute($value) {
        return ucwords($value);
    }

    public function getCategoryDescriptionAttribute($value) {
        return ucfirst($value);
    }
}
