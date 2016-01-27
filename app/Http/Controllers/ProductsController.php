<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product;

use Validator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit', 10);
    	
//     	$products = Cache::remember('products', 15/60, function() use($limit) {
//     		return Product::orderBy('created_at', 'desc')->paginate($limit);
// 			return Product::all();
//     	});

    	$products = Product::orderBy('created_at', 'desc')->paginate($limit);
    	
    	// trigger to expose all the variants
    	foreach($products as $product) {
    		$product->channels;
    		foreach($product->variants as $variant) {
    			$variant->channels;
    		}
    	}
    	
    	return response()->json(array_merge($products->toArray(), ['code'=> 200]), 200);
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
    	
        // create new product
        $product = Product::create([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
       
       return response()->json(['message'=>"Product {$request->name} has been created", 'data'=>$product, 'code'=>201], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($sku)
    {
        $product = Product::where('sku', '=', $sku)->limit(1)->first();        
        if(!$product) {
        	return response()->json(['message'=>"Unable to find product by sku:{$sku}", 'code'=>404], 404);
        }
        
        $product->channels;        
        foreach($product->variants as $variant) {
    		$variant->channels;
    	}
        
    	return response()->json(['data'=>$product, 'code'=>200], 200);
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
    	$product = Product::where('sku', '=', $sku)->limit(1)->first();
    	if(!$product) {
    		return response()->json(['message'=>"Unable to find product by sku:{$sku}", 'code'=>404], 404);
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
    	 
    	$product->update([
        	"name" => $request->name,
        	"sku" => $request->sku,
        	"stock" => $request->stock,        	
        	"price" => $request->price,
        ]);
    	 
    	return response()->json(['message'=>"Product sku:{$sku} has been updated", 'data'=>$product, 'code'=>200], 200); 
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
    	$product = Product::where('sku', '=', $sku)->limit(1)->first();
    	if(!$product) {
    		return response()->json(['message'=>"Unable to find product by sku:{$sku}", 'code'=>404], 404);
    	}
    	
    	$product->delete();
    	    	
    	return response()->json(['message'=>"Product sku:{$sku} has been deleted", 'code'=>200], 200);
    }
}
