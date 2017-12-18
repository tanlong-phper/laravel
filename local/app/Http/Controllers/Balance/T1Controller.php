<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\Models\TbuyDzdDetail;
use App\Models\Order;
use App\Models\TbuyDzd;
use DB;
use Illuminate\Http\Request;

class T1Controller extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sort=!empty(request()->get('sort'))?request()->get('sort'):'asc';
        $pageSize=!empty(request()->get('pagesize'))?request()->get('pagesize'):10;
        $res=DB::table('tbuy_order_details')
            ->where('tbuy_order.order_type',2)
            ->where(function ($query){
                $query->where('tbuy_order_details.is_balance',2)->orWhere(function($query){
                    $query->where('tbuy_order_details.is_balance',1)
                        ->where('tbuy_order_details.status','>=',1)//已发货 2变成1,商家待发货
                        ->where('tbuy_order_details.status','<',8)//不显示维权中,已退款
                        ->where('tbuy_order.pay_time','<',date('Y-m-d 00:00:00'));
                });
            })
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
            ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
            ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
            ->select('tbuy_order_details.*',
                't1.nodecode',
                't2.consignee_name',
                't2.mobile_no',
                't2.country',
                't2.province',
                't2.city',
                't2.region',
                't2.address',
                't5.supplier_name',
                't5.shortname',
                't5.supplier_id',
                'tbuy_order.order_no',
                'tbuy_order.pay_time',
                'tbuy_order.pay_type_group')
            ->orderBy('tbuy_order.pay_time',$sort);
        if(request()->has('order_type')){
            $res->where('tbuy_order.order_type',request()->get('order_type'));
        }
        if(request()->has('pay_type')){
            $res->where('tbuy_order.pay_type',request()->get('pay_type'));
        }
        if(request()->has('s_time')){
            $res->where('tbuy_order.create_time','>=',request()->get('s_time'));
        }
        if(request()->has('e_time')){
            $res->where('tbuy_order.create_time','<=',request()->get('e_time'));
        }
        if(request()->has('keyname') and request()->has('keyval')){
            switch (request()->get('keyname')){
                case 'order_no':
                    $res->where('tbuy_order.order_no',request()->get('keyval'));
                    break;
                case 'product_id';
                    if(!is_numeric(request()->get('keyval'))){break;}//防止报错
                    $res->where('tbuy_order_details.product_id',request()->get('keyval'));
                    break;
                case 'product_name':
                    $res->where('tbuy_order_details.product_name','like','%'.request()->get('keyval').'%');
                    break;
                case 'supplier_name':
                    $res->where('t5.supplier_name','like','%'.request()->get('keyval').'%');
                    break;
                case 'nodecode':
                    $res->where('t1.nodecode',request()->get('keyval'));
                    break;
                case 'consignee_name':
                    $res->where('t2.consignee_name','like','%'.request()->get('keyval').'%');
                    break;
                case 'mobile_no':
                    $res->where('t2.mobile_no',request()->get('keyval'));
                    break;
                case 'express_no':
                    $res->where('t3.express_no',request()->get('keyval'));
                    break;
                default;
            }
        }
        if(request()->get('export')=='true'){
            $res->whereIn('tbuy_order_details.details_id',explode(',',request()->input('details_list')));//导出筛选
        }
        $res=$res->paginate($pageSize);
        foreach ($res->items() as $v){
            $v->pay_type_str=Order::parsePayTypeNum($v->pay_type_group);
        }
        if(request()->get('export')=='true'){
            TbuyDzd::exportFileNormal('结算单导出表'.date('Y-m-d'),[
                '序号','订单号','付款人电话','收货人姓名','收货人电话','收货人地址','商品名称','单价','数量','结算总价','销售总额','利润','供应商简称','付款时间','支付方式'
            ],TbuyDzd::parseToExcelForO($res));
        }
        return view('balance.t1.index',['data'=>$res]);
    }


    /**
     *T+1 生成预结算页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus1Tmp(){
        if(request()->isXmlHttpRequest()){
            //保存至表中导出同时将此批子订单改变状态 TODO
            $ids=request()->input('ids');
            //查询基本数据 TODO
            $res=DB::table('tbuy_order_details')
                ->where('tbuy_order.order_type',2)
                ->whereIn('tbuy_order_details.details_id',explode(',',$ids))
                ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
                ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
                ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
                ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
                ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
                ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
                ->select('tbuy_order_details.*',
                    't1.nodecode',
                    't2.consignee_name',
                    't2.mobile_no',
                    't2.country',
                    't2.province',
                    't2.city',
                    't2.region',
                    't2.address',
                    't5.supplier_name',
                    't5.supplier_id',
                    'tbuy_order.pay_time',
                    'tbuy_order.pay_type_group')
                ->get();
            foreach ($res as $v){
                $v->pay_type_str=Order::parsePayTypeNum($v->pay_type_group);
            }
            //解析表单数据
            $formData=request()->input();
            //录入至表中
            DB::beginTransaction();
            $insertId=DB::table('tbuy_dzd')->insertGetId([
                'dzd_id'=> TbuyDzd::getNextSeq(),
                'trial_name'=>$formData['trial_name'],
                'trial_time'=>date('Y-m-d H:i:s'),
                'trial_remark'=>$formData['trial_remark'],
                'dzd_type'=>1,//对账单类型为T+1
                'dzd_status'=>1,//第一阶段运营初审
                'table_name'=>$formData['supplier_name'].'订单明细',
                'date_str'=>$res->min('pay_time').'至'.$res->max('pay_time'),
                'settle_price'=>$formData['settle_price'],  //结算总价
                'total_sales'=>$formData['total_sales'],     //销售总额
                'unusual'=>request()->get('unusual') //是否为异常结算
            ],'dzd_id');
            if(!$insertId){
                DB::rollBack();
                return response()->json(['status'=>0,'msg'=>'系统繁忙,请稍后再试','data'=>'']);
            }
            $res=DB::table('tbuy_dzd_detail')->insert(TbuyDzdDetail::parseFormData($insertId,$formData));
            if($res){
                $tmp=DB::table('tbuy_order_details')->whereIn('tbuy_order_details.details_id',explode(',',$ids))->update(['is_balance'=>3]);
                if(!$tmp){
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'系统繁忙,请稍后再试','data'=>'']);
                }
                DB::commit();
            }else{
                DB::rollBack();
                return response()->json(['status'=>0,'msg'=>'系统繁忙,请稍后再试','data'=>'']);
            }
            //判断是否需要导出至excel
            return response()->json(['status'=>1,'msg'=>'success','data'=>$insertId]);
        }else{
            //检查ids是否属于同一供应商 TODO
            //查询ids列表
            $res=DB::table('tbuy_order_details')
                ->where('tbuy_order.order_type',2)
                ->whereIn('tbuy_order_details.details_id',explode(',',request()->get('ids')))
                ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
                ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
                ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
                ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
                ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
                ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
                ->select('tbuy_order_details.*',
                    't1.nodecode',
                    't2.consignee_name',
                    't2.mobile_no',
                    't2.country',
                    't2.province',
                    't2.city',
                    't2.region',
                    't2.address',
                    't5.supplier_name',
                    't5.shortname',
                    't5.supplier_id',
                    'tbuy_order.pay_time',
                    'tbuy_order.pay_type_group',
                    'tbuy_order.order_no')
                ->get();
            foreach ($res as $v){
                $v->pay_type_str=Order::parsePayTypeNum($v->pay_type_group);
            }
            return view('balance.t1.t_plus1_tmp',
                [
                    'data'=>$res,
                    'supplier_id'=>$res->first()->supplier_id,
                    'supplier_name'=>$res->first()->supplier_name,
                    'date_str'=>$res->min('pay_time').'至'.$res->max('pay_time')
                ]
            );
        }
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
}
