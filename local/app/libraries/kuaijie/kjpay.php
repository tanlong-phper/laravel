<?php
header("Content-type: text/html; charset=utf-8");//输出文本格式
require (dirname(__FILE__).'/Yfslpay_Unitpay.class.php');

$params_arr = array(//订单信息
	'requestNo'=>'LS200112424222116',
	'orderNo'=>'OD200412232112388'.time(),
	'transAmt'=>'0.01',
	'goodsname'=>'testproduct',

	'cardName' => '高敦明',
	'phoneNo' => '13501593761',
	'cardIdcardType' => '01',
	'cardIdcardNo' => '420117199306094313',
	'cardNo' => '6210817200012810139',
	'cardType' => '01',
	'expDate' => '',
	'cvn2' => '',
	);
	
$yfslpay_unitpay = new Yfslpay_Unitpay($params_arr,'');
$return_param= $yfslpay_unitpay -> kjpay();//微信二维码支付

echo '<pre>';
var_dump($return_param);
//返回参数中的codeUrl为二维码链接
?>

<html>
<img src="<?php echo $return_param['imgUrl'] ?>" width='200px' height='200px'/>
</html>
