<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AttrController extends BaseController
{
    //
    public function index(){
        return view('product.attr.index');
    }
}
