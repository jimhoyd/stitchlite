<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Location;

use Validator;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit', 10);
    	
//     	$items = Cache::remember('items', 15/60, function() use($limit) {
//     		return Item::orderBy('created_at', 'desc')->paginate($limit);
// 			return Item::all();
//     	});

    	$locations = Location::orderBy('created_at', 'desc')->paginate($limit);
    	
    	return response()->json(array_merge($locations->toArray(), ['code'=> 200]), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
			'sku' => 'required|max:60',
			'quantity' => 'required|integer',				
			'price' => 'required|numeric',
	    ]);
	
	    if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
	    }
    	
        // create new item
        $location = Location::create([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
       
       return response()->json(['message'=>"Item {$request->name} has been created", 'data'=>$location, 'code'=>201], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = Item::where('id', '=', $id)->limit(1)->first();
        if(!$location) {
        	return response()->json(['message'=>"Unable to find location by id:{$id}", 'code'=>404], 404);
        }

    	return response()->json(['data'=>$location, 'code'=>200], 200);
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
        // edit
		$location = Location::where('id', '=', $id)->limit(1)->first();
    	if(!$item) {
    		return response()->json(['message'=>"Unable to find location by id:{$id}", 'code'=>404], 404);
    	}        
    	    	
    	$validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
			'sku' => 'required|max:60',
			'quantity' => 'required|integer',				
			'price' => 'required|numeric',   			
    	]);
    	
    	if ($validator->fails()) {
    		return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
    	}

		$location->update([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
    	 
    	return response()->json(['message'=>"Location id:{$id} has been updated", 'data'=>$location, 'code'=>200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
		$location = Location::where('id', '=', $id)->limit(1)->first();
    	if(!$item) {
    		return response()->json(['message'=>"Unable to find location by id:{$id}", 'code'=>404], 404);
    	}

		$item->delete();
    	    	
    	return response()->json(['message'=>"Location id:{$id} has been deleted", 'code'=>200], 200);
    }
}
