<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

// Models
use App\Models\Employee;
use App\Models\Owner;

class AuthController extends Controller
{
    public function login(Request $request) {
		$owner = Owner::where('owner_email', $request->email)->first();
        $employee = Employee::where('employee_email', $request->email)->first();
	
		if($owner && Hash::check($request->password, $owner->owner_password)) {
            $token = $owner->createToken('pos-opticalgo-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
				'data' => [
					'id' => $owner->id,
					'name' => $owner->owner_name,
					'email' => $owner->owner_email,
					'image' => $owner->owner_image,
					'role' => 1,
					'token' => $token
				]
            ]);
        }
	
        if($employee && Hash::check($request->password, $employee->employee_password)) {
            $token = $employee->createToken('pos-opticalgo-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'data' => [
					'id' => $employee->id,
					'name' => $employee->employee_name,
					'email' => $employee->employee_email,
					'image' => $employee->employee_image,
					'role' => 2,
					'token' => $token
				]
            ]);
        }

        return response()->json([
            'status' => 'not match',
        ]);
    }
}
