<?php

namespace App\Services\Channels;

use GuzzleHttp\Client;

use App\Product;
use App\Channel;

abstract class Sync implements SyncInterface {
	
	private $channel;
	
	private $client;
	
	private $url;

	abstract public function nomalizeData($data);
	
	abstract public function mapProductData($data);
	
	abstract public function mapVariantData($data);
		
	// pass in the channel object so we can get the sync url
	public function __construct(Channel $channel) {
		// set the channel
		$this->channel = $channel;
		// set the url from the database
		$this->url = $channel->sync;
		// setup guzzel client
		$this->client = new Client();
	}
	
	//refactor this code to use structured data from channels table 
	public function fetchData() {
		$response = $this->client->request('GET', $this->url);
		return json_decode($response->getBody(), true);
	}
		
	public function sync() {
		// fetch the data from the channel api
		// nomalize the data if it comes back linearly
		$products = $this->nomalizeData($this->fetchData());
	
		// loop thru the products
		foreach($products as $productData) {
			// store the product, if exist update
			$product = $this->storeProduct($productData);
			// loop thru the variants
			if($product && $productData['variants']) {
				foreach($productData['variants'] as $variantData) {
					// store the variant, if exsit update
					$this->storeVariant($product, $variantData);
				}
			}
		}
	
		return true;
	}	
	
	// store product
	private function storeProduct($data) {
		$data = $this->mapProductData($data);
		
		$product = Product::updateOrCreate(['sku'=>$data['sku']], $data);
		// add link to channel via pivot table
		// inefficient should refactor
		if(!$product->channels->contains($this->channel->id)) {
			$product->channels()->attach($this->channel->id);				
		}
		return $product;
	}
	
	// store variant
	private function storeVariant(Product $product, $data) {		
		$data = $this->mapVariantData($data);		
		
		$variant = $product->variants()->updateOrCreate(['sku'=>$data['sku']], $data);
		// add link to channel via pivot table
		// inefficient should refactor
		if(!$variant->channels->contains($this->channel->id)) {
			$variant->channels()->attach($this->channel->id);				
		}
		
		return $variant;
	}
}