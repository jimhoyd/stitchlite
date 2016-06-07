<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Vendor;

use Validator;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit', 10);
    	
//     	$vendors = Cache::remember('items', 15/60, function() use($limit) {
//     		return Item::orderBy('created_at', 'desc')->paginate($limit);
// 			return Item::all();
//     	});

    	$vendors = Item::orderBy('created_at', 'desc')->paginate($limit);
    	
    	return response()->json(array_merge($vendors->toArray(), ['code'=> 200]), 200);
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
			'id' => 'required|max:60',
			'quantity' => 'required|integer',				
			'price' => 'required|numeric',
	    ]);
	
	    if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
	    }
    	
        // create new item
        $vendor = Item::create([
        	"name" => $request->name,
        	"id" => $request->id,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
       
       return response()->json(['message'=>"Vendor {$request->name} has been created", 'data'=>$vendor, 'code'=>201], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Item::where('id', '=', $id)->limit(1)->first();
        if(!$vendor) {
        	return response()->json(['message'=>"Unable to find vendor by id:{$id}", 'code'=>404], 404);
        }

		$vendor->channels;
        foreach($vendor->variants as $variant) {
    		$variant->channels;
    	}
        
    	return response()->json(['data'=>$vendor, 'code'=>200], 200);
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
		$vendor = Item::where('id', '=', $id)->limit(1)->first();
    	if(!$vendor) {
    		return response()->json(['message'=>"Unable to find vendor by id:{$id}", 'code'=>404], 404);
    	}        
    	    	
    	$validator = Validator::make($request->all(), [
	        'name' => 'required|max:255',
			'id' => 'required|max:60',
			'quantity' => 'required|integer',				
			'price' => 'required|numeric',   			
    	]);
    	
    	if ($validator->fails()) {
    		return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
    	}

		$vendor->update([
        	"name" => $request->name,
        	"id" => $request->id,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
    	 
    	return response()->json(['message'=>"Vendor id:{$id} has been updated", 'data'=>$vendor, 'code'=>200], 200);
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
		$vendor = Item::where('id', '=', $id)->limit(1)->first();
    	if(!$vendor) {
    		return response()->json(['message'=>"Unable to find vendor by id:{$id}", 'code'=>404], 404);
    	}

		$vendor->delete();
    	    	
    	return response()->json(['message'=>"Vendor id:{$id} has been deleted", 'code'=>200], 200);
    }
}
