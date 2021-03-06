<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\libraries\libs\pinyin;
use App\Models\TbuyDzd;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\TbuyLogistics;
use App\Models\TbuyOrderDetails;
use DB;
use Illuminate\Http\Request;

class PendingController extends BaseController
{
    public $payStatus = [
        0=>'等待支付',
        1=>'支付成功',
        2=>'支付失败',
        3=>'订单关闭',
    ];
    public $orderStatus = [
        0=>'未初始化',      //待付款
        1=>'商家待发货',    //待发货
        2=>'已发货待签收',  //待收货
        4=>'已签收',       //已收货
        8=>'维权中',       //申请售后
        16=>'已退款',      //已退款
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$where['tbuy_order_details.is_balance'] = 1;

        $pay_status = $request->input('pay_status');
        $status = $request->input('status');
        $order_no = $request->input('order_no');
        unset($_REQUEST['_token']);
        //搜索
        if(isset($request->search)){

            if(!empty($order_no)){
                $where['tbuy_order.order_no'] = $order_no;
            }
            if(!empty($status)){
                $where['tbuy_order_details.status'] = $status;
            }
            if($pay_status != ''){
                $where['tbuy_order.status'] = $pay_status;
            }
            if(!empty($request->keyword)){
                $where[] = [$request->keyword_type, 'like', '%'.$request->keyword.'%'];
            }
            $where[] = ['create_time', '>=', $request->begin_time];
            $where[] = ['create_time', '<=', $request->end_time];
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
            $logistics = DB::table('tbuy_logistics')->where('details', $val->details_id)->first();
            if(!empty($logistics)){
                $val->expressData['express_company'] = $logistics->express_company;
                $val->expressData['express_no'] = $logistics->express_no;
            }
        }

        return view('balance.pending.index',['data'=>$order_list,'order_no'=>$order_no,'orderStatus'=>$this->orderStatus, 'pay_status' => $pay_status, 'status' => $status,'payStatus'=>$this->payStatus]);*/

        $order=new Order();
        if(request()->get('export')=='true'){
            $content=$order->queryOrderByIds(explode(',',request()->input('details_list')));
            TbuyDzd::exportFileNormal('待结算列表导出'.date('Y-m-d'),[
                '序号','商品名称','购买数量','商品ID','供应商简称','订单状态','订单号','下单账号','下单时间','收货人姓名','收货人电话','支付方式','物流公司','物流单号'
            ],$content);
        }
        $result=$order->queryOrderByPage(request()->input(),1,request()->get('sort'),request()->get('pagesize'));
        return view('balance.pending.index',['data'=>$result]);
    }

    public function orderDetail(){
        $details_id = request()->input('details_id');
//        查询物流信息,可能没有物流信息
        $logistics = DB::table('tbuy_logistics')->where('details', $details_id)->first();
        $expressData=[
            'data'=>[],
            'express_no'=>''
        ];
        if($logistics){
            $result = kuaidi(pinyin::get($logistics->express_company), $logistics->express_no);
            $expressData['express_company']=$logistics->express_company;
            $expressData['data'] = $result['data']??[];
            $expressData['express_no'] = $logistics->express_no;
        }
//        订单信息
        $orderDetail=DB::table('tbuy_order_details')
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
            ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
            ->leftJoin('tbuy_sku','tbuy_sku.sku_id','=','tbuy_order_details.sku_id')
            ->where('tbuy_order_details.details_id',$details_id)
            ->select('tbuy_order_details.details_id',
                't1.nodecode',
                'tbuy_order.create_time',
                'tbuy_order.remarks',
                'tbuy_order.order_no',
                't2.consignee_name',
                't2.mobile_no',
                't2.country',
                't2.province',
                't2.city',
                't2.region',
                't2.address',
                't5.supplier_name',
                'tbuy_sku.ga_integral',
                'tbuy_order_details.product_id',
                'tbuy_order_details.product_name',
                'tbuy_order_details.buy_count',
                'tbuy_order_details.price',
                'tbuy_order_details.shop_id',
                'tbuy_order.pay_time',
                'tbuy_order.pay_amount',
                'tbuy_order_details.detail_pv',
                'tbuy_order_details.hl_integral',
                'tbuy_order.pay_type_group',
                'tbuy_order_details.status')
            ->first();
//        商品信息
//        商家备注
//        运营备注
        $orderDetail->detailStatus=Order::parseStatus($orderDetail->status);
        $pay_arr=[];
        foreach (json_decode($orderDetail->pay_type_group,true) as $item){
            $pay_arr[]=[
                'remark'=>$item['Remarks'],
                'payAmount'=>$item['PayAmount']
            ];
        }
        $orderDetail->pay_arr=$pay_arr;
        return view(' balance.pending.order_detail',[
            'expressData'=>$expressData,
            'orderDetail'=>$orderDetail
        ]);
    }


    /**
     * Excel导出订单数据
     * @param Request $request
     */
    public function exportOrderData(Request $request){
        $where = [];
        $pay_status = $request->input('pay_status');
        $status = $request->input('status');
        $order_no = $request->input('order_no');
        unset($_REQUEST['_token']);
        //搜索
        if(!empty($request->search)){

            if(!empty($order_no)){
                $where['tbuy_order.order_no'] = $order_no;
            }
            if(!empty($status)){
                $where['tbuy_order_details.status'] = $status;
            }
            if($pay_status != ''){
                $where['tbuy_order.status'] = $pay_status;
            }
            if(!empty($request->keyword)){
                $where[] = [$request->keyword_type, 'like', '%'.$request->keyword.'%'];
            }
        }

        $order_list = DB::table('tbuy_order')
            ->join('tbuy_order_details', 'tbuy_order.order_id', '=', 'tbuy_order_details.order_id')
            ->join('tnet_reginfo', 'tbuy_order.node_id', '=', 'tnet_reginfo.nodeid')
            ->join('tbuy_order_consignee', 'tbuy_order.order_id', '=', 'tbuy_order_consignee.order_id')
            ->where($where)
            ->select('tbuy_order.order_id', 'tbuy_order.order_no',  'tbuy_order_details.product_name', 'tbuy_order_details.sku_remarks', 'tnet_reginfo.nodecode', 'tbuy_order_details.price', 'tbuy_order_details.buy_count', 'tbuy_order_details.postage', 'tbuy_order.status as pay_status', 'tbuy_order.pay_type_group','tbuy_order_details.status', 'tbuy_order.create_time', 'tbuy_order.pay_time','tbuy_order_consignee.province','tbuy_order_consignee.city','tbuy_order_consignee.region','tbuy_order_consignee.address','tbuy_order_consignee.consignee_name','tbuy_order_consignee.mobile_no','tbuy_order_consignee.phone','tbuy_order_consignee.post_code')
            ->orderBy('order_id', 'desc')
            ->get();

        foreach ($order_list as &$val) {
            $val->price = round($val->price, 2);
            $pay_type_group = json_decode($val->pay_type_group, true);
            $str = '';
            foreach ($pay_type_group as $k => $v) {
                $str.= $v['Remarks'] . ':' . $v['PayAmount']/ 100 . '，';
            }
            $val->pay_type_group = trim($str, '，');
            $val->pay_status = isset($this->payStatus[$val->pay_status]) ? $this->payStatus[$val->pay_status] : $val->pay_status;
            $val->status = isset($this->orderStatus[$val->status]) ? $this->orderStatus[$val->status] : $val->pay_status;

            $val->order_no = "'".$val->order_no;
            $val->product_name = str_replace("\r\n",'',$val->product_name);
            $val->consignee_address = $val->province.' '.$val->city.' '.$val->region.' '.$val->address;
            unset($val->province,$val->city,$val->region,$val->address);
        }

        $order_list = json_decode(json_encode($order_list), true);

        $title = ['订单ID号','订单编号','产品名称','商品属性','手机号','商品单价','数量','邮费','支付状态','支付方式','订单状态','下单时间','支付时间','收货人姓名','收货人手机号','收货人电话','邮编','收货地址'];

        exportData($title,$order_list,'订单数据'.date('Y-m-d'));

    }

    /**
     * 订单发货
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function ship(Request $request, $id){
        $details_id = (int)$id;

        $data = TbuyOrderDetails::where(['details_id' => $details_id])->first();
        if(empty($data)) exit('数据不存在');

        $express_company = [
            '邮政包裹'    => 'youzhengguonei',
            'EMS'         => 'ems',
            '顺丰'        => 'shunfeng',
            '申通'        => 'shentong',
            '圆通'        => 'yuantong',
            '中通'        => 'zhongtong',
            '汇通'        => 'huitongkuaidi',
            '韵达'        => 'yunda',
            '宅急送'      => 'zhaijisong',
            '天天'        => 'tiantian',
            '京东'        => 'jd',
            '优速'        => 'youshuwuliu',
            '自提'        => ''
        ];

        if($request->isMethod('post'))
        {
            $express_company = $request->input('express_company');
            $express_company_no = $request->input('express_company_no');
            $express_no = $request->input('express_no');

            unset($_REQUEST['express_company'],$_REQUEST['express_company_no'],$_REQUEST['express_no'],$_REQUEST['_token']);
            if($data->status != 1)
            {
                return $this->ajaxError('订单状态已变更，无法发货');
            }

            if(empty($express_company)){
                return $this->ajaxError('请选择快递公司');
            }
            if(empty($express_no)){
                return $this->ajaxError('请填写快递单号');
            }


            $logistics_data = array(
                'lid'           => TbuyLogistics::getNextSeq(),
                'details'       => $details_id,
                'logistics_type'    => 0,
                'express_company'   => $express_company,
                'express_no'        => $express_no,
                'express_company_no'    => $express_company_no,
            );
            $res = TbuyLogistics::insert($logistics_data);
            if(!$res) return $this->ajaxError();

            TbuyOrderDetails::where(['details_id' => $details_id])->update(['status' => 2]);


            $redirect_url = 'order/order';
            if(isset($request->_show)){
                $redirect_url = 'order/order/'.$data->order_id;
                unset($_REQUEST['_show']);
            }

            return $this->ajaxSuccess('发货成功！', url($redirect_url.'?'.http_build_query($_REQUEST)));
        }else{

            $consignee_info = DB::table('tbuy_order_consignee')->where(['order_id' => $data->order_id])->first();

            return view('order.order.ship', ['express_company' => $express_company, 'details_id' => $details_id, 'consignee_info' => $consignee_info]);
        }

    }

    /**
     * 订单详情
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        $orderDetail = DB::table('tbuy_order')
            ->join('tbuy_order_details', 'tbuy_order.order_id', '=', 'tbuy_order_details.order_id')
            ->join('tnet_reginfo', 'tbuy_order.node_id', '=', 'tnet_reginfo.nodeid')
            ->join('tbuy_order_consignee', 'tbuy_order.order_id', '=', 'tbuy_order_consignee.order_id')
            ->where(['tbuy_order.order_id'=>$id])
            ->first(['tbuy_order_details.status','tnet_reginfo.nodecode','tnet_reginfo.nodename','tbuy_order.order_id','tbuy_order.create_time','tbuy_order.pay_type_group','tbuy_order.pay_time','tbuy_order.remarks','tbuy_order_consignee.consignee_name','tbuy_order_consignee.mobile_no','tbuy_order_consignee.country','tbuy_order_consignee.province','tbuy_order_consignee.city','tbuy_order_consignee.region','tbuy_order_consignee.address','tbuy_order_details.product_id','tbuy_order_details.product_name','tbuy_order_details.buy_count','tbuy_order_details.price','tbuy_order_details.sku_id','tbuy_order_details.details_id']);

        $orderDetail->price = round($orderDetail->price, 2);
        $pay_type_group = json_decode($orderDetail->pay_type_group, true);
        $str = '';
        foreach ($pay_type_group as $k => $v) {
            $str.= $v['Remarks'] . ':' . $v['PayAmount']/ 100 . '，';
        }
        $orderDetail->pay_group = trim($str, '，');
        $supplier = DB::table('tbuy_product')->join('finance_supplier','tbuy_product.supplier_id','=','finance_supplier.supplier_id')->where('product_id',$orderDetail->product_id)->first(['finance_supplier.supplier_id','finance_supplier.supplier_name']);

        if(!empty($supplier)){
            $orderDetail->supplier_name = $supplier->supplier_name;
            $orderDetail->supplier_id = $supplier->supplier_id;
        }

        $orderDetail->sku = DB::table('tbuy_sku')->where('sku_id',$orderDetail->sku_id)->first(['hl_integral','pv','ga_integral']);

        $expressData = $this->logistics($orderDetail->details_id);

        return view('order.order.show',['orderDetail'=>$orderDetail,'orderStatus'=>$this->orderStatus,'expressData'=>$expressData]);
    }

    /**
     * 订单的物流信息
     * @param $details_id
     * @return array
     */
    private function logistics($details_id){
        $logistics = DB::table('tbuy_logistics')->where('details', $details_id)->first();
        $expressData=['data'=>[], 'express_no'=>''];
        if($logistics){
            $result = kuaidi(pinyin::get($logistics->express_company), $logistics->express_no);
            $expressData['express_company']=$logistics->express_company;
            $expressData['data'] = $result['data']?:[];
            $expressData['express_no'] = $logistics->express_no;
        }
        return $expressData;
    }

    /**
     * 标记结算状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markInSettle(){
        if(request()->isXmlHttpRequest()){
            //子订单中的is_balance由1标记为2
            if(empty(request()->input('details_id'))){
                return response()->json(['status'=>0,'msg'=>'请选择至少一个子订单','data'=>'']);
            }
            $res=DB::table('tbuy_order_details')
                ->whereIn('details_id',explode(',',request()->input('details_id')))
                ->update(['is_balance'=>2]);
            if ($res){
                return response()->json(['status'=>1,'msg'=>'操作成功','data'=>'']);
            }else{
                return response()->json(['status'=>0,'msg'=>'操作失败,可能已经标记','data'=>'']);
            }
        }else{
            return response()->json(['status'=>0,'msg'=>'error method','data'=>'']);
        }
    }

}
