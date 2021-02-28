<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = [
        'owner_name', 'owner_email', 'owner_password', 'owner_image'
    ];

    protected $hidden = [
        'owner_password', 'created_at', 'updated_at'
    ];

    // Mutators
    public function setOwnerNameAttribute($value) {
        $this->attributes['owner_name'] = strtolower($value);
    }

    // Accessors
    public function getOwnerNameAttribute($value) {
        return ucwords($value);
    }
}
