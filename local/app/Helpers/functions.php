<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/12 0012
 * Time: 11:00
 */

function objectToArray($object) {
    //先编码成json字符串，再解码成数组
    return json_decode(json_encode($object), true);
}

function arrayToObject($arr){
    if(is_array($arr)){
        return (object) array_map(__FUNCTION__, $arr);
    }else{
        return $arr;
    }
}

//生成树形图
function genTree($items,$id='id',$pid='pid',$son = 'children'){
    $tree = array(); //格式化的树
    $tmpMap = array();  //临时扁平数据

    foreach ($items as $item) {
        $tmpMap[$item[$id]] = $item;
    }

    foreach ($items as $item) {
        if (isset($tmpMap[$item[$pid]])) {
            $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
        } else {
            $tree[] = &$tmpMap[$item[$id]];
        }
    }
    unset($tmpMap);
    return $tree;
}

/**
 * 分类排序（降序）
 */
function _tree_sort($arr,$cols){
    //子分类排序
    foreach ($arr as $k => &$v) {
        if(!empty($v['sub'])){
            $v['sub']=_tree_sort($v['sub'],$cols);
        }
        $sort[$k]=$v[$cols];
    }
    if(isset($sort))
        array_multisort($sort,SORT_ASC,$arr);
    return $arr;
}
/**
 * 横向分类树
 */
function _tree_hTree($arr,$pid=0){
    foreach($arr as $k => $v){
        if($v['pid']==$pid){
            $data[$v['id']]=$v;
            $data[$v['id']]['sub']=_tree_hTree($arr,$v['id']);
        }
    }
    return isset($data)?$data:array();
}
    /**
     * 纵向分类树
     */
function _tree_vTree($arr,$pid=0){
    foreach($arr as $k => $v){
        if($v['pid']==$pid){
            $data[$v['id']]=$v;
            $data+=_tree_vTree($arr,$v['id']);
        }
    }
    return isset($data)?$data:array();
}

function ajax_error($msg = '服务器错误，可刷新页面重试',$data=array()){
    $return = array('status'=>'0');
    $return['info'] = $msg;
    $return['data'] = $data;
    exit(json_encode($return,JSON_UNESCAPED_UNICODE));
}
function ajax_success($msg = '提交成功',$data=array()){
    $return = array('status'=>'1');
    $return['info'] = $msg;
    $return['data'] = $data;
    exit(json_encode($return,JSON_UNESCAPED_UNICODE));
}

function is_login(){
    
}

/**
 * 打印调试函数
 * @param mixed $var 打印的东西
 */
function p($var = null,$debugger = 0){
    $str = '<pre style="border:1px solid #ccc; padding:10px; font-size:16px; line-height:28px; border-radius:5px; background:#eaebe6;">%str%</pre>';
    $replace = print_r($var, true);
    if(is_null($var)){
        $replace = '__NULL__';
    }elseif(is_bool($var)){
        $var = $var === true ? 'true' : 'false';
        $replace = '(bool)'.$var;
    }elseif(is_string($var) && trim($var) === ''){
        $replace = '空';
    }
    $str = str_replace('%str%', $replace, $str);
    echo $str;
    if($debugger) exit;
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
    $callback($return);
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
















