<?php
require (dirname(__FILE__).'/Hsbpay_Unitpay.class.php');

$retrun_params = array();
$retrun_params = $_POST;

//日志
//$str=json_encode($retrun_params);
//$open=fopen("notify.txt","a" );
//fwrite($open,date("YmdHis",time()).'['.$str.']\n');
//fclose($open);

$hsbpay_unitpay = new Hsbpay_Unitpay('',$retrun_params);
$return_param= $hsbpay_unitpay->notify_verify();

if($return_param){
	#需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
	#并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复确认到账的情况发生.
	#商户自行处理平台逻辑
	
	echo 'SUCCESS';//平台处理完自己的业务逻辑后输出该字符串，页面不能有SUCCESS以外其他内容
}else{
	echo 'Data_Error';
}

?>