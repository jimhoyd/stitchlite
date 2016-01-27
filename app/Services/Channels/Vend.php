<?php

namespace App\Services\Channels;

class Vend extends Sync {

	public function __construct() {
		// TODO: move to config file
		$this->url = 'https://vendjimhoyd.vendhq.com/api/products?active=1&access_token=9VGEiK4dkm7UQRw1t2EpO1Hl5yIOJHaAVPnx0qVc';
		return parent::__construct();
	}
	
	public function nomalizeData($data) {
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
		return $products;
	}
	
	public function mapProductData($data) {
		return [
    		'name' => $data['name'],
    		'sku' => $data['sku'],
    		'price' => (float) $data['price'],
    		'quantity' => (int) array_reduce($data['inventory'], function($total, $item) {
    			return $total+$item['count'];
    		}, 0)
    	];
	}
	
	public function mapVariantData($data) {
		return [
    		'name' => $data['name'],
    		'sku' =>  $data['sku'],
    		'price' => (float) $data['price'],
    		'quantity' =>  (int) array_reduce($data['inventory'], function($total, $item) {
    			return $total+$item['count'];
    		}, 0)
    	];
	}
	
}