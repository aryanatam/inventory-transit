<?php

namespace App\Http\Controllers;

use App\StockCodeItem;
use App\StockCodeItemsInfo;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockCodeItemController extends Controller
{
    //
    protected $title = 'Data S/C';

    public function updateDataFormView() {
        $icon = 'voyager-archive';

        // Check permission
        $this->authorize('edit', app('App\StockCodeItem'));

        return view("stock-code-items.update-data", compact(
            'title',
            'icon',
            'item'
        ));
    }

	public function updateData() {
		if(Input::hasFile('import_file')){
			$path = Input::file('import_file')->getRealPath();
			StockCodeItem::truncate();
			Excel::filter('chunk')->load($path)->chunk(1000, function($results) {
				foreach($results as $result) {
					$insert[] = [
						'sc' => $result->sc,
						'item_name' => $result->item_name,
						'desc1' => $result->desc1,
						'desc2' => $result->desc2,
						'desc3' => $result->desc3,
						'uoi' => $result->uoi
					];
				}
				if(!empty($insert)){
					StockCodeItem::insert($insert);
				}
			});
		}
		return redirect()->route("voyager.stock-code-items.index");
	}


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Request $request)
    {
    	$term = trim($request->q);

    	if (empty($term)) {
    		return \Response::json([]);
    	}

        $items =  StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('sc', $term)
            ->orWhereRaw("MATCH(sc,item_name,desc1,desc2,desc3) AGAINST((?) IN BOOLEAN MODE)", array($term))
            ->orWhereRaw("MATCH(info) AGAINST((?) IN BOOLEAN MODE)", array($term))
            ->get();

        if (count($items) <= 0) {
            $items =StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('item_name', 'like', '%'.$term.'%')
            ->orWhere('desc1', 'like', '%'.$term.'%')
            ->orWhere('desc2', 'like', '%'.$term.'%')
            ->orWhere('desc3', 'like', '%'.$term.'%')
            ->orWhere('info', 'like', '%'.$term.'%')
            ->get();
        }

    	$formatted_items = [];

    	foreach ($items as $item) {
    		$formatted_items[] = ['id' => $item->sc, 'text' => $item->sc . ' - ' . $item->item_name. ' ' . $item->desc1. ' ' . $item->desc2. ' ' . $item->desc3. ' ' . $item->info, 'uoi' => $item->uoi];
    	}

    	return \Response::json($formatted_items);
    }

    public function browse(Request $request)
    {

    	$columns = array( 
    		0 =>'sc', 
    		1 =>'item_name',
    		2=> 'desc1',
    		3=> 'desc2',
    		4=> 'desc3',
    		5=> 'uoi',
    	);

    	$totalData = StockCodeItem::count();

    	$totalFiltered = $totalData; 

    	$limit = $request->input('length');
    	$start = $request->input('start');

    	if(empty($request->input('search.value')))
    	{            
    		$results = StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
    		->offset($start)
    		->limit($limit)
    		->get();
    	}
    	else {
    		$search = $request->input('search.value'); 

    		$results =  StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('sc', $search)
    		->orWhereRaw("MATCH(sc,item_name,desc1,desc2,desc3) AGAINST((?) IN BOOLEAN MODE)", array($search))
    		->orWhereRaw("MATCH(info) AGAINST((?) IN BOOLEAN MODE)", array($search))
    		->offset($start)
    		->limit($limit)
    		->get();

    		$totalFiltered = StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')
            ->where('sc', $search)
            ->orWhereRaw("MATCH(sc,item_name,desc1,desc2,desc3) AGAINST((?) IN BOOLEAN MODE)", array($search))
    		->orWhereRaw("MATCH(info) AGAINST((?) IN BOOLEAN MODE)", array($search))->count();
    	}

    	$data = array();
    	if(!empty($results))
    	{
    		foreach ($results as $result)
    		{
    			$edit =  route('stock-code-item.edit', $result->sc);

    			$nestedData['sc'] = "<a href='{$edit}' title='Edit'>{$result->sc}</a>";
    			$nestedData['item_name'] = $result->item_name;
    			$nestedData['desc1'] = $result->desc1;
    			$nestedData['desc2'] = $result->desc2;
    			$nestedData['desc3'] = $result->desc3;
    			$nestedData['info'] = $result->info;
    			$nestedData['uoi'] = $result->uoi;

    			$data[] = $nestedData;

    		}
    	}

    	$json_data = array(
    		"draw"            => intval($request->input('draw')),  
    		"recordsTotal"    => intval($totalData),  
    		"recordsFiltered" => intval($totalFiltered), 
    		"data"            => $data   
    	);

    	echo json_encode($json_data); 

    }

    public function edit($id) {
        $title = 'Stocks Out';
        $icon = 'voyager-archive';

        $data = StockCodeItem::where('sc', $id)->first();

        // Check permission
        $this->authorize('edit', app('App\StockCodeItem'));

        return view("stock-code-items.edit", compact(
            'title',
            'icon',
            'data'
        ));
    }

    public function update($id) {
        if(Input::has('info')){
            $info = Input::get('info');
            $sc_info = StockCodeItemsInfo::firstOrNew(array('info_sc' => $id));
            $sc_info->info = $info;
            $sc_info->save();
        }
        return redirect()->route("voyager.stock-code-items.index");
    }

    public function findById($id) {
        return StockCodeItem::leftjoin('stock_code_items_infos', 'stock_code_items_infos.info_sc', '=', 'stock_code_items.sc')->find($id);
    }
}
