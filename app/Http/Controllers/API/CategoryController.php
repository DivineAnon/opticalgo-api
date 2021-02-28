<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Category;

// Import resources
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at', 'DESC');
		
		if(request()->keyword != '') {
			$categories = $categories->where('category_code', 'LIKE', '%' . request()->keyword . '%')
									->orWhere('category_name', 'LIKE', '%' . request()->keyword . '%')
									->orWhere('category_description', 'LIKE', '%' . request()->keyword . '%')
									->orWhere('category_price', 'LIKE', '%' . request()->keyword . '%');
		}

        if($categories) {
            return new CategoryCollection($categories->get());
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
		$recent_category_code = Category::orderBy('created_at', 'DESC')->first();
	
		$last_increment_digits = ($recent_category_code) ? substr($recent_category_code->category_code, -4) : 0;
		
		$category_requests = $request->all();

		$category_requests['category_code'] = 'KTG' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);
		
        try {
            Category::create($category_requests);

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
        $category = Category::find($id);

        if($category) {
            return response()->json([
                'status' => 'success', 
                'data' => $category
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
        $category = Category::find($id);

        if($category) {
            return response()->json([
                'status' => 'success', 
                'data' => $category
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
        $category = Category::find($id);

        try {
            $category->update($request->all());

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
        $category = Category::find($id);

        if($category) {
            if($category->stocks()->count()) {
                return response()->json(['status' => 'restricted']);
            } 

            try {
                $category->delete();
    
                return response()->json(['status' => 'success']);
                
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }
        
        return response()->json(['status' => 'not found']);
    }
}
