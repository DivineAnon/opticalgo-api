<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Hash;

// Import models
use App\Models\Employee;

// Import resources
use App\Http\Resources\EmployeeCollection;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'DESC');
        
        if(request()->keyword != '') {
			$employees = $employees->where('employee_code', 'LIKE', '%' . request()->keyword . '%')
								->orWhere('employee_name', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('employee_phone', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('employee_address', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('employee_date_of_birth', 'LIKE', '%' . request()->keyword . '%')
                                ->orWhere('employee_email', 'LIKE', '%' . request()->keyword . '%');
		}

        if($employees) {
            return new EmployeeCollection($employees->get());   
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $recent_employee_code = Employee::orderBy('created_at', 'DESC')->first();
		
		$last_increment_digits = ($recent_employee_code) ? substr($recent_employee_code->employee_code, -4) : 0;

        $employee_requests = $request->all();
		
		$employee_requests['employee_code'] = 'KRY' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);
		
		$employee_requests['employee_password'] = Hash::make('Opticalgo@admPOS0221EMP');

        try {
            Employee::create($employee_requests);

            return response()->json(['status' => 'success']);
        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        if($employee) {            
            return response()->json([
                'status' => 'success', 
                'data' => $employee
            ]);
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);

        if($employee) {
            return response()->json([
                'status' => 'success', 
                'data' => $employee
            ]);
        }

        return response()->json(['status' => 'not found']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
		
		try {			
			$employee->update($request->all());

            return response()->json(['status' => 'success']);

        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }
	
	public function update_profile(Request $request, $id) {
		$employee = Employee::find($id);
		
		try {			
			if($request->employee_password) {
				$employee->employee_password = Hash::make($request->employee_password);
			}
			
			if($request->file('employee_image')) {
				$employee->employee_image = $request->file('employee_image')->storeAs('images/employee', $request->employee_name . '.jpg');
			}
			
			$employee->employee_name = $request->employee_name;
			$employee->employee_email = $request->employee_email;
		
            $employee->save();

            return response()->json(['status' => 'success']);

        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if($employee) {
            try {
                $employee->delete();
    
                return response()->json(['status' => 'success']);
                
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        } 

        return response()->json(['status' => 'not found']);
    }
}
