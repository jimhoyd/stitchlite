<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['sku', 'name', 'summary', 'description', 'quantity', 'price'];
    
    protected $hidden = [
    		'id',
    		'parent_id',
    		'updated_at',
    		'created_at'
    ];
    
    public function variants() {
    	return $this->hasMany('App\Item', 'parent_id');
    }
    
    public function items() {
    	return $this->belongsTo('App\Item', 'parent_id');
    }
    
    public function channels() {
    	return $this->belongsToMany('App\Channel');
    }    
}
