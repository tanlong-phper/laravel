<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use DB;
use Illuminate\Http\Request;

class ExamineController extends BaseController
{
    //
    public function index(Request $request){

        $keyword = $request->input('keyword' , '');
        $status = $request->input('status' , '');

        $where = [];
        if($keyword){
            $where[] = ['product_name' , 'like' , '%'.$keyword.'%'];
        }
        if(isset($status) && $status!=''){
            $where[] = ['up_status','=',$status];
        }

        $product = DB::table('tbuy_product')->where($where)->select('product_id','product_name','up_status')->orderBy('product_id', 'desc')->paginate(15);

        return view('product.examine.index',['product'=>$product]);
    }
}
