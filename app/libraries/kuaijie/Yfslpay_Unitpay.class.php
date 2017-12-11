<?php
namespace App\Libraries\Yfslpay_unitpay;
use App\Libraries\Yfslpay_Rsa\Yfslpay_Rsa;

error_reporting(0);
/**
 * User: Administrator
 * Date:
 * Time:
 */
require (__DIR__.'/Yfslpay_Rsa.class.php');

class Yfslpay_unitpay
{
//    private $requesturl =  "http://119.23.136.94/services/gateway/api/backTransReq";#测试接口地址
    private $requesturl = "http://apps.yfslpay.com/services/gateway/api/backTransReq"; // 正式接口地址

//    private $merNo = '850440058115206'; #商户号 // 测试版
    private $merNo = '850440059705220'; // 正式版

//    private $pubfile = '/rsafile/850440058115206_pub.pem'; #测试公钥
    private $pubfile = '/rsafile/1005009_pub.pem'; #正式公钥

//    private $prifile = '/rsafile/850440058115206_prv.pem'; #测试私钥
    private $prifile = '/rsafile/1005009_prv.pem'; #正式私钥

    private $productId      = '';

    private $transId      = ''; 
	
    private $payment;

    private $order;




    public function __construct($payment_info,$order_info){
        $this->yfslpay($payment_info,$order_info);
    }
    public function yfslpay($payment_info = array(),$order_info = array()){
        if(!empty($payment_info)){
            $this->payment	= $payment_info;
        }
        if(!empty($order_info)){
            $this->order	= $order_info;
        }
    }

    public function qrpay(){
        $this->productId ='0108';
        $this->transId = '17';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestno'],					#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->productId,						#产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => date('Ymd'),					            #订单日期
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'returnUrl' => MSYMURL."yfslpay/return_url.php",	#页面通知地址
            'notifyUrl' => MSYMURL."yfslpay/notify_url.php",	#异步通知地址
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
        return $pay_params;//返回参数中包含二维码图片地址，将该地址通过html解析成图片，展示给用户
    }

	//支付宝扫码支付
    public function aliqrpay(){
        $this->productId ='0107';
        $this->transId = '17';
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestNo'],				#交易请求流水号
            'version' => "V1.0",									#版本号
            'productId' => $this->productId,						#产品类型
            'transId' => $this->transId ,					        #交易类型
            'merNo' => $this->merNo,								#商户号
            'orderDate' => date('Ymd'),					            #订单日期
            'orderNo' =>  $this->payment['orderNo'],					#商户订单号
            'returnUrl' => MSYMURL."yfslpay/return_url.php",	#页面通知地址
            'notifyUrl' => MSYMURL."yfslpay/notify_url.php",	#异步通知地址
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
        return $pay_params;//返回参数中包含支付宝二维码地址，用户在支付宝浏览器中访问该地址，或者将该二维码地址解析为图片，让用户扫描， 完成支付
    }
	
	//其他支付方式请参考对接文档自主编写，如公众号支付、代付、快捷等
	public function wxjsppay(){
		
	}
	
    // 快捷支付
	public function kjpay(){
		$this->productId ='0302';
        $this->transId = '15';
        $params_post = array(
            'requestNo' =>  $this->payment['requestNo'],                #交易请求流水号
            'version' => "V1.0",                                    #版本号
            'productId' => $this->productId,                        #产品类型
            'transId' => $this->transId ,                           #交易类型
            'merNo' => $this->merNo,                                #商户号
            'orderDate' => date('Ymd'),                             #订单日期
            'orderNo' =>  $this->payment['orderNo'],                    #商户订单号
            'returnUrl' => $this->payment['returnUrl'],    #页面通知地址
            'notifyUrl' => $this->payment['notifyUrl'],    #异步通知地址
            'transAmt' => $this->payment['transAmt']*100,               #交易金额
            'commodityName' => $this->payment['goodsname'],                     #商品名称

            // 银行卡信息
            'cardName' => $this->payment['cardName'], // 持卡人姓名
            'phoneNo' => $this->payment['phoneNo'], // 手机号码
            'cardIdcardType' => $this->payment['cardIdcardType'], // 证件类型
            'cardIdcardNo' => $this->payment['cardIdcardNo'], // 证件号
            'cardNo' => $this->payment['cardNo'], //卡号
            'cardType' => $this->payment['cardType'], // 卡片类型
            'expDate' => $this->payment['expDate'], // 卡片有效期
            'cvn2' => $this->payment['cvn2'], // 卡片附加码
        );
        // 过滤空值
        $params_post = array_filter($params_post);

        #签名
        $params_post["signature"] = $this->getignature($params_post);

        // mydebug($params_post);
        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含payUrl， 直接跳转到此地址，进行付款
	}
	
    // 余额代付
	public function transferpay(){
		$this->productId ='0201'; // 产品类型,0201非垫资 0203垫资
        $this->transId = '07'; 
        define('MSYMURL', 'http://'.$_SERVER['HTTP_HOST'].'/');
        $params_post = array(
            'requestNo' =>  $this->payment['requestNo'],                #交易请求流水号
            'version' => "V1.0",                                    #版本号
            'productId' => $this->productId,                        #产品类型
            'transId' => $this->transId ,                           #交易类型
            'merNo' => $this->merNo,                                #商户号
            'orderDate' => date('Ymd'),                             #订单日期
            'orderNo' =>  $this->payment['orderNo'],                    #商户订单号
            'notifyUrl' => $this->payment['notifyUrl'],    #异步通知地址
            'transAmt' => $this->payment['transAmt']*100,               #交易金额
            'isCompay' => '0', // 对公对私标识 0为对私，1为对公

            // 代付账号银行卡信息
            'customerName' => $this->payment['customerName'], // 代付账户持卡人姓名
            'phoneNo' => $this->payment['phoneNo'], // 代付账户手机号码
            'cerdType' => $this->payment['cerdType'], // 代付账户证件类型
            'cerdId' => $this->payment['cerdId'], // 代付账户证件号
            'accBankNo' => $this->payment['accBankNo'], // 收款账户银行卡号
            'accBankName' => $this->payment['accBankName'], // 收款账户银行卡开户行
            'bankNo' => $this->payment['bankNo'], // 银行联行号
            'acctNo' => $this->payment['acctNo'],// 代付账号银行卡号
            'note' => $this->payment['note'],// 代付摘要
        );
        // 过滤空值
        $params_post = array_filter($params_post);
        $params_post['isCompay'] = 0;

        #签名
        $params_post["signature"] = $this->getignature($params_post);

        // mydebug($params_post);
        #发起请求
        $Response_Contents = $this->getHttpResponsePOST($this->requesturl, $params_post);

        #遍历结果
        $pay_params = $this->setContents($Response_Contents);
        //var_dump($pay_params);
        return $pay_params;//返回参数中包含payUrl， 直接跳转到此地址，进行付款
	}
	
	public function checkorderstatus(){
		
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
                #需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
                #并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复确认到账的情况发生.
                #商户自行处理平台逻辑
				
                echo 'SUCCESS';
            }
        }else{
            echo "Data_error";
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
        $yfslpay_rsa = new Yfslpay_Rsa($parentDirName.$this->pubfile, $parentDirName.$this->prifile);
        $signature = $yfslpay_rsa->sign($signstring);

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
        $yfslpay_rsa = new Yfslpay_Rsa($parentDirName.$this->pubfile, $parentDirName.$this->prifile);
        $result = $yfslpay_rsa->verify($signstring,$returnsignature);

        return $result;
    }

    #处理响应结果
    public function setContents($Response_Contents){
        $result = explode("&",$Response_Contents);#拆分返回值
        $params = array();
        #遍历结果
        for($index=0;$index<count($result);$index++){//数组循环
            $aryReturn = explode("=",$result[$index],2);
            $sKey = $aryReturn[0];
            $sValue = $aryReturn[1];
            $params[$sKey] = $sValue;
        }
        return $params;
    }




}

?>