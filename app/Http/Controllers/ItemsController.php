<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;

use Validator;

class ItemsController extends Controller
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

    	$items = Item::orderBy('created_at', 'desc')->paginate($limit);
    	
    	// trigger to expose all the variants
    	foreach($items as $item) {
			$item->channels;
    		foreach($item->variants as $variant) {
    			$variant->channels;
    		}
    	}
    	
    	return response()->json(array_merge($items->toArray(), ['code'=> 200]), 200);
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
        $item = Item::create([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
       
       return response()->json(['message'=>"Item {$request->name} has been created", 'data'=>$item, 'code'=>201], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sku)
    {
        $item = Item::where('sku', '=', $sku)->limit(1)->first();
        if(!$item) {
        	return response()->json(['message'=>"Unable to find item by sku:{$sku}", 'code'=>404], 404);
        }

		$item->channels;
        foreach($item->variants as $variant) {
    		$variant->channels;
    	}
        
    	return response()->json(['data'=>$item, 'code'=>200], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sku)
    {
        // edit
		$item = Item::where('sku', '=', $sku)->limit(1)->first();
    	if(!$item) {
    		return response()->json(['message'=>"Unable to find item by sku:{$sku}", 'code'=>404], 404);
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

		$item->update([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
    	 
    	return response()->json(['message'=>"Item sku:{$sku} has been updated", 'data'=>$item, 'code'=>200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($sku)
    {
        // delete
		$item = Item::where('sku', '=', $sku)->limit(1)->first();
    	if(!$item) {
    		return response()->json(['message'=>"Unable to find item by sku:{$sku}", 'code'=>404], 404);
    	}

		$item->delete();
    	    	
    	return response()->json(['message'=>"Item sku:{$sku} has been deleted", 'code'=>200], 200);
    }
}
