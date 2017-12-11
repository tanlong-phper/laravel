<?php
namespace App\Models;

use App\Models\Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Order extends Base {
    protected $table = 'tbuy_order';

    /**
     * 获取所有代购订单信息
     */
    public function get_purchas_order() {
        return DB::table('tbuy_order')->where('order_type',2)->orderBy('order_id', 'desc')->paginate(20);
    }

    /**
     * 模糊查询代购订单信息（根据订单编号）
     * @param `order_no`
     */
    public function get_purchas_order_by_order_no($order_no) {
        if(!$order_no) {
            return false;
        }

        return DB::table('tbuy_order')->where('order_no','like','%'.$order_no.'%')->orderBy('order_id','desc')->paginate(20);
    }

    /**
     * 根据订单ID号，查询订单信息
     * @param `id`  订单ID号
     */
    public function get_order_by_id($id) {
        if(!$id || !is_numeric($id)) {
            return false;
        }

        //查询订单信息
        return DB::table('tbuy_order')->where('order_id',$id)->first();
    }

    /**
     * 根据订单ID号，查询订单详细信息
     * @param `order_id`   订单ID号
     */
    public function get_order_detail($order_id) {
        if(!$order_id) {
            return false;
        }
        //查询订单信息
        return DB::table('tbuy_order_details')->where('order_id',$order_id)->get();
    }

    /**
     * 查询所有代购订单信息
     */
    public function get_purchas_order_detail() {
        return DB::table('tbuy_order_details')->orderBy('details_id','desc')->get();
    }

    /**
     * 根据订单ID号，查询订单的收货地址
     * @param `id`  订单ID号
     */
    public function get_consignee_by_id($id) {
        if(!$id) {
            return false;
        }
        return DB::table('tbuy_order_consignee')->where('order_id',$id)->first();
    }

    /**
     * 根据产品ID号，查询订单详情信息
     * @param `product_id`  产品ID号
     **/
    public function get_order_detail_by_product_id($product_id) {
        if(!$product_id || !is_numeric($product_id)) {
            return false;
        }
        //查询数据
        return DB::table('tbuy_order_details')->select('order_id','amount','buy_count')->where('product_id',$product_id)->get();
    }


    /**
     * 获取订单列表
     */
    public function get_order_list($keyword){
        $where = [
            ['status' , '=' , '1'],
        ];
        if($keyword){
            $where[] = ['order_no' , 'like' , '%'.$keyword.'%'];
        }
        $order = $this
            ->where($where)
            ->orderBy('order_id', 'desc')
            ->paginate(20);

        foreach($order as $k => &$o){
            $user = DB::table('tnet_reginfo t')
                        ->where(['t.nodeid'=>$o['node_id']])
                        ->select('t.nodeid','t.nodename','t.nodecode','t.introducer','t2.nodecode as introducer_nodecode')
                        ->leftJoin('tnet_reginfo t2' , 't.introducer' , '=' , 't2.nodeid')
                        ->first();
            $o->user = $user;

            $details = DB::table('tbuy_order_details')
                                ->where(['order_id'=>$o['order_id']])
                                ->select('details_id','product_id','product_name','amount','buy_count','status')
                                ->get();
            $o->details = $details;

            $product_name = [];
            foreach($details as $d){
                $product_name[] = $d->product_name;
            }
            $o->product_name = implode(',' , $product_name);
        }
        return $order;
    }


    /**
     * 获取线下订单列表
     */
    public function  get_lineorder_list($order_no,$pay_state){
        $where = array();
        if($order_no){
            $where[] = ['order_no' , '=' ,$order_no];
        }
        if($pay_state){
            $where[] = ['pay_state' , '=' ,$pay_state];
        }
        $order = DB::table('vshops_order o')
            ->where($where)
            ->leftJoin('ruzhu_merchant_basic r','o.sid' , '=' , 'r.id')
            ->select('o.order_no','o.id','o.sid','o.nodeid','o.consume_money','o.add_time','o.pay_time','o.pay_state','o.dropout_money','r.merchant_name','r.corpman_mobile')
            ->paginate(10); 
        
        return $order;
    }


    /**
     * 解析订单支付方式
     */
    public function parsePayType($pay_type_json){
        $pay_type = json_decode($pay_type_json , true);

        $result = [];
        $name = '';
        foreach($pay_type as $p){
            switch($p['PayType']){
                case 1 : { // 数据库存储的为分
                    $name = '储值积分';
                    break;
                }
                case 2 : { // 全球积分
                    $name = '全球积分';
                    break;
                }
                case 16 : { // 微信app
                    $name = '微信APP';
                    break;
                }
                case 8 : { // 微信jsapi
                    $name = '微信';
                    break;
                }
                case 32 : { // 支付宝
                    $name = '支付宝';
                    break;
                }
                case 256 : { // 快捷支付
                    $name = '快捷支付';
                    break;
                }
            }
            $result[$p['PayType']] = [
                'amount' => $p['PayAmount'] / 100,
                'name' => $name,
            ];
        }
        return $result;
    }

    // 生成一个订单号
    public function scopeCreateOrderNo($query, $order_id){
        $pad = str_pad($order_id,8,'0',STR_PAD_LEFT);
        $order_no  = date('Ymd').rand(100000,999999).$pad;
        return $order_no;
    }

    /**
     * 根据条件查询用户订单
     *
     * @param array $where
     * @param $isBalance
     * @param string $sort
     * @param int $pageSize
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function queryOrderByPage(array $where=[],$isBalance,$sort='asc',$pageSize=10){
        if(empty($sort)){
            $sort='asc';
        }
        if(empty($pageSize)){
            $pageSize=10;
        }
        $query=DB::table('tbuy_order_details')
            ->where( 'tbuy_order.status','=',1)
            ->where('tbuy_order_details.is_balance',$isBalance)
            ->where('t6.is_default',1)
            ->select(
                'tbuy_order.order_id',
                'tbuy_order.order_no', //status与详情的status发生了冲突
                'tbuy_order.node_id',
                'tbuy_order.amount',
                'tbuy_order.pay_amount',
                'tbuy_order.pay_time',
                'tbuy_order.pay_type',
                'tbuy_order.remarks',
                'tbuy_order.create_time',
                'tbuy_order.pay_type_group',
                'tbuy_order.discount_amount',
                'tbuy_order.order_type',
                'tbuy_order.order_pv',
                't2.consignee_name',
                't2.mobile_no',
                'tbuy_order_details.details_id',
                'tbuy_order_details.product_name',
                'tbuy_order_details.sku_id',
                'tbuy_order_details.is_balance',
                'tbuy_order_details.buy_count',
                'tbuy_order_details.status',
                't3.express_no',
                't3.express_company',
                't5.supplier_name',
                'tbuy_sku.sku_no',
                't6.img_url',
                't1.nodecode')
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
            ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
            ->leftJoin('tbuy_sku','tbuy_sku.sku_id','=','tbuy_order_details.sku_id')
            ->leftJoin('tbuy_product_img t6','t6.product_id','=','tbuy_order_details.product_id')
            ->orderBy('tbuy_order.order_id',$sort);
        if(!empty($where)){
            if (!empty($where['order_no'])){
                $query->where('tbuy_order.order_no',$where['order_no']);
            }
            if (!empty($where['s_time'])){
                $query->where('tbuy_order.create_time','>=',$where['s_time']);
            }
            if (!empty($where['e_time'])){
                $query->where('tbuy_order.create_time','<=',$where['e_time']);
            }
            if (!empty($where['nodecode'])){
                $query->where('t1.nodecode',$where['nodecode']);
            }
            if (!empty($where['supplier_name'])){
                $query->where('t5.supplier_name',$where['supplier_name']);
            }
            if (!empty($where['consignee_name'])){
                $query->where('t2.consignee_name',$where['consignee_name']);
            }
            if (!empty($where['mobile_no'])){
                $query->where('t2.mobile_no',$where['mobile_no']);
            }
            if (!empty($where['product_name'])){
                $query->where('tbuy_order_details.product_name',$where['product_name']);
            }
            if (!empty($where['order_type'])){
                $query->where('tbuy_order.order_type',$where['order_type']);
            }
            if (!empty($where['pay_type'])){
                $query->where('tbuy_order.pay_type',$where['pay_type']);
            }
            if (!empty($where['express_no'])){
                $query->where('t3.express_no', $where['express_no']);
            }
            if (!empty($where['sku_no'])){
                $query->where('tbuy_sku.sku_no',$where['sku_no']);
            }
            if(!empty($where['status'])){
                if($where['status']!=8){
                    $query->where('tbuy_order_details.status',$where['status']);
                }else{
                    $query->whereBetween('tbuy_order_details.status',[8,15]);//维权中
                }
            }
            if(!empty($where['keyname']) and !empty($where['keyval'])){
                    switch ($where['keyname']){
                        case 'order_no':
                            $query->where('tbuy_order.order_no',$where['keyval']);
                            break;
                        case 'product_id';
                            if(!is_numeric($where['keyval'])){break;}//防止报错
                            $query->where('tbuy_order_details.product_id',$where['keyval']);
                            break;
                        case 'product_name':
                            $query->where('tbuy_order_details.product_name','like','%'.$where['keyval'].'%');
                            break;
                        case 'supplier_name':
                            $query->where('t5.supplier_name','like','%'.$where['keyval'].'%');
                            break;
                        case 'nodecode':
                            $query->where('t1.nodecode',$where['keyval']);
                            break;
                        case 'consignee_name':
                            $query->where('t2.consignee_name','like','%'.$where['keyval'].'%');
                            break;
                        case 'mobile_no':
                            $query->where('t2.mobile_no',$where['keyval']);
                            break;
                        case 'express_no':
                            $query->where('t3.express_no',$where['keyval']);
                            break;
                            default;
                    }
            }
        }
        $res=$query->paginate($pageSize);
        foreach ($res->items() as $v){
            $v->detailsStatus=Order::parseStatus($v->status);
            $v->pay_type_str=self::parsePayTypeNum($v->pay_type_group);
        }
        return $res;
    }

    /**
     * 格式化支付类型
     *
     * @param $payTypeGroup
     * @return array
     */
    public static function parsePayTypeNum($payTypeGroup){
        $payTypeGroup = json_decode($payTypeGroup, true);
        $pay_type = [];
        foreach ($payTypeGroup as $val) {
            $pay_type[]=$val['Remarks'].'：'.$val['PayAmount']/100;//此处分换算成元
        }
        return $pay_type;
    }

    /**
     * 获取某个供货商下的T+7订单列表
     */
    public function getLists($supplierId){
        $res=DB::table('tbuy_order_details')
            ->where(function ($query){
                $query->where('tbuy_order_details.is_balance',2)->orWhere(function($query){
                    $query->where('tbuy_order_details.is_balance',1)
                        ->where('tbuy_order_details.status','>=',2)//已发货
                        ->where('tbuy_order_details.status','<',8)//不显示维权中,已退款
                        ->where('t3.delivery_time','<=',date('Y-m-d H:i:s',strtotime('-15 day')));
                });
            })
            ->where('t5.supplier_id',$supplierId)
            ->where('tbuy_order.order_type',1)
            ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product','tbuy_product.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','tbuy_product.supplier_id')
            ->select('tbuy_order_details.*','tbuy_order.pay_time')//去除多余字段
            ->get();
        return $res;
    }

    /**
     * 订单状态$DetailsStatus${未初始化 = 0,商家待发货 = 1,已发货待签收 = 2,已签收 = 4,维权中=8,已退款 = 16}
     *
     * @param $status
     * @return string
     */
    public static function parseStatus($status){
        if($status==0){
            return '未初始化';
        }elseif($status==1){
            return '商家待发货';
        }elseif($status==2){
            return '已发货待签收';
        }elseif($status==4){
            return '已签收';
        }elseif($status>=8 and $status<16){
            return '维权中';
        }elseif($status==16){
            return '已退款';
        }else{
            return '未知状态';
        }
    }

    /**
     * 根据条件查询用户订单
     *
     * @param array $where
     * @return array
     */
    public function queryOrderByIds(array $where){
        $query=DB::table('tbuy_order_details')
            ->whereIn('tbuy_order_details.details_id',$where)
            ->select(
                'tbuy_order.order_id',
                'tbuy_order.order_no', //status与详情的status发生了冲突
                'tbuy_order.node_id',
                'tbuy_order.amount',
                'tbuy_order.pay_amount',
                'tbuy_order.pay_time',
                'tbuy_order.pay_type',
                'tbuy_order.remarks',
                'tbuy_order.create_time',
                'tbuy_order.pay_type_group',
                'tbuy_order.discount_amount',
                'tbuy_order.order_type',
                'tbuy_order.order_pv',
                't2.consignee_name',
                't2.mobile_no',
                'tbuy_order_details.details_id',
                'tbuy_order_details.product_name',
                'tbuy_order_details.sku_id',
                'tbuy_order_details.is_balance',
                'tbuy_order_details.buy_count',
                'tbuy_order_details.status',
                't3.express_no',
                't3.express_company',
                't5.supplier_name',
                'tbuy_sku.sku_no',
                't1.nodecode')
            ->leftJoin('tbuy_order','tbuy_order.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tnet_reginfo t1','t1.nodeid','=','tbuy_order.node_id')
            ->leftJoin('tbuy_order_consignee t2','t2.order_id','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_logistics t3','t3.details','=','tbuy_order_details.order_id')
            ->leftJoin('tbuy_product t4','t4.product_id','=','tbuy_order_details.product_id')
            ->leftJoin('finance_supplier t5','t5.supplier_id','=','t4.supplier_id')
            ->leftJoin('tbuy_sku','tbuy_sku.sku_id','=','tbuy_order_details.sku_id')
            ->get();
        $data=[];
        foreach ($query as $k=>$v){
            $data[]=[
                $k+1,
                $v->product_name,
                $v->buy_count,
                $v->sku_no,
                $v->supplier_name,
                Order::parseStatus($v->status),
                $v->order_no,
                $v->nodecode,
                $v->create_time,
                $v->consignee_name,
                $v->mobile_no,
                implode(PHP_EOL,self::parsePayTypeNum($v->pay_type_group)),
                empty($v->express_company)?'无':$v->express_company,
                empty($v->express_company)?'无':$v->express_no
            ];
        }
        return $data;
    }
}