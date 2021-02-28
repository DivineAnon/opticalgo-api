<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Import models
use App\Models\Owner;

// Import resources
use App\Http\Resources\OwnerCollection;

class OwnerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $owner = Owner::find($id);

        if($owner) {
            return response()->json([
                'status' => 'success', 
                'data' => $owner
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
        $owner = Owner::find($id);

        if($owner) {
            return response()->json([
                'status' => 'success', 
                'data' => $owner
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
        $owner = Owner::find($id);
		
        try {			
			if($request->owner_password) {
				$owner->owner_password = Hash::make($request->owner_password);
			}
			
			if($request->file('owner_image')) {
				$owner->owner_image = $request->file('owner_image')->storeAs('images/owner', $request->owner_name . '.jpg');
			}
			
			$owner->owner_name = $request->owner_name;
			$owner->owner_email = $request->owner_email;
		
            $owner->save();

            return response()->json(['status' => 'success']);

        } catch(QueryException $e) {
            return response()->json(['status' => 'failed']);
        }
    }

}
