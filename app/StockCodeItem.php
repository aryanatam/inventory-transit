<?php

namespace App;

use App\OrderStock;
use App\InStock;
use App\StockCodeItemsInfo;
use Illuminate\Database\Eloquent\Model;


class StockCodeItem extends Model
{
	protected $primaryKey = 'sc';

    public function getInfo(){
    	$result = StockCodeItemsInfo::where('info_sc', $this->sc)->first();
    	return $result == null ? '' : $result->info;
    }

    public function sumQtyOnOrder($order_id){
    	$result = OrderStock::where('project_id', $order_id)->where('sc', $this->sc)->sum('qty');
    	return $result;
    }

    public function sumInQtyOnOrder($order_id){
    	$result = InStock::where('project_id', $order_id)->where('sc', $this->sc)->sum('qty');
    	return $result;
    }
}
