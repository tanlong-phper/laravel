<?php
require (dirname(__FILE__).'/Hrpay_Wxpay.class.php');

$retrun_params = array();
$retrun_params = $_POST;

//日志
//$str=json_encode($retrun_params);
//$open=fopen("notify.txt","a" );
//fwrite($open,date("YmdHis",time()).'['.$str.']\n');
//fclose($open);

$yfslpay_unitpay = new Yfslpay_Unitpay('',$retrun_params);
$return_param= $yfslpay_unitpay->notify_verify();

?>