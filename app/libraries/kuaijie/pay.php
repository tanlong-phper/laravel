<?php
header("Content-type: text/html; charset=utf-8");//输出文本格式
require (dirname(__FILE__).'/Yfslpay_Unitpay.class.php');

$params_arr = array(//订单信息
	'requestno'=>'LS200112424222116',
	'orderNo'=>'OD200412232112388'.time(),
	'transAmt'=>'0.01',
	'goodsname'=>'testproduct'
	);
	
$yfslpay_unitpay = new Yfslpay_Unitpay($params_arr,'');
$return_param= $yfslpay_unitpay -> qrpay();//微信二维码支付

echo '<pre>';
var_dump($return_param);
//返回参数中的codeUrl为二维码链接
?>

<html>
<img src="<?php echo $return_param['imgUrl'] ?>" width='200px' height='200px'/>
</html>
