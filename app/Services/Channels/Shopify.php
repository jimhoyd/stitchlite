<?php

namespace App\Services\Channels;

class Shopify extends Sync {

	public function __construct() {
		// TODO: move to config file
		$this->url = 'https://3155a4a7f64a0ce0f7cf95f93b852182:fcd5194e83e316e7d1b3f2d915d92b06@stitchlite-jimhoyd.myshopify.com/admin/products.json';
		return parent::__construct();
	}	
	
	public function nomalizeData($data) {
		return $data['products'];
	}
	
	public function mapProductData($data) {
		return [
			'name' => $data['title'],
			'sku' => @$data['handle']
		];
	}
	
	public function mapVariantData($data) {
		return [
			'name' => $data['title'],
			'sku' =>  $data['sku'],
			'quantity' => $data['inventory_quantity'],
			'price' => $data['price']
		];
	}
	
}