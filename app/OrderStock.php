<?php

namespace App;

use App\StockCodeItem;
use Illuminate\Database\Eloquent\Model;


class OrderStock extends Model
{
    public function stockCodeItem(){
    	return $this->belongsTo(StockCodeItem::class, 'sc');
    }
}
