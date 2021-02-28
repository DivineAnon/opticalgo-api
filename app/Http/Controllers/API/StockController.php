<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Database\QueryException;

// Import models
use App\Models\Stock;

// Import resources
use App\Http\Resources\StockCollection;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {		
		if(request()->keyword != '') {
			$stocks = Stock::with(['brand', 'category'])
							->whereHas('brand', function($query) {
								$query->where('brand_name', 'LIKE', '%' . request()->keyword . '%');
							})
							->orWhereHas('category', function($query) {
								$query->where('category_name', 'LIKE', '%' . request()->keyword . '%');
							})
							->orWhere('stock_code', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('stock_type', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('stock_quantity', 'LIKE', '%' . request()->keyword . '%')
							->orWhere('stock_color', 'LIKE', '%' . request()->keyword . '%');
		} else {
			$stocks = Stock::with(['brand', 'category']);
		}

        if($stocks) {
            return new StockCollection($stocks->orderBy('created_at', 'DESC')->get());
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
		$recent_stock_code = Stock::orderBy('created_at', 'DESC')->first();

        $last_increment_digits = ($recent_stock_code) ? substr($recent_stock_code->stock_code, -4) : 0;
		
        $stock_requests = $request->all();

		$stock_requests['stock_code'] = 'STK' . str_pad($last_increment_digits + 1, 4, 0, STR_PAD_LEFT);
        
		try {
            Stock::create($stock_requests);

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
        $stock = Stock::with(['brand'])->find($id);

        if($stock) {
            return response()->json([
                'status' => 'success', 
                'data' => $stock
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
        $stock = Stock::with(['brand', 'category'])->find($id);

        if($stock) {
            return response()->json([
                'status' => 'success', 
                'data' => $stock
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
        $stock = Stock::find($id);

        try {
            $stock->update($request->all());

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
        $stock = Stock::find($id);

        if($stock) {
            try {
                $stock->delete();
    
                return response()->json(['status' => 'success']);
                
            } catch(QueryException $e) {
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }
}
