<?php
header("Content-type: text/html; charset=utf-8");//输出文本格式
require (dirname(__FILE__).'/Hsbpay_Unitpay.class.php');

$params_arr = array(//订单信息
	'requestno'=>'LS2001124242255456',
	'orderNo'=>'OD200412232115657',
	'transAmt'=>'100',
	'goodsname'=>'testproduct',
	);
	
$hsbpay_unitpay = new Hsbpay_Unitpay($params_arr,'');
$return_param= $hsbpay_unitpay -> wxjspay();//微信公众号支付

var_dump($return_param);
//返回参数中的payUrl为公众号支付链接，payHtml为公众号支付html代码
?>
<?php if($return_param[payUrl]){ ?>
<html>

   <script language="javascript">
			window.location.href="<?php echo $return_param[payUrl];?>";
	</script>

</html>
<?php } ?>

<?php if($return_param[payHtml]){ ?>
<?php echo $return_param[payHtml];?>
<?php } ?>