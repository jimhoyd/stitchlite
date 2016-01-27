<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'quantity', 'price'];
    
    protected $hidden = [
    		'id',
    		'parent_id',
    		'updated_at',
    		'created_at'
    ];
    
    public function variants() {
    	return $this->hasMany('App\Product', 'parent_id');
    }
    
    public function products() {
    	return $this->belongsTo('App\Product', 'parent_id');
    }
    
    public function channels() {
    	return $this->belongsToMany('App\Channel');
    }    
}
