<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends BaseController
{
    //
    public function index(){
        return view('product.review.index');
    }
}
