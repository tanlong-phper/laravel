<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\TbuyDzd;
use App\Models\TbuyDzdDetailS;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class T7Controller extends BaseController
{
    /**
     * T+7列表  子订单已发货七天后 order_type=1,现已变更为15天以后
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $pageSize=!empty(request()->get('pagesize'))?request()->get('pagesize'):10;
        $orderModel=new Order();
        $res=DB::table('tbuy_order_details')
            ->where('tbuy_order.order_type',1)
            ->where(function ($query){
                $query->where('tbuy_order_details.is_balance',2)->orWhere(function($query){
                    $query->where('tbuy_order_details.is_balance',1)
                        ->where('tbuy_order_details.status','>=',2)//已发货
                        ->where('tbuy_order_details.status','<',8)//不显示维权中,已退款
                        ->where('t3.delivery_time','<=',date('Y-m-d H:i:s',strtotime('-15 day')));
                });
            })
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product','tbuy_product.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','tbuy_product.supplier_id')
            ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
            ->groupBy(['tbuy_product.supplier_id','t5.shortname','t5.cardholder','t5.bank_name','t5.bank_no','t5.transfer_type'])
            ->select('tbuy_product.supplier_id','t5.shortname','t5.cardholder','t5.bank_name','t5.bank_no','t5.transfer_type');
        if(request()->has('supplier_name')){
            $res->where('t5.supplier_name','like','%'.request()->get('supplier_name').'%');
        }
        if(request()->get('export')=='true'){
            $res->whereIn('tbuy_product.supplier_id',explode(',',request()->input('details_list')));//导出筛选
        }
        $res=$res->paginate($pageSize);
        foreach ($res->items() as $v){
            $v->details=TbuyDzdDetailS::countDetails($orderModel->getLists($v->supplier_id));
        }
        if(request()->get('export')=='true'){
            TbuyDzd::exportFileNormal('结算单导出表'.date('Y-m-d'),[
                '序号','供应商简称','结算总价','销售总额','手续费','银行账号','开户行','户名','转账类别'
            ],TbuyDzd::parseToExcelForS($res));
        }
        return view('balance.t7.index',['data'=>$res]);
    }

    /**
     * 查看供应商下的所有子订单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderDetailS(){
        //查询所有子订单列表 供应商ID存在,子订单IDS或对账单ID二选一
        $supplier_id=request()->get('supplier_id');
        if(request()->has('details_ids')){
            //未生成对账单
            $details_ids=explode(',',request()->get('details_ids'));
            $time_range="";
        }else{
            if(!request()->has('dzd_id')){
                return abort(403,'缺少参数!');
            }
            //已生成对账单
            $dzd_info=DB::table('tbuy_dzd_detail_s')
                ->leftJoin('tbuy_dzd','tbuy_dzd.dzd_id','=','tbuy_dzd_detail_s.dzd_id')
                ->where('tbuy_dzd_detail_s.dzd_id',request()->get('dzd_id'))
                ->where('tbuy_dzd_detail_s.supplier_id',$supplier_id)
                ->select('tbuy_dzd.date_str','tbuy_dzd_detail_s.details_ids')
                ->first();
            $details_ids=explode(',',$dzd_info->details_ids);
            $time_range=$dzd_info->date_str;
        }
        //获取子订单详情
        $res=DB::table('tbuy_order_details')
            ->whereIn('tbuy_order_details.details_id',$details_ids)
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
            ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
            ->select('tbuy_order_details.*',
                't1.nodecode',
                't2.consignee_name',
                't2.mobile_no',
                't2.country',
                't2.province',
                't2.city',
                't2.region',
                't2.address',
                'tbuy_order.pay_time',
                'tbuy_order.pay_type_group',
                'tbuy_order.order_no')
            ->get();
        $total=[
            'counts'=>0,
            'settleTotal'=>0,
            'saleTotal'=>0
        ];
        foreach ($res as $item){
            $total['counts']+=$item->buy_count;
            $total['settleTotal']+=$item->cost_price*$item->buy_count;
            $total['saleTotal']+=$item->amount;
            $item->pay_type_str=Order::parsePayTypeNum($item->pay_type_group);
        }
        //获取供应商简称
        $supplier_name=DB::table('finance_supplier')->where('supplier_id',$supplier_id)->pluck('supplier_name')->pop();
        return view('balance.t7.order_detail_s',['lists'=>$res,'supplier_name'=>$supplier_name,'time_range'=>$time_range,'total'=>$total]);
    }


    /**
     * T+7 生成预结算页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus7Tmp(){
        if(request()->isXmlHttpRequest()){
            //保存至表中导出同时将此批子订单改变状态
            //解析表单数据
            $formData=request()->input();
            //录入至表中
            DB::beginTransaction();
            $insertId=DB::table('tbuy_dzd')->insertGetId([
                'dzd_id'=> TbuyDzd::getNextSeq(),
                'trial_name'=>$formData['trial_name'],
                'trial_time'=>date('Y-m-d H:i:s'),
                'trial_remark'=>$formData['trial_remark'],
                'dzd_type'=>2,//对账单类型为T+7
                'dzd_status'=>1,//第一阶段运营初审
                'table_name'=>'全球购供应商对帐清单',
                'date_str'=>$formData['date_str'],
                'settle_price'=>$formData['settle_price'],  //结算总价
                'total_sales'=>$formData['total_sales'],     //销售总额
                'service_charge'=>$formData['service_charge'],//手续费
                'unusual'=>request()->get('unusual')   //是否为异常结算
            ],'dzd_id');
            if(!$insertId){
                DB::rollBack();
                return response()->json(['status'=>0,'msg'=>'系统繁忙,请稍后再试','data'=>'']);
            }
            $res=DB::table('tbuy_dzd_detail_s')->insert(TbuyDzdDetailS::parseFormData($insertId,$formData));
            if($res){
                $ids_arr=[];
                $ids=request()->input('details_ids');
                foreach ($ids as $v){
                    $ids_arr=array_merge($ids_arr,explode(',',$v));
                }
                if(empty($ids_arr)){
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'子订单参数无效,请稍后再试','data'=>'']);
                }
                $tmp=DB::table('tbuy_order_details')->whereIn('tbuy_order_details.details_id',$ids_arr)->update(['is_balance'=>3]);
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
            if(!request()->has('ids')){
                return abort(403,'您未选中任何供应商');
            }
            $orderModel=new Order();
            $res=DB::table('tbuy_order_details')
                ->where('tbuy_order.order_type',1)
                ->whereIn('tbuy_product.supplier_id',explode(',',request()->get('ids')))
                ->where(function ($query){
                    $query->where('tbuy_order_details.is_balance',2)->orWhere(function($query){
                        $query->where('tbuy_order_details.is_balance',1)
                            ->where('tbuy_order_details.status','>=',2)//已发货
                            ->where('tbuy_order_details.status','<',8)//不显示维权中,已退款
                            ->where('t3.delivery_time','<=',date('Y-m-d H:i:s',strtotime('-15 day')));
                    });
                })
                ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
                ->leftJoin('tbuy_product','tbuy_product.product_id','=','tbuy_order_details.product_id')
                ->leftJoin('finance_supplier t5','t5.supplier_id','=','tbuy_product.supplier_id')
                ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
                ->groupBy(['tbuy_product.supplier_id','t5.supplier_name','t5.cardholder','t5.bank_name','t5.bank_no','t5.transfer_type'])
                ->select('tbuy_product.supplier_id','t5.supplier_name','t5.cardholder','t5.bank_name','t5.bank_no','t5.transfer_type')
                ->get();
            $time_arr=[];
            foreach ($res as $v){
                $v->transfer_str=Supplier::parseTransferType($v->transfer_type);
                $orderList=$orderModel->getLists($v->supplier_id);
                $v->details=TbuyDzdDetailS::countDetails($orderList);
                $time_arr=array_merge($time_arr,TbuyDzdDetailS::calPayTime($orderList));
            }
            $collect=collect($time_arr);
            return view('balance.t7.t_plus7_tmp',['data'=>$res,'date_str'=>$collect->min().'至'.$collect->max()]);
        }
    }


}






























