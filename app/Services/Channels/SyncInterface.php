<?php

namespace App\Services\Channels;

use App\Item;

interface SyncInterface {
	
	public function fetchData();
	
	public function nomalizeData($data);
	
	public function mapItemData($data);
	
	public function mapVariantData($data);	
	
	public function sync();
	
}