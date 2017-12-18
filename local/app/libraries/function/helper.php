<?php

/**
 * 调试信息输出
 * @param $args
 * @param bool $exit
 */
function mydebug($args , $exit = true){
	echo '<pre>';
	echo '<meta charset="utf-8">';
	print_r($args);
	echo '</pre>';
	$exit && exit;
}


/**
 * 记文件形式调试信息,也可用于记录日志
 * @param $data //内容
 * @param string $filepath 文件存放路径 如为 ./log或log则为日志记录,自动加上日期文件夹
 */
//function filedebug($data , $filepath='./filedebug.php'){
function filedebug($data , $filepath='./filedebug'){    
	// 如果是日志，则将日志分隔成日期文件夹
	$date = date('Ym/d');
	//$filepath = str_replace('log/' , "log/$date/" , $filepath);
	$filepath = "../storage/logs/$date/".$filepath.".php";
	$have = strripos($filepath , '/');
	$path = (false === $have ? '' :  substr($filepath , 0 , $have+1)); // 文件夹
	$filearr=explode('/',$filepath);
	$file = end($filearr) ?:'default.php'; // 文件名
	if($path && !is_dir($path)){
		mkdir($path,0777,true);
	}
	$filepath = $path . $file;
	//var_dump($filepath); exit();
	$f = fopen($filepath , 'a+');
	fwrite($f , date('Y-m-d H:i:s').'----------'."\n");
	fwrite($f , var_export($data , true));
	fwrite($f , "\n".'----------');
	fclose($f);
}

// 得到客户端ip
function getIp() {
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR'];
	else $ip = "127.0.0.1";
	return ($ip);
}

// 生成 guid / uuid
function guid(){
	if (function_exists('com_create_guid')){
		return trim(com_create_guid(),'{}"');
	}else{
		mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
		$charid = strtoupper(md5(uniqid(rand(), true)));
		$hyphen = chr(45);// "-"
//		$uuid = chr(123)// "{"
//			.substr($charid, 0, 8).$hyphen
//			.substr($charid, 8, 4).$hyphen
//			.substr($charid,12, 4).$hyphen
//			.substr($charid,16, 4).$hyphen
//			.substr($charid,20,12)
//			.chr(125);// "}"
		$uuid = substr($charid, 0, 9).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,19,12);
		return $uuid;
	}
}

/**
 * @param $proArr	// 一个一维数组
 * @return int|string
 * 一个随机算法
 */
function get_rand($proArr) {
	$result = '';
	
	//概率数组的总概率精度
	$proSum = array_sum($proArr) * 10;
	
	//概率数组循环
	foreach ($proArr as $key => $proCur) {
		$randNum = mt_rand(1, $proSum);
		$proCur *= 10;
		if ($randNum <= $proCur) {
			$result = $key;
			break;
		} else {
			$proSum -= $proCur;
		}
	}
	unset ($proArr);
	
	return $result;
}

/**
 * 将数据导出EXCEL
 * @param  [array 一维数组] $title   [标题]
 * @param  [array 二维数组] $content [导出内容]
 * @param  [string] $filename [文件名,默认为data.xls]
 */
function exportData($title , $content , $filename = 'data'){
//	$title = array('标题a' , '标题b' , '标题c');
//	$content = array(
//		array('aa' , 'bb' , 'cc'),
//		array('dd' , 'ee' , 'ff'),
//		array('gg' , 'hh' , 'ii'),
//	);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=' . $filename . '.xls');
	header('Pragma: no-cache');
	header('Expires: 0');

	echo iconv('utf-8', 'gbk', implode("\t", $title)), "\n";
	foreach ($content as $value) {
		echo iconv('utf-8', 'gbk', implode("\t", $value)), "\n";
	}
	exit();
}

/**
 * 添加压缩包
 * @param $file 目标压缩包路径
 * @param $filename 待添加至压缩包的文件路径
 */
function addZip($file,$filename){
	if(!$file || !$filename){
		return false;
	}
	//实例化类
	$zip = new ZipArchive();
	//需要打开的zip文件,文件不存在将会自动创建

	if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {
		//如果是Linux系统，需要保证服务器开放了文件写权限
		exit("文件打开失败!");
	}

	//将test.php文件添加到压缩文件中
	$zip->addFile($file);

//	//输出加入的文件数 , 这里应该是 2
//	echo "文件数 : ".$zip->numFiles;

	//关闭文件
	$zip->close();
}

/**
 * @param $time
 * @return bool|string
 * 时间格式化
 */
function time2date($time){
	if(!$time) return 0;
	$date = date('Y-m-d H:i:s',$time);
	return $date;
}
function time2date_date($time){
	if(!$time) return 0;
	$date = date('Y-m-d',$time);
	return $date;
}
function time2zh($time){
	$now_time = NOW_TIME;
	$zh = time2date($time);
	$diff = $now_time - $time;
	if($diff < 60){
		$zh = $diff.' 秒前';
	}elseif($diff < 3600){
		$zh = floor($diff/60).' 分钟前';
	}elseif($diff < 3600 * 24) {
		$zh = floor($diff/3600).' 小时前';
	}elseif($diff < 3600 * 24 * 7){
		$zh = floor($diff/86400).' 天前';
	}
	return $zh;
}
function expire2zh($expire_time){
	$now_time = NOW_TIME;
	// 过期时间减去当前时间，得到剩余秒数
	$cha = $expire_time - $now_time;
	if($cha <= 0){
		$zh = '<div class="time gray" style="background-color:#666666;">过期';
		$tian = ceil(-$cha / 86400);
	}else{
		$zh = '<div class="time">剩余';
		$tian = ceil($cha / 86400);
	}
	return $zh.' '.$tian.' 天</div>';
}

/**
 * 将php对象转为数组
 * @param $object
 * @return mixed
 */
function object2array($object) {
	if (is_object($object)) {
		foreach ($object as $key => $value) {
			$array[$key] = $value;
		}
	}
	else {
		$array = $object;
	}
	return $array;
}

/**
 * 将对象转为php数组
 * @param $array
 * @return StdClass
 */
function array2object($array) {
	if (is_array($array)) {
		$obj = new StdClass();
		foreach ($array as $key => $val){
			$obj->$key = $val;
		}
	}
	else {
		$obj = $array;
	}
	return $obj;
}

//url base64编码
function urlsafe_b64encode($string) {
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}
//url base64解码
function urlsafe_b64decode($string) {
	$data = str_replace(array('-','_'),array('+','/'),$string);
	$mod4 = strlen($data) % 4;
	if ($mod4) {
		$data .= substr('====', $mod4);
	}
	return base64_decode($data);
}

//字符串转十六进制
function strToHex($string){
	$hex="";
	for($i=0;$i<strlen($string);$i++)
		$hex.=dechex(ord($string[$i]));
	$hex=strtoupper($hex);
	return $hex;
}

//十六进制转字符串
function hexToStr($hex){
	$string="";
	for($i=0;$i<strlen($hex)-1;$i+=2)
		$string.=chr(hexdec($hex[$i].$hex[$i+1]));
	return  $string;
}

// 模板自动指定名称
function views($blade_name = null,$data = []){
	if(empty($blade_name)){
		$module = substr(strrchr(Route::current()->getAction()['namespace'],'\\'),1);
		$route = Route::current()->uri();
		$blade_name = strtolower($module.'.'.str_replace('/','.',$route));
	}
	return view($blade_name,$data);
}


//js跳转
function jq_redirect($url){
	echo "<scritp language='javascript' type='text/javascript'> location.href=".$url.";</scritp>";
}

// base64 上传图片
function base64_upload($type,$field,$callback = ''){
	$sBase64 = request($field);
	$file_name_pre = '/storage/'.$type.'/'.date('Y-m-d').'/';
	$return = array();
	if(empty($sBase64)){
		@$callback([]);
		return false;
	}
	if(!is_array($sBase64)){
		$sBase64 = array($sBase64);
	}
	foreach($sBase64 as $base64){
		if(strpos($base64 , "base64,")){
			$base64 = explode('base64,' , $base64)[1];
		}
		$base64 = base64_decode($base64);
		if(empty($base64)) continue;
		
		$file_name = $file_name_pre.uniqid(date('Ymd').rand('1000','9999')).'.png';
		$save_path = '.'.$file_name;
		if(!is_dir('.'.$file_name_pre)){
			mkdir('.'.$file_name_pre,0777,true);
		}
		file_put_contents($save_path,$base64);
		$return[] = $file_name;
	}
	@$callback($return);
	return true;
}

/**
 * @param $files
 * @return mixed
 * 上传到文件服务器
 */
function curl_upfile($files){
    $cwd = rtrim(getcwd(), '/') . '/';
    
    $ch = curl_init();
	$post = array();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15','Content-Type: multipart/form-data'));
	curl_setopt($ch, CURLOPT_URL,env('FILE_SERVER_URL'));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// same as <input type="file" name="file_box">
	$post = array(
		'AppId'		=> env('FILE_SERVER_APPID'),
		'SafeCode'	=> env('FILE_SERVER_SAFECODE'),
		'Thumnail'	=> '0',
//		'file_box'	=> "@".getcwd().$files,
		'image[]'	=> curl_file_create($cwd . $files),
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$response = curl_exec($ch);
	if(curl_errno($ch)){	//出错则显示错误信息
		throw new Exception('上传图片文件过大');
	}
	curl_close($ch); //关闭curl链接
	unlink($cwd . $files);
	preg_match('/<fullpath.*fullpath>/i',$response,$match);
	return strip_tags($match[0]);
}

	/**
	 * 记录用户行为日志
	 **/
	function record_action($action_id,$controller='',$action='',$remark='',$request_info='') {
		//获取管理员信息
	
		$node_id = $_SESSION['admin_uid'];
		$node_info = DB::table('tpc_admin')
					 ->where(['id'=>$node_id])
					 ->first();
		//记录用户行为信息
		$data['action_id'] 	  = $action_id;
		$data['node_id']      = $node_id;
		$data['node_code']    = $node_info->username;
		$data['controller']   = $controller;
		$data['action'] 	  = $action;
		$data['request_info'] = $request_info;
		$data['action_time']  = date('Y-m-d H:i:s', time());
		$data['remark']       = $remark;
		//添加记录信息到数据库
		DB::table('admin_action')->insert($data);
		return $data;
	}

/**
 * 将中文转换成首字母大写
 * 获取首字母
 */
function getfirstchar($s0){
	$fchar = ord($s0{0});
	if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
	$s1 = @iconv("UTF-8","gbk", $s0);
	$s2 = iconv("gbk","UTF-8", $s1);
	if($s2 == $s0){$s = $s1;}else{$s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if($asc >= -20319 and $asc <= -20284) return "A";
	if($asc >= -20283 and $asc <= -19776) return "B";
	if($asc >= -19775 and $asc <= -19219) return "C";
	if($asc >= -19218 and $asc <= -18711) return "D";
	if($asc >= -18710 and $asc <= -18527) return "E";
	if($asc >= -18526 and $asc <= -18240) return "F";
	if($asc >= -18239 and $asc <= -17923) return "G";
	if($asc >= -17922 and $asc <= -17418) return "H";
	if($asc >= -17417 and $asc <= -16475) return "J";
	if($asc >= -16474 and $asc <= -16213) return "K";
	if($asc >= -16212 and $asc <= -15641) return "L";
	if($asc >= -15640 and $asc <= -15166) return "M";
	if($asc >= -15165 and $asc <= -14923) return "N";
	if($asc >= -14922 and $asc <= -14915) return "O";
	if($asc >= -14914 and $asc <= -14631) return "P";
	if($asc >= -14630 and $asc <= -14150) return "Q";
	if($asc >= -14149 and $asc <= -14091) return "R";
	if($asc >= -14090 and $asc <= -13319) return "S";
	if($asc >= -13318 and $asc <= -12839) return "T";
	if($asc >= -12838 and $asc <= -12557) return "W";
	if($asc >= -12556 and $asc <= -11848) return "X";
	if($asc >= -11847 and $asc <= -11056) return "Y";
	if($asc >= -11055 and $asc <= -10247) return "Z";
	return null;
}

/**
 * 将中文转换成首字母大写
 * 中文字符转英文字符
 */
function make_semiangle($str){
	$arr = array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G', 'H' => 'H', 'I' => 'I', 'J' => 'J', 'K' => 'K', 'L' => 'L', 'M' => 'M', 'N' => 'N', 'O' => 'O', 'P' => 'P', 'Q' => 'Q', 'R' => 'R', 'S' => 'S', 'T' => 'T', 'U' => 'U', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Y' => 'Y', 'Z' => 'Z', 'a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e', 'f' => 'f', 'g' => 'g', 'h' => 'h', 'i' => 'i', 'j' => 'j', 'k' => 'k', 'l' => 'l', 'm' => 'm', 'n' => 'n', 'o' => 'o', 'p' => 'p', 'q' => 'q', 'r' => 'r', 's' => 's', 't' => 't', 'u' => 'u', 'v' => 'v', 'w' => 'w', 'x' => 'x', 'y' => 'y', 'z' => 'z', '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']', '“' => '"', '”' => '"', '‘' => '\'', '’' => '\'', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-', '：' => ':', '。' => '.', '、' => ',', '，' => ',', '；' => ';', '？' => '?', '！' => '!', '…' => '...', '‖' => '|', '｜' => '|', '〃' => '"', '　' => ' ');
	return strtr($str, $arr);
}

/**
 * 将中文转换成首字母大写
 * 输入中文转换首字母英文
 */
function zh2pinyin($zh){
	$zh = make_semiangle($zh);
	$ret = "";
	$s1 = iconv("utf-8","gbk", $zh);
	$s2 = iconv("gbk","utf-8", $s1);
	if($s2 == $zh){$zh = $s1;}
	for($i = 0; $i < strlen($zh); $i++){
		$s1 = substr($zh,$i,1);
		$p = ord($s1);
		if($p > 160){
			$s2 = substr($zh,$i++,2);
			$ret .= getfirstchar($s2);
		}else{
			$ret .= $s1;
		}
	}
	return $ret;
}

/**
 * 根据传过来的属性，生成sku
 * 例：颜色:白/黑,大小:S/M
 */
function create_sku($property){
	if($property == '默认选择' || $property == '无'){
		$property = '选择属性:默认选择';
	}
	$kv_arr = explode(',' , $property);
	$res = [];
	// 生成Pro
	foreach($kv_arr as $kv){
		$row = [];
		$kv = explode(':' , $kv);
		if(count($kv) != 2){
			return false;
		}
		$row[$kv[0]] = explode('~' , $kv[1]);
		$res['pro'][$kv[0]] = explode('~' , $kv[1]);
	}
	// 生成sku
	$sku_arr = [];
	foreach($res['pro'] as $t => $one){
		$row = [];
		foreach($one as $v){
			$row[] = $t . ':' . $v;
		}
		$sku_arr[] = $row;
	}
	$res['sku'] = dikaer($sku_arr);
	return $res;
}

/**
 * 将多个数组生成一个笛卡尔积
 * $arr [[1,2,3] , [a,b,c]]
 */
function dikaer($arr){
	// 依次将两个数组合并成一个
	$cur_res = array_shift($arr);
	while($cur = array_shift($arr)){
		$temp = [];
		foreach($cur_res as $c){
			if(!is_array($c)) $c = [$c];
			foreach($cur as $c2){
				if(!is_array($c2)) $c2 = [$c2];
				$temp[] = array_merge_recursive($c , $c2);
			}
		}
		$cur_res = $temp;
	}
	// 检测sku，如果是一维数组则转换成二维
	foreach($cur_res as &$v){
		if(!is_array($v)){
			$v = [$v];
		}
	}
	return $cur_res;
}

/**
 * @param $add_status array
 * @return bool
 * 检查事务是否都是插入成功的。
 */
function checkTrans($add_status)
{
	foreach ($add_status as $v) {
		if (!$v && $v !== 0) {
			return false;
		}
		return true;
	}
}


/**
 * 查询快递信息
 * @param $com 物流公司信息，拼音
 * @param $no 快递单号
 *  常见快递公司编码：
公司名称 	公司公司编码
邮政包裹/平邮 	youzhengguonei
国际包裹 	youzhengguoji
EMS 	ems
EMS-国际件 	emsguoji
EMS-国际件 	emsinten
北京EMS 	bjemstckj
顺丰 	shunfeng
申通 	shentong
圆通 	yuantong
中通 	zhongtong
汇通 	huitongkuaidi
韵达 	yunda
宅急送 	zhaijisong
天天 	tiantian
德邦 	debangwuliu
国通 	guotongkuaidi
增益 	zengyisudi
速尔 	suer
中铁物流 	ztky
中铁快运 	zhongtiewuliu
能达 	ganzhongnengda
优速 	youshuwuliu
全峰 	quanfengkuaidi
京东 	jd
 */
function kuaidi($com , $no){
	$host = "http://express.woyueche.com";
	$path = "/query.action";
	$appcode = "ece8cce0e2e84443b684286e65965c89"; // porter的阿里云
	$headers = array();
	array_push($headers, "Authorization:APPCODE " . $appcode);
	//根据API的要求，定义相对应的Content-Type
	array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded;charset=UTF-8");

	$url = $host . $path;
	$result = curl_post($url , ['express'=>$com , 'trackingNo'=>$no] , $headers);
	$result =explode("\n" , $result);
	return json_decode(array_pop($result), true);
}

// curl 模拟 post 请求
function curl_post($url,$post_data = array() , $header = false){
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url);//设置链接
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
	curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));//POST数据
	$response = curl_exec($ch);//接收返回信息
	if(curl_errno($ch)){	//出错则显示错误信息
		print curl_error($ch);
	}
	curl_close($ch); //关闭curl链接
	return $response;
}


/**
 * check the input string whether a time format string.
 * @param $timeStr
 * @return bool
 */
function checkIsTimeFormat($timeStr)
{
	//2011-09-29 23:21:59
	$pattern = '/^[0-9]{4}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1} [0-2]{1}[0-9]{1}\:[0-5]{1}[0-9]{1}\:[0-5]{1}[0-9]{1}$/';
	if (preg_match($pattern, $timeStr)) {
		return true;
	}
	return false;
}

function iconv_str($text){
	return iconv("GB2312","UTF-8",$text);
}

/**
 * @param $arr 需要过滤的数组
 * 过滤添加 修改不必要的字段
 */
function filter_update_arr($arr){
	unset($arr['_token'],
		$arr['is_agree'],$arr['s']
	);
	return $arr;
}

/**
 * $arr 数组
 * $field_name 字段名称
 * 将统计好的数组以,隔开
 */
function norm_arr($arr,$field_name){
	$str='';
	foreach($arr as $v){
		$str.=$v->$field_name.',';
	}
	return substr($str,0,strlen($str)-1);
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