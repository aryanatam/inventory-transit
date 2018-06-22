<?php

namespace App\Http\Controllers;

use App\AdjustStock;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class AdjustStockController extends Controller
{
    //
	public function store(Request $request) {

			$sc = Input::get('sc');
			$adjust = Input::get('adjust');
			$total = Input::get('total');

			if ($adjust == null || $adjust == $total) {
				return response()->json(['error' => 'invalid'], 400);
			}

			$val = intval($adjust) - intval($total);
			$item = new AdjustStock;
			$item->sc = $sc;
			$item->adjust = $val;
			$item->save();

			return response()->json(['success' => 'success'], 200);
	}
}
