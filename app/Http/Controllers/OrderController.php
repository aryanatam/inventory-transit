<?php

namespace App\Http\Controllers;

use App\Order;
use App\DataType;
use App\StockCodeItem;
use App\InStock;
use App\OrderStock;
use Illuminate\Http\Request;

class OrderController extends Controller
{

	public function index(){

		$dataType = DataType::where('name', 'orders')->first();
		$isServerSide = isset($dataType->server_side) && $dataType->server_side;

		$rows = Order::get();

		$this->authorize('browse', app('App\Order'));

		return view("orders.browse", compact(
			'dataType',
			'isServerSide',
			'rows'
		));
	}

	public function all(){
		return Order::pluck('project_name', 'id')->toArray();
	}

	public function review(Request $request, $id)
	{
		$stocks = [];
		$in_stocks = InStock::where('project_id', $id)->pluck('sc')->toArray();
		$order_stock = OrderStock::where('project_id', $id)->pluck('sc')->toArray();
		foreach ($in_stocks as $stock) {
			array_push($stocks, $stock);
		}
		foreach ($order_stock as $stock) {
			array_push($stocks, $stock);
		}

		$results = StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
		->whereIn('sc', $stocks)
		->get();

		$totalData = count($results);

		$data = array();
		if(!empty($results))
		{
			foreach ($results as $result)
			{
				$order_qty = $result->sumQtyOnOrder($id);
				$o = $order_qty == null ? "<span style='color:lightgray'>none</span>" : $order_qty;
				$i = $result->sumInQtyOnOrder($id);

				$progress = $order_qty == null ? 0 : round(floatval($i)/floatval($o) * 100, 0);
				$progresstext = $order_qty == null ? "<span style='color:lightgray'>none</span>" : $progress.'%';
				$progresstype = "danger";
				$progresstype = $progress > 30 ? "warning" : $progresstype;
				$progresstype = $progress >= 100 ? "success" : $progresstype;
				$progressbar = '<div class="progress progress-xs"><div class="progress-bar progress-bar-'. $progresstype .'" style="width: '.$progress.'%"></div></div>';
				$progresstext = '<span style="font-size: 10px">'.$progresstext.'</span>';

				$nestedData['sc'] = $result->sc;
				$nestedData['item_name'] = $result->item_name;
				$nestedData['desc1'] = $result->desc1;
				$nestedData['desc2'] = $result->desc2;
				$nestedData['desc3'] = $result->desc3;
				$nestedData['info'] = $result->info;
				$nestedData['in'] = $i;
				$nestedData['req'] = $o;
				$nestedData['uoi'] = $result->uoi;
				$nestedData['progress'] = $progressbar;
				$nestedData['percentage'] = $progresstext;

				$data[] = $nestedData;

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalData), 
			"data"            => $data   
		);

		echo json_encode($json_data); 

	}
}
