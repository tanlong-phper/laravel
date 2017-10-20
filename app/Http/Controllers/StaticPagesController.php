<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaticPagesController extends Controller
{
    //
    public function home(){

        



        /*$rs = DB::table('ADMIN_ACTION')->first();
        var_dump($rs);*/




        return view('static_pages/home');
    }
    public function help(){
        return view('static_pages/help');
    }
    public function about(){
        return view('static_pages/about');
    }
}
