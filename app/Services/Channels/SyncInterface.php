<?php

namespace App\Services\Channels;

use App\Product;

interface SyncInterface {
	
	public function fetchData();
	
	public function nomalizeData($data);
	
	public function mapProductData($data);
	
	public function mapVariantData($data);	
	
	public function sync();
	
}