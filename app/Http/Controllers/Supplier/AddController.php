<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\BaseController;
use App\Models\Supplier;
use App\Models\TnetArea;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddController extends BaseController
{
    //
    public function index(Request $request){
        $id = $request->supplier_id;
        $data=[];
        $city=[];
        $areaModel=new TnetArea();
        if(!empty($id)){
            $data=DB::table('finance_supplier')->where(['supplier_id'=>$id])->first();
            if(!empty($data->province)){
                $city=$areaModel->getChild($data->province);
            }
        }
        $province=$areaModel->getChild();//获得省级列表
        return view('supplier.add.index',['data'=>$data,'province'=>$province,'city'=>$city]);
    }

    public function store(Request $request){
        $request_info  = $request->all();

        if(!empty($request->supplier_id)){
            $rs=DB::table('finance_supplier')->where(['supplier_id'=>$request->supplier_id])->update($request_info);
            return redirect()->route('supplier/manage')->with('success','编辑供应商成功！');
        }else{
            $request_info['supplier_id']=Supplier::getNextSeq();
            $request_info['c_time']=date('Y-m-d H:i:s',time());
            $rs=DB::table('finance_supplier')->insert($request_info);
            if($rs){
                return redirect()->route('supplier/manage')->with('success','添加供应商成功！');
            }else{
                return redirect()->route('supplier/add')->with('error','添加供应商失败！');
            }
        }
    }

    

    public function getCity(Request $request){
        if(!isset($request->pid)) return;
        $areaModel=new TnetArea();
        $result=$areaModel->getChild($request->pid);
        return $this->ajaxSuccess('','',$result);
    }

}





























