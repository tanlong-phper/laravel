<?php
header("Content-type: text/html; charset=utf-8");//输出文本格式
require (dirname(__FILE__).'/Hsbpay_Unitpay.class.php');

$params_arr = array(//订单信息
	'requestno'=>'LS20011242423311',
	'orderNo'=>'OD200412231232326',
	'transAmt'=>'100',
	'goodsname'=>'testproduct',
	'subMerNo'=>'0000022',
	'subMerName'=>'测试商户',
	);
	
$hsbpay_unitpay = new Hsbpay_Unitpay($params_arr,'');
$return_param= $hsbpay_unitpay -> qrpay();//微信二维码支付

var_dump($return_param);
//返回参数中的codeUrl为二维码链接imgUrl为二维码图片地址
?>

<html>
<img src="<?php echo $return_param['imgUrl'] ?>" width='200px' height='200px'/>
</html>
