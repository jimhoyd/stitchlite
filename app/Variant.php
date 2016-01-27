<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    protected $fillable = ['name', 'sku', 'parent_sku', 'quantity', 'price'];
    
    protected $hidden = [
    		'id',
    		'updated_at',
    		'created_at'
    ];
    
    public function product() {
    	return $this->belongsTo('App\Product');
    }
    
    public function channel() {
    	
    }
}
