<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutStock extends Model
{
    //
	public function stockCodeItem(){
		return $this->belongsTo(StockCodeItem::class, 'sc');
	}

	public function division(){
		return $this->belongsTo(Division::class, 'division_id');
	}
}
