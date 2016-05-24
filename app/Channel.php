<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['name'];
    
    protected $hidden = [
    	'sync',
    	'pivot',
   		'updated_at',
   		'created_at'
    ];    
    
    public function items() {
    	return $this->belongsToMany('App\Item');
    }    
}
