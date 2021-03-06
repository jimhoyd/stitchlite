<?php

namespace App\Services\Channels;

class Shopify extends Sync {

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