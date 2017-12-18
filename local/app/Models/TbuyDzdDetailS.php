<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27 0027
 * Time: 10:54
 */

namespace App\Models;


class TbuyDzdDetailS extends Base
{
    /**
     * T+7 详情表
     *
     * @var string
     */
    protected $table = 'tbuy_dzd_detail_s';


    /**
     * 计算T+7 的数额列表
     *
     * @param $getLists
     * @return array
     */
    public static function countDetails($getLists)
    {
        $ids=[];
        $settle_price=0;
        $total_sales=0;
        foreach ($getLists as $v){
            $settle_price+=$v->cost_price*$v->buy_count;
            $total_sales+=$v->amount;
            $ids[]=$v->details_id;
        }
        $service_charge=self::calServiceCharge($settle_price);
        return [
            'settle_price'=>$settle_price,//结算总价
            'total_sales'=>$total_sales,//销售总额
            'service_charge'=>$service_charge,//手续费
            'details_ids'=>implode(',',$ids)
        ];
    }

    /**
     * 将支付时间拼成数组
     *
     * @param $getLists
     * @return array
     */
    public static function calPayTime($getLists){
        $arr=[];
        foreach ($getLists as $v){
            $arr[]=$v->pay_time;
        }
        return $arr;
    }

    /**
     *计算手续费
     *
     * @param $money
     * @return int
     */
    public static function calServiceCharge($money){
        if($money<=10000){
            return 5;
        }elseif ($money>10000 and $money<=100000){
            return 10;
        }elseif ($money>10000 and $money<=500000){
            return 15;
        }elseif ($money>50000 and $money<=1000000){
            return 20;
        }else{
            return 200;
        }
    }

    /**
     * 格式化录入T+7对账单详情中
     *
     * @param $id
     * @param array $data
     * @return array
     */
    public static function parseFormData($id,array $data){
        $tmp=[];
        foreach ($data['supplier_id'] as $k=>$v){
            $tmp[]=[
                'cid'=>TbuyDzdDetail::getNextSeq(),
                'dzd_id'=>$id,
                'supplier_id'=>$v,
                'supplier_name'=>$data['supplier_name'][$k],
                'finance_charge'=>$data['finance_charge'][$k],
                'service_charge'=>$data['service_charge'][$k],
                'total_sales'=>$data['total_sales'][$k],
                'remark'=>$data['remark'][$k],
                'details_ids'=>$data['details_ids'][$k]
            ];
        }
        return $tmp;
    }

    /**
     * 获取T+7对账单详情
     *
     * @param $dzdId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDetail($dzdId){
        return $this->where('tbuy_dzd_detail_s.dzd_id',$dzdId)
            ->leftJoin('finance_supplier t1','t1.supplier_id','=','tbuy_dzd_detail_s.supplier_id')
            ->select('tbuy_dzd_detail_s.*','t1.*')
            ->get();
    }

}