<?php

namespace App\Http\Controllers;

use App\InStock;
use App\OutStock;
use App\AdjustStock;
use App\StockCodeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
	public function all(Request $request)
	{
		$in_stocks = InStock::groupBy('sc')->selectRaw('sc, sum(qty) as qty')->pluck('qty', 'sc')->toArray();
		$out_stocks = OutStock::groupBy('sc')->selectRaw('sc, sum(qty) as qty')->pluck('qty', 'sc')->toArray();
		$adj_stocks = AdjustStock::groupBy('sc')->selectRaw('sc, sum(adjust) as qty')->pluck('qty', 'sc')->toArray();

		$stocks = null;
		$list_sc = [];
		foreach ($in_stocks as $stock => $val) {
			$stocks[$stock]['in'] = $val;
			array_push($list_sc, $stock);
		}
		foreach ($out_stocks as $stock => $val) {
			$stocks[$stock]['out'] = $val;
			array_push($list_sc, $stock);
		}
		foreach ($adj_stocks as $stock => $val) {
			$stocks[$stock]['adj'] = $val;
			array_push($list_sc, $stock);
		}

		$results = StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
		->whereIn('sc', $list_sc)
		->get();

		$totalData = count($results);

		$data = array();
		if(!empty($results))
		{
			foreach ($results as $result)
			{
				$sc = $result->sc;

				$in = !isset($stocks[$sc]['in']) ? 0 : $stocks[$sc]['in'];
				$out = !isset($stocks[$sc]['out']) ? 0 : $stocks[$sc]['out'];
				$adj = !isset($stocks[$sc]['adj']) ? 0 : $stocks[$sc]['adj'];

				$total = intval($in) - intval($out) + intval($adj);

				$uoi = $result->uoi;

				$nestedData['sc'] = $result->sc;
				$nestedData['item_name'] = $result->item_name;
				$nestedData['desc1'] = $result->desc1;
				$nestedData['desc2'] = $result->desc2;
				$nestedData['desc3'] = $result->desc3;
				$nestedData['info'] = $result->info;
				$nestedData['uoi'] = $uoi;
				$nestedData['total'] = $total;
				$nestedData['actions'] = '<a href="javascript:updateItem('.$sc.','.$total.')" class="edit">UPDATE</a>';

				$data[] = $nestedData;

			}
		}

		$json_data = array(
			"draw"            => intval($request->input('draw')),  
			"recordsTotal"    => intval($totalData),  
			"recordsFiltered" => intval($totalData), 
			"data"            => $data   
		);

		return json_encode($json_data); 

	}
}
