<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends BaseController
{
    //
    public function index(){
        return view('supplier.review.index');
    }
}
