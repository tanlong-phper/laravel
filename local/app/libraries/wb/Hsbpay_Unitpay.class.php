<?php
namespace App\Libraries\wb_sdk;
error_reporting(0);
/**
 * User: Administrator
 * Date:
 * Time:
 */
require (dirname(__FILE__).'/Hsbpay_Rsa.class.php');


class Hsbpay_unitpay
{
    private $requesturl =  "http://m.iicpay.com/api/index";#测试接口地址

    private $merNo = '8800345000054'; #商户号

    private $pubfile = '/rsafile/8800345000054_mp_pub.pem'; #公钥

    private $prifile = '/rsafile/8800345000054_m_prv.pem'; #私钥

    private $productId      = '';

    private $transId      = ''; 
	
    private $payment;

    private $order;




    public function __construct($payment_info,$order_info){
        $this->hsbpay($payment_info,$order_info);
    }
    public function hsbpay($payment_info = array(),$order_info = array()){
        if(!empty($payment_info)){
            $this->payment	= $payment_info;
        }
        if(!empty($order_info)){
            $this->order	= $order_info;
        }
    }

    public function qrpay(){
        $this->productId ='1001';
        $this->transId = '10';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestno'],					#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->productId,						#产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => date('Ymd'),					            #订单日期
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'returnUrl' => MSYMURL."hsbpay/return_url.php",	#页面通知地址
            'notifyUrl' => MSYMURL."hsbpay/notify_url.php",	#异步通知地址
            'transAmt' => $this->payment['transAmt']*100,				#交易金额
            'commodityName' => $this->payment['goodsname'],					    #商品名称
			'subMerNo' => $this->payment['subMerNo'],						#支付商户识别id
			'subMerName' => $this->payment['subMerName'],					#支付收款商户名称
        );
        #签名
        $params_post["signature"] = $this->getignature($params_post);

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
		//var_dump($pay_params);
        return $pay_params;//返回参数中包含二维码图片地址，将该地址通过html解析成图片，展示给用户
    }

	//支付宝扫码支付
    public function aliqrpay(){
        $this->productId ='1006';
        $this->transId = '10';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestNo'],				#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->productId,						#产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => date('Ymd'),					            #订单日期
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'returnUrl' => MSYMURL."hsbpay/return_url.php",	#页面通知地址
            'notifyUrl' => MSYMURL."hsbpay/notify_url.php",	#异步通知地址
            'transAmt' => $this->payment['transAmt']*100,				#交易金额
            'commodityName' => $this->payment['goodsname'],					    #商品名称
			'storeId' => $this->payment['storeId'],					#商户门店编号
			'terminalId' => $this->payment['terminalId'],			#商户机具终端编号
        );
        #签名
        $params_post["signature"] = $this->getignature($params_post);

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含支付宝二维码地址，用户在支付宝浏览器中访问该地址，或者将该二维码地址解析为图片，让用户扫描， 完成支付
    }
	
	//微信公众号支付
	public function wxjspay(){
		$this->productId ='1002';
        $this->transId = '10';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestno'],					#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->productId,						#产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => date('Ymd'),					            #订单日期
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'returnUrl' => MSYMURL."hsbpay/return_url.php",	#页面通知地址
            'notifyUrl' => MSYMURL."hsbpay/notify_url.php",	#异步通知地址
            'transAmt' => $this->payment['transAmt']*100,				#交易金额
            'commodityName' => $this->payment['goodsname'],					    #商品名称
        );
        #签名
        $params_post["signature"] = $this->getignature($params_post);

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
		//var_dump($pay_params);
        return $pay_params;//返回参数中包含公众号支付地址payUrl，微信浏览器中跳转到该地址完成支付，或者通过执行payHtml，完成支付
	}
	
	//其他支付方式请参考对接文档自主编写，如代付、订单查询等

    //代付
	public function transferpay(){
        $this->transId = '06';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->create_order_no(time(),4),					#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->payment['productId'],			#产品类型 2001-普通代付 2003-额度代付
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => $this->payment['orderDate'],								#代付订单申请日期yyyyMMdd
            'orderNo' => $this->payment['orderNo'],								#商户订单号
            'notifyUrl' => $this->payment['notifyUrl'],				#预留字段，升级后使用
            'transAmt' => $this->payment['transAmt']*100,			#交易金额
            'isCompay' => $this->payment['isCompay'],				#对公对私标识1为对私，2为对公
            'phoneNo' => '110',					#代付银行手机号
            'customerName' => $this->payment['customerName'],									#代付账户名称
            'cerdType' => "01",									#代付证件类型 默认身份证
            'cerdId' => '110',									#证件号
            'accBankNo' => '110',									#收款账户开户行号
            'accBankName' => '110',							#收款账户开户行名称
            'acctNo' =>$this->payment['acctNo'],							#银行卡号
            'note' =>$this->payment['note'],							#代付摘要
        );

        #签名
        $params_post["signature"] = $this->getignature($params_post);
//        var_dump($params_post);exit;

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含公众号支付地址payUrl，微信浏览器中跳转到该地址完成支付，或者通过执行payHtml，完成支付
	}

    //查看支付状态
	public function checkorderstatus(){
        $this->transId = '05';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->create_order_no(time(),4),					#交易请求流水号
            'version' => "V1.0",									#版本号	            #产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => $this->payment['orderDate'],					            #原商品订单的日期yyyyMMdd
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'orderPayType' => $this->payment['orderPayType'],	#01消费、02代付
        );
        #签名
        $params_post["signature"] = $this->getignature($params_post);

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含公众号支付地址payUrl，微信浏览器中跳转到该地址完成支付，或者通过执行payHtml，完成支付

    }

    //查询账户信息
    public function checkbalance(){

        $this->transId = '08';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->create_order_no(time(),4),					#交易请求流水号
            'version' => "V1.0",									#版本号
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
        );

        #签名
        $params_post["signature"] = $this->getignature($params_post);
//        var_dump($params_post);

        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含公众号支付地址payUrl，微信浏览器中跳转到该地址完成支付，或者通过执行payHtml，完成支付
    }

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
	
    public function return_verify(){//同步
        $params_return = $this->order;
        $return_signature = $params_return[signature];

        $params_return=array_filter($params_return);#过滤空元素
        unset($params_return[signature]);
        #签名
        $checksignature = $this->checksignature($params_return,$return_signature);

        if($checksignature){
            if($params_return['respCode']=="0000"){
                return true;
            }
        }else{
            return false;
        }
    }


    public function notify_verify() {//异步
        $params_return = $this->order;
        $return_signature = $params_return[signature];

        $params_return=array_filter($params_return);#过滤空元素
        unset($params_return[signature]);
        #签名
        $checksignature = $this->checksignature($params_return,$return_signature);

        if($checksignature){
            if($params_return['respCode']=="0000"){
                return true;
            }
        }else{
            return false;
        }
    }


    public function getHttpResponsePOST($url, $para=array()) {
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT,30);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($para));

        //执行并获取url地址的内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }

    #生成签名
    public function getignature($params_signature){
        ksort($params_signature);//自然排序
        $signstring = "";
        $i=0;
        foreach ($params_signature as $key => $val){
            $signstring = $signstring.($i==0 ? $key."=".$val : ('&'.$key."=".$val));
            $i++;
        }

        #调用证书生成签名
        $parentDirName = dirname(__FILE__);
        $hsbpay_rsa = new Hsbpay_Rsa($parentDirName.$this->pubfile, $parentDirName.$this->prifile);
        $signature = $hsbpay_rsa->sign(sha1($signstring));

        return $signature;
    }
	#验证签名
	public function checksignature($params_signature,$returnsignature){
        ksort($params_signature);//自然排序
        $signstring = "";
        $i=0;
        foreach ($params_signature as $key => $val){
            $signstring = $signstring.($i==0 ? $key."=".$val : ('&'.$key."=".$val));
            $i++;
        }
		
        #调用证书验证签名
        $parentDirName = dirname(__FILE__);
        $hsbpay_rsa = new Hsbpay_Rsa($parentDirName.$this->pubfile, $parentDirName.$this->prifile);
        $result = $hsbpay_rsa->verify(sha1($signstring),$returnsignature);

        return $result;
    }

    #处理响应结果
    public function setContents($Response_Contents){
        return json_decode($Response_Contents,true);
    }




}

?>