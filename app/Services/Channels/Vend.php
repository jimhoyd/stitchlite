<?php

namespace App\Services\Channels;

// refactor this class to only loop thru once, leveraging the products() and variants()

class Vend extends Sync {

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
    		'quantity' => $this->getTotalInventory($data['inventory'])
    	];
	}
	
	public function mapVariantData($data) {
		return [
    		'name' => $data['name'],
    		'sku' =>  $data['sku'],
    		'price' => (float) $data['price'],
    		'quantity' => $this->getTotalInventory($data['inventory'])
    	];
	}
	
	private function getTotalInventory($data) {
		return (int) array_reduce($data, function($total, $item) {
    		return $total+$item['count'];
    	}, 0);	
	}
	
}