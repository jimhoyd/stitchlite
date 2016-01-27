<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product;
use App\Variant;
use App\Channel;

use GuzzleHttp\Client;

use Validator;


class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit', 10);
    	
//     	$channels = Cache::remember('channels', 15/60, function() use($limit) {
//     		return Channel::orderBy('created_at', 'desc')->paginate($limit);
// 			return Channel::all();
//     	});

    	$channels = Channel::orderBy('created_at', 'desc')->paginate($limit);
    	
    	return response()->json(array_merge($channels->toArray(), ['code'=> 200]), 200);
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
	        'name' => 'required|max:255'
	    ]);
	
	    if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
	    }
    	
        // create new channel
        $channel = Channel::create([
        	"name" => $request->name,
        ]);
       
       return response()->json(['message'=>"Channel {$request->name} has been created", 'data'=>$channel, 'code'=>201], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = Channel::find($id);        
        if(!$channel) {
        	return response()->json(['message'=>"Unable to find channel by id:{$id}", 'code'=>404], 404);
        }
        
    	return response()->json(['data'=>$channel, 'code'=>200], 200);
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
        $channel = Channel::find($id);        
        if(!$channel) {
        	return response()->json(['message'=>"Unable to find channel by id:{$id}", 'code'=>404], 404);
        }    
    	    	
    	$validator = Validator::make($request->all(), [
	        'name' => 'required|max:255'
	    ]);
	
	    if ($validator->fails()) {
			return response()->json(['message'=>$validator->errors(), 'code'=>422], 422);
	    }
    	 
    	$channel->update([
        	"name" => $request->name,
        ]);
    	 
    	return response()->json(['message'=>"Channel id:{$id} has been updated", 'data'=>$channel, 'code'=>200], 200); 
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
    	$channel = Channel::find($id);
    	if(!$channel) {
    		return response()->json(['message'=>"Unable to find channel by id:{$id}", 'code'=>404], 404);
    	}
    	
    	$channel->delete();
    	    	
    	return response()->json(['message'=>"Channel id:{$id} has been deleted", 'code'=>200], 200);
    }
    
    public function sync() {
    	$httpClient = new Client();
    
    	// fetch the data
    	$response = $httpClient->request('GET', 'https://vendjimhoyd.vendhq.com/api/products?active=1&access_token=9VGEiK4dkm7UQRw1t2EpO1Hl5yIOJHaAVPnx0qVc');
    	$data = json_decode($response->getBody(), true);
    	
		$products = [];
		
    	foreach($data['products'] as $item) {
   			if(!$item['variant_parent_id']) {   				
   				$item['variants'] = [];
   				$products[$item['id']] = $item;
   			}     		
    	}
    	foreach($data['products'] as $item) {
    		if($item['variant_parent_id']) {
    			$products[$item['variant_parent_id']]['variants'][$item['id']] = $item;
    		}
    	}
    	
    	// loop thru the data
    	foreach($products as $p) {
    		$sku = $p['sku'];
    
    		// create the product if does not exist
    		$product = Product::where('sku', $sku)->get()->first();
    		if(!$product) {
    			$product = Product::create([
    					'name' => $p['name'],
    					'sku' => $sku,
    					'price' => (float) $p['price'],
    					'quantity' => (int) array_reduce($p['inventory'], function($total, $item) {
    						return $total+$item['count'];
    					}, 0)
    			]);
    		}
    
    		// loop thru all the variants
    		foreach($p['variants'] as $v) {
    			// remapping from variant data
    			extract([
    					'name' => $v['name'],
    					'sku' =>  $v['sku'],
    					'price' => (float) $v['price'],
    					'quantity' =>  (int) array_reduce($v['inventory'], function($total, $item) {
    						return $total+$item['count'];
    					}, 0)
    			], EXTR_OVERWRITE);
    			 
    			$productVariants = $product->variants();
    			$variant = $productVariants->where('sku', $sku)->get()->first();
    			if(!$variant) {
    				$variant = $productVariants->create(compact('name', 'sku', 'quantity', 'price'));
    			} else {
    				$variant->update(compact('name', 'quantity', 'price'));
    			}
    		}
    
    	}
    	 
    }    
    
    public function sync2() {
    	$httpClient = new Client();
    	    	
    	// fetch the data
    	$response = $httpClient->request('GET', 'https://3155a4a7f64a0ce0f7cf95f93b852182:fcd5194e83e316e7d1b3f2d915d92b06@stitchlite-jimhoyd.myshopify.com/admin/products.json');    	    	
    	$data = json_decode($response->getBody(), true);
    	
    	$products = $data['products'];
    	
    	// loop thru the data
    	foreach($products as $p) {    		
    		$sku = $p['handle'];
    		
    		// create the product if does not exist
    		$product = Product::where('sku', $sku)->get()->first();
    		if(!$product) {
    			$product = Product::create([
    				'name' => $p['title'],
    				'sku' => $sku
    			]);    			 
    		}
    		
    		// loop thru all the variants
    		foreach($p['variants'] as $variantData) {
    			// remapping from variant data
    			extract([
					'name' => $variantData['title'],
					'sku' =>  $variantData['sku'],
					'quantity' => $variantData['inventory_quantity'],
					'price' => $variantData['price']    					    	
    			], EXTR_OVERWRITE);
    			
    			$v = $product->variants(); 
    			$variant = $v->where('sku', $sku)->get()->first();
    			if(!$variant) {
    				$variant = $v->create(compact('name', 'sku', 'quantity', 'price'));    				
    			} else {
    				$variant->update(compact('name', 'quantity', 'price'));
    			}
    		}
    		
    	}
    	
    }
}
