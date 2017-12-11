<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\BaseController;
use App\Models\Supplier;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageController extends BaseController
{

    protected $supplier_status = ['禁用','启用'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [];
        if(isset($request->search)){
            if(isset($request->status) && $request->status != ''){
                $where['status'] = $request->status;
            }
            if(!empty($request->keyword)){
                $where[] = [$request->keyword_type, 'like', '%'.$request->keyword.'%'];
            }
        }

        unset($_REQUEST['_token']);

        //
        $data=DB::table('finance_supplier')->where($where)->orderBy('supplier_id','desc')->paginate(10);
        return view('supplier.manage.index',['data'=>$data,'supplier_status'=>$this->supplier_status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateStatus($id, $status){

        $data['status'] = !$status;

        $rs = Supplier::where('supplier_id', $id)->update($data);
        if($rs){
            return $this->ajaxSuccess('操作成功！', url('supplier/manage'));
        }
        return $this->ajaxError('操作失败！', url('supplier/manage'));
    }
}
