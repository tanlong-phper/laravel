<?php

namespace App\Http\Controllers\House;
use App\Http\Controllers\BaseController;
use App\Models\Account;
use Illuminate\Http\Request;

class HouseController extends BaseController {
	public function houseLister() {
		return view('house.houseLister');
	}
}