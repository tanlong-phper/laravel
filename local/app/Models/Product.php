<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class Product extends Base {
    protected $table = 'tbuy_product';
    /**
     * 根据商品ID号，查询商品信息
     * @param `product_id`  商品ID号
     */
    public function get_product_by_id($product_id) {
        if(!$product_id) {
            return false;
        }
        return DB::table('tbuy_product')->where('product_id',$product_id)->first();
    }

    /**
     * 查询商品SKU信息
     * @param `id`
     */
    public function get_product_sku_by_id($id) {
        if(!$id) {
            return false;
        }
        return DB::table('tbuy_sku')->where('sku_id',$id)->first();
    }

    /**
     * 模糊查询产品名称信息
     * @param `product_name`   产品名称
     **/
    public function get_product_by_product_name($product_name) {
        if(!$product_name) {
            return false;
        }

        return DB::table('tbuy_product')->select('product_id','product_name')->where('product_name','like','%'.$product_name.'%')->take(10)->get();
    }

    /**
     * 获取产品库存
     * @param `product_id`  产品ID号
     **/
    public function get_stock_by_id($product_id) {
        if(!$product_id) {
            return false;
        }

        //查询产品的库存
        $sku = DB::table('tbuy_sku')->select('stock')->where('product_id',$product_id)->first();
        return $sku->stock;
    }

    /**
     * 获取某分类商品
     * @param $class_id 分类id，默认总分类
     */
    public function get_product_by_class($class_id = 1){
        $product = DB::table('tbuy_product as tp')
                    ->leftJoin('tbuy_product_class as tpc' , 'tp.product_id' , 'tpc.product_id')
                    ->where('tpc.class_id' , $class_id)
                    ->select('tp.product_name','tp.product_id')
                    ->paginate(Config::get('app.pagesize'));

        return $product;
    }

    /**
     * 获取某分类下所有商品
     * @param $class_id 分类id，默认总分类
     */
    public function get_class_all_product($class_id = 1, $where=[]){
        $classes = $this->get_down_class($class_id);
        $product = DB::table('tbuy_product as tp')
            ->leftJoin('tbuy_product_class as tpc' , 'tp.product_id' , 'tpc.product_id')
            ->whereIn('tpc.class_id' , $classes)
            ->where($where)
            ->select('tp.product_name','tp.product_id')
            ->paginate(Config::get('app.pagesize'));
    
        return $product;
    }

    /**
     * 获取某分类下所有商品
     * @param $class_id 分类id，默认总分类
     */
    public function get_all_product($where=[['']]){
        $product = DB::table('tbuy_product as tp')
            ->leftJoin('tbuy_product_class as tpc' , 'tp.product_id' , 'tpc.product_id')
            ->where($where)
            ->select('tp.product_name','tp.product_id')
            ->orderBy('up_status' , 'desc')
            ->paginate(Config::get('app.pagesize'));

        return $product;
    }

    /**
     * 得到当前分类下所有的子分类
     * @param $class_id 分类ID，默认总分类
     */
    public function get_down_class($class_id = 1 , &$result=[]){
        if(empty($result)){
            $result = [$class_id];
        }
        $son = DB::table('tbuy_class')
                    ->where('father_id' , $class_id)
//                    ->limit(100)
                    ->pluck('class_id');
        foreach($son as $k => $v){
            $result[] = $v;
            $this->get_down_class($v, $result);
        }
        return $result;
    }

    /**
     * 商品积分回收详情
     * @param $product_id
     */
    public function recovery($product_id){
        // 取出商品的子订单，根据子订单取出总订单，再根据子订单金额和总订单的金额算出百分比，，总订单支付方式的百分比即为该商品回收的积分
        $order_details = DB::table('tbuy_order_details')
                        ->where('product_id' , $product_id)
                        ->where('status' , 1)
                        ->get();

        $shop = $hl = $cash = 0;
        foreach($order_details as $detail){
            //根据子订单取出总订单
            $order = DB::table('tbuy_order')->where("order_id" , $detail->order_id)->first();
            if(!$order){
                echo $detail->order_id.'<br/>';
                continue;
            }

            // 根据子订单金额和总订单的金额算出百分比
            $percent = $detail->amount / $order->amount;

            // 总订单支付方式的百分比即为该商品回收的积分
            $pay_type = json_decode($order->pay_type_group , true);
            foreach($pay_type as $t){

                switch($t['PayType']){
                    case 1 : {
                        $shop += $t['PayAmount'] * $percent / 100; // 数据库存储的为分
                        break;
                    }
                    case 2 : { // 全球积分
                        $hl += $t['PayAmount'] * $percent / 100;
                        break;
                    }
                    case 16 : { // 微信app

                    }
                    case 8 : { // 微信jsapi

                    }
                    case 32 : { // 支付宝
                    }
                    case 256 : { // 快捷支付
                        $cash += $t['PayAmount'] * $percent / 100;
                        break;
                    }
                }
            }
        }

        return [
            'shop' => round($shop , 2),
            'hl'   => round($hl , 2),
            'cash' => round($cash , 2),
        ];
    }
}