<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InStock extends Model
{
    //
    public function stockCodeItem(){
    	return $this->belongsTo(StockCodeItem::class, 'sc');
    }
}
