<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends BaseController
{
    //
    public function index(){
        return view('supplier.add.index');
    }
}
