<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class BatchSetController extends BaseController
{
    //
    public function index(){
        return view('product.batch_set.index');
    }
}
