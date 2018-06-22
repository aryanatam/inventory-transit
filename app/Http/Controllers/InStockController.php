<?php

namespace App\Http\Controllers;

use App\InStock;
use App\StockCodeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InStockController extends Controller
{
    //
    public function getByProject(Request $request, $id){
        $results = InStock::where('project_id', $id)->get();
        $request->session()->put('project_id', $id);
        

        $data = [];
        foreach ($results as $result) {
          $item = $result->stockCodeItem;
          $vals = [
             $result->sc,
             $item->item_name,
             $item->desc1,
             $item->desc2,
             $item->desc3,
             $item->info,
             $result->qty,
             $item->uoi,
             '<a href="javascript:deleteItem('.$result->id.')" class="delete">DELETE</a>'];
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
         $item = InStock::destroy($id);
         return response()->json(['success' => 'Record has been deleted successfully!']);
     }

     public function find(Request $request)
     {
        $term = trim($request->q);
        $items = null;
        if (empty($term)) {
            $items = StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->get();
        } else {
            $items =  StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('sc', $term)
            ->orWhereRaw("MATCH(sc,item_name,desc1,desc2,desc3) AGAINST((?) IN BOOLEAN MODE)", array($term))
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->orWhereRaw("MATCH(info) AGAINST((?) IN BOOLEAN MODE)", array($term))
            ->get();
        }

        if (count($items) <= 0) {
            $items =StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('item_name', 'like', '%'.$term.'%')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->orWhere('desc1', 'like', '%'.$term.'%')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->orWhere('desc2', 'like', '%'.$term.'%')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->orWhere('desc3', 'like', '%'.$term.'%')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->orWhere('info', 'like', '%'.$term.'%')
            ->whereIn('sc', function($query) {$query->from('in_stocks')->select('sc');})
            ->get();
        }


        $formatted_items = [];

        foreach ($items as $item) {
            $formatted_items[] = ['id' => $item->sc, 'text' => $item->sc . ' - ' . $item->item_name. ' ' . $item->desc1. ' ' . $item->desc2. ' ' . $item->desc3. ' ' . $item->info, 'uoi' => $item->uoi];
        }

        return \Response::json($formatted_items);
    }
}
