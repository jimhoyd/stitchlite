<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product;
use App\Variant;
use App\Channel;

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
    	// ability to swwitch syncs
    	$channels = ['Shopify', 'Vend'];    	 
    	
    	foreach($channels as $channel) {
    		$class = '\App\Services\Channels\\'.$channel;
    		$channelSync = new $class();
    		$channelSync->sync();    		
    	}
    	
    	return response()->json(['message'=>"Sync completed", 'code'=>200], 200);
    }
}
