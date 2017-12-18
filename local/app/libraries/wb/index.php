<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/1
 * Time: 17:00
 */
require (dirname(__FILE__).'/Hsbpay_Unitpay.class.php');

//$payment_info=[];
//$order_info=[];
//$Hsbpay_unitpay=new Hsbpay_unitpay($payment_info,$order_info);
//var_dump($Hsbpay_unitpay->checkbalance());

$order_sn='4201708027962031501644233';
//$payment_info=[
//    'productId'=>'2001',
//    'notifyUrl'=>'http://baidu.com',
//    'transAmt'=>'2',
//    'orderDate'=>date('Ymd'),
//    'orderNo'=>$order_sn,
//    'isCompay'=>'1',
//    'phoneNo'=>'110',
//    'customerName'=>'肖泽鑫1',
//    'cerdId'=>'110',
//    'accBankNo'=>'110',
//    'accBankName'=>'中国平安银行',
//    'acctNo'=>'6230580000081805767',
//    'note'=>'供应商货款结算'
//];
//$order_info=[];
//$Hsbpay_unitpay=new Hsbpay_unitpay($payment_info,$order_info);
//var_dump($Hsbpay_unitpay->transferpay());

$payment_info=[
    'orderDate'=>date('Ymd'),
    'orderNo'=>$order_sn,
    'orderPayType'=>'02',
];
$order_info=[];
$Hsbpay_unitpay=new Hsbpay_unitpay($payment_info,$order_info);
var_dump($Hsbpay_unitpay->checkorderstatus());


/**
 * 生成订单号 order_no
 * @param $order_id 订单号
 * @param int $pre 前缀 2-购物订单 3-充值订单 4-vip套餐购买 5-好项目购买 6-免费领取手机 7-免费领取手机线下代理支付
 * @return string
 */
function create_order_no($order_id , $pre=2){
    $pad = str_pad($order_id,8,'0',STR_PAD_LEFT);
    $order_no  = date('Ymd').rand(100000,999999).$pad;
    return $pre.$order_no;
}