<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\BaseController;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShipController extends BaseController
{
    //
    public $payStatus = [
        0=>'等待支付',
        1=>'支付成功',
        2=>'支付失败',
        3=>'订单关闭',
    ];
    public $orderStatus = [
        0=>'未初始化',
        1=>'商家待发货',
        2=>'已发货待签收',
        4=>'已签收',
        8=>'维权中',
        16=>'已退款',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $where = [];
        $whereBetween = [];
        $pay_status = $request->input('pay_status');
        if($pay_status!=null){
            $where['tbuy_order.status'] = $pay_status;
        }

        $status = $request->input('status');
        if($status!=null){
            if($status>=8 && $status<=15){
                $where[] = ['tbuy_order_details.status', '>=', 8];
                $where[] = ['tbuy_order_details.status', '<=', 15];

            }else{
                $where['tbuy_order_details.status'] = $status;
            }
        }

        $order_no = $request->input('order_no');
        if(!empty($order_no)){
            $where['tbuy_order.order_no'] = $order_no;
        }

        $order_list = DB::table('tbuy_order')
            ->join('tbuy_order_details', 'tbuy_order.order_id', '=', 'tbuy_order_details.order_id')
            ->join('tnet_reginfo', 'tbuy_order.node_id', '=', 'tnet_reginfo.nodeid')
            ->join('tbuy_order_consignee', 'tbuy_order.order_id', '=', 'tbuy_order_consignee.order_id')
            ->where($where)
            ->select('tnet_reginfo.nodename','tbuy_order_details.postage','tbuy_order_consignee.consignee_name','tbuy_order.order_id', 'tbuy_order_details.details_id', 'tbuy_order.order_no', 'tbuy_order.node_id', 'tbuy_order.create_time', 'tbuy_order.pay_time', 'tbuy_order.pay_type_group', 'tbuy_order.status as pay_status', 'tbuy_order_details.product_name', 'tbuy_order_details.product_id', 'tbuy_order_details.amount', 'tbuy_order_details.price', 'tbuy_order_details.buy_count','tbuy_order_details.status', 'tbuy_order_details.sku_remarks', 'tnet_reginfo.nodecode')
            ->orderBy('order_id', 'desc')
            ->paginate(20);

        foreach ($order_list as &$val) {
            $val->price = round($val->price, 2);
            $pay_type_group = json_decode($val->pay_type_group, true);
            $str = '';
            foreach ($pay_type_group as $k => $v) {
                $str.= $v['Remarks'] . ':' . $v['PayAmount']/ 100 . '，';
            }
            $val->pay_group = trim($str, '，');
            $val->supplier_name = DB::table('tbuy_product')->join('finance_supplier','tbuy_product.supplier_id','=','finance_supplier.supplier_id')->where('product_id',$val->product_id)->value('finance_supplier.shortname');
            $val->product_image = DB::table('tbuy_product_img')->where('product_id',$val->product_id)->value('img_url');
        }


        return view('order.ship.index',['data'=>$order_list,'order_no'=>$order_no,'orderStatus'=>$this->orderStatus, 'pay_status' => $pay_status, 'status' => $status]);
    }
}
