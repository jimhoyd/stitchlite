<?php

namespace App\Services\Channels;

use GuzzleHttp\Client;

use App\Product;
use App\Channel;

abstract class Sync implements SyncInterface {
	
	private $client;
	
	private $url;

	abstract public function nomalizeData($data);
	
	abstract public function mapProductData($data);
	
	abstract public function mapVariantData($data);
		
	// pass in the channel object so we can get the sync url
	public function __construct(Channel $channel) {
		// set the url from the database
		$this->url = $channel->sync;
		// setup guzzel client
		$this->client = new Client();
	}
	
	public function fetchData() {
		$response = $this->client->request('GET', $this->url);
		return json_decode($response->getBody(), true);
	}
		
	// store product
	public function storeProduct($data) {
		$data = $this->mapProductData($data);
		return Product::updateOrCreate(['sku'=>$data['sku']], $data);				
	}
	
	// store variant
	public function storeVariant(Product $product, $data) {
		$data = $this->mapVariantData($data);
		return $product->variants()->updateOrCreate(['sku'=>$data['sku']], $data);
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
			foreach($productData['variants'] as $variantData) {
				// store the variant, if exsit update
				$this->storeVariant($product, $variantData);
			}				
		}		
		
		return true;
	}
	
}