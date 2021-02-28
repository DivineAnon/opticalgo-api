<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'employee_code', 'employee_name', 'employee_phone',
        'employee_address', 'employee_date_of_birth', 'employee_email',
        'employee_password', 'employee_image'
    ];

    protected $hidden = [
        'employee_password', 'created_at', 'updated_at'
    ];

    // Mutators
    public function setEmployeeNameAttribute($value) {
        $this->attributes['employee_name'] = strtolower($value);
    }

    public function setEmployeeAddressAttribute($value) {
        $this->attributes['employee_address'] = strtolower($value);
    }

    // Accessors
    public function getEmployeeNameAttribute($value) {
        return ucwords($value);
    }

    public function getEmployeeAddressAttribute($value) {
        return ucwords($value);
    }
}
