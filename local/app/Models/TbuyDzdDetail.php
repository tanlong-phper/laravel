<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26 0026
 * Time: 14:07
 */

namespace App\Models;


class TbuyDzdDetail extends Base
{
    /**
     * T+1详情表
     *
     * @var string
     */
    protected $table = 'tbuy_dzd_detail';


    /**
     * 格式化输入的表单录入到详情表中
     *
     * @param $id
     * @param array $data
     * @return array
     */
    public static function parseFormData($id,array $data){
        $tmp=[];
        foreach ($data['details_id'] as $k=>$v){
            $tmp[]=[
                'cid'=>TbuyDzdDetail::getNextSeq(),
                'dzd_id'=>$id,
                'details_id'=>$v,
                'nodecode'=>$data['nodecode'][$k],
                'consignee_name'=>$data['consignee_name'][$k],
                'mobile_no'=>$data['mobile_no'][$k],
                'all_address'=>$data['all_address'][$k],
                'product_name'=>$data['product_name'][$k],
                'cost_price'=>$data['cost_price'][$k],
                'buy_count'=>$data['num1'][$k],
                'finance_charge'=>$data['num2'][$k],
                'amount'=>$data['num3'][$k],
                'pay_time'=>$data['pay_time'][$k],
                'pay_type_str'=>$data['pay_type_str'][$k],
                'remark'=>$data['remark'][$k],
                'supplier_id'=>$data['supplier_id'][$k]
            ];
        }
        return $tmp;
    }

    /**
     * 获取T+1对账单详情
     *
     * @param $dzdId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDetail($dzdId){
        return $this->from('tbuy_dzd_detail t0')
            ->where('t0.dzd_id',$dzdId)
            ->leftJoin('tbuy_order_details t1','t0.details_id','=','t1.details_id')
            ->leftJoin('tbuy_order t2','t1.order_id','=','t2.order_id')
            ->select('t0.*','t2.order_no')
            ->get();
    }
}