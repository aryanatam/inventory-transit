<?php

namespace App\Http\Controllers;

use App\OrderStock;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;

class OrderStockController extends Controller
{
    //
    public function getByProject(Request $request, $id){
        $results = OrderStock::where('project_id', $id)->get();
        $request->session()->put('project_id', $id);

    	$data = [];
    	foreach ($results as $result) {
    		$item = $result->stockCodeItem;
            $editUri = route('voyager.order-stocks.edit', $result->id);
            $deleteUri = '#';

    		$vals = [
    			$result->sc,
                $item->item_name,
                $item->desc1,
                $item->desc2,
                $item->desc3,
                $item->info,
                $result->qty,
    			$item->uoi,
                '<a href="javascript:deleteItem('.$result->id.')" class="delete">DELETE</a>'
    		];
    		$data[] = $vals;
    	}
  		$out['draw'] = 1;
  		$out['recordsTotal'] = count($results);
  		$out['recordsFiltered'] = count($results);
    	$out['data'] = $data;
    	return $out;
    }

    public function destroy($id)
    {
        OrderStock::destroy($id);
        return response()->json(['success' => 'Record has been deleted successfully!']);
    }
}
