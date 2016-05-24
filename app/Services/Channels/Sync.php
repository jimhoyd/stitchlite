<?php

namespace App\Services\Channels;

use GuzzleHttp\Client;

use App\Item;
use App\Channel;

abstract class Sync implements SyncInterface {
	
	private $channel;
	
	private $client;
	
	private $url;

	abstract public function nomalizeData($data);
	
	abstract public function mapItemData($data);
	
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
		$items = $this->nomalizeData($this->fetchData());
	
		// loop thru the products
		foreach($items as $itemData) {
			// store the product, if exist update
			$item = $this->storeItem($itemData);
			// loop thru the variants
			if($item && $itemData['variants']) {
				foreach($itemData['variants'] as $variantData) {
					// store the variant, if exsit update
					$this->storeVariant($item, $variantData);
				}
			}
		}
	
		return true;
	}	
	
	// store product
	private function storeItem($data) {
		$data = $this->mapItemData($data);
		
		$item = Item::updateOrCreate(['sku'=>$data['sku']], $data);
		// add link to channel via pivot table
		// inefficient should refactor
		if(!$item->channels->contains($this->channel->id)) {
			$item->channels()->attach($this->channel->id);
		}
		return $item;
	}
	
	// store variant
	private function storeVariant(Item $item, $data) {
		$data = $this->mapVariantData($data);		
		
		$variant = $item->variants()->updateOrCreate(['sku'=>$data['sku']], $data);
		// add link to channel via pivot table
		// inefficient should refactor
		if(!$variant->channels->contains($this->channel->id)) {
			$variant->channels()->attach($this->channel->id);				
		}
		
		return $variant;
	}
}