<?php

namespace App\Http\Controllers;

use App\Stock;
use App\OutStock;
use Illuminate\Http\Request;

class OutStockController extends Controller
{
    //
    public function getByProject(Request $request, $id){
        $results = OutStock::where('project_id', $id)->get();
        $request->session()->put('project_id', $id);

    	$data = [];
    	foreach ($results as $result) {
    		$item = $result->stockCodeItem;
    		$division = $result->division;
    		$vals = [
    			$result->sc,
                $item->item_name,
                $item->desc1,
                $item->desc2,
                $item->desc3,
                $item->info,
                $result->qty,
    			$item->uoi,
    			$division == null ? '<b>UNDEFINED</b>' : $division->name,
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
        OutStock::destroy($id);
        return response()->json(['success' => 'Record has been deleted successfully!']);
    }
}
