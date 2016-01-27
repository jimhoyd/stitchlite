<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['name'];
    
    protected $hidden = [
    	'id',
    	'sync',
    	'pivot',
   		'updated_at',
   		'created_at'
    ];    
    
    public function products() {
    	return $this->belongsToMany('App\Product');
    }    
    
    public function variants() {
    	return $this->belongsToMany('App\Variant');
    }
}
