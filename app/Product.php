<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'sku', 'quantity', 'price'];
    
    protected $hidden = [
    		'id',
    		'updated_at',
    		'created_at'
    ];    
    
}
