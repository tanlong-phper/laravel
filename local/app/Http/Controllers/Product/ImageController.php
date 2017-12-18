<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ImageController extends BaseController
{
    //
    public function index(){
        return view('product.image.index');
    }
}
