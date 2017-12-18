<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\BaseController;
use App\Models\Qrcode;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageController extends BaseController
{
    /**
     * 商家管理列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $condition = array();
        $keywords = $request->input('keywords');
        $status = $request->input('status');
        if($keywords){
            $condition[] = ['merchant_name', 'like', '%'.$keywords.'%'];
        }
        switch ($status) {
            case '1':
                $condition[] = ['status','=',1];
                break;
            case '2':
                $condition[] = ['status','=',2];
                break;
        }


        //查询商家列表
        $data = DB::table('ruzhu_merchant_basic')->where($condition)->orderBy('id','desc')->paginate(20);
        //展示模板
        return view('business.manage.index',[
            'data' => $data,
            'keywords' => $keywords
        ]);
    }

    /**
     * 上下架页面
     **/
    public function updown(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        return view('business.manage.updown',[
            'id' => $id,
            'status' => $status
        ]);
    }

    //上下架执行操作
    public function upSave(Request $request) {
        $xj_reason = $request->input('xj_reason');
        $status = $request->input('status');
        $id = $request->input('id');
        $data['status'] = $status;
        $data['xj_reason'] = $xj_reason;
        $result = DB::table('ruzhu_merchant_basic')->where('id',$id)->update($data);
        echo 1;
    }

    /**
     * 编辑
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function edit(Request $request){
        if($request->isMethod('post')){
            $data = array();
            $id = $request->input('id');
            $status = $request->input('status');

            if(!$id){
                return $this->ajaxError('ID为空');
            }
            if($merchant_name = $request->input('merchant_name')){
                $data['merchant_name'] = $merchant_name;
            }else{
                return $this->ajaxError('店铺名称不能为空');
            }
            if($pic = $request->input('pic')){
                $data['logo'] = $pic;
            }
            //$status = $request->input('status')

            if($merchant_address = $request->input('merchant_address')){ //地址
                $data['merchant_address'] = $merchant_address;
            }
            if($rebate = $request->input('rebate')){ //一级折扣
                $data['rebate'] = $rebate;
            }
            if($second_rebate = $request->input('second_rebate')){ //二级折扣
                $data['second_rebate'] = $second_rebate;
            }
            if($short_name = $request->input('short_name')){ //店铺简介
                $data['short_name'] = $short_name;
            }

            if($corpman_mobile = $request->input('corpman_mobile')){ //法人手机号
                $data['corpman_mobile'] = $corpman_mobile;
            }

            $data['status'] = $status;

            $result = DB::table('ruzhu_merchant_basic')->where('id',$id)->update($data);

            if(!$result){
                return $this->ajaxError('修改失败');
            }
            return $this->ajaxSuccess('修改成功');
        }

        $id = $request->input('id');

        //编辑页面信息
        $storeInfo = DB::table('ruzhu_merchant_basic')->where('id',$id)->first(); //店铺信息
        return view('business.manage.edit',[
            'data' => $storeInfo,
            'action' => 'edit',
            'id' => '$id'
        ]);
    }

    /*
	  订单列表order_list
	 */
    public function orderList(Request $request){
        $order_no = $request->input('order_no');
        $pay_state = $request->input('pay_state');
        $sid = $request->input('id');
        $where = array();
        if($sid){
            $where[] = ['sid' , '=' ,$sid];
        }
        if($order_no){
            $where[] = ['order_no' , '=' ,$order_no];
        }
        if($pay_state){
            $where[] = ['pay_state' , '=' ,$pay_state];
        }
        $data = DB::table('vshops_order')->where($where)->paginate(10);


        return view('business.manage.order_list',[
            'data' => $data,
            'sid' =>$sid
        ]);
    }

    /*
	  评论列表comment_list
	 */
    public function commentList(Request $request){
        $sid = $request->input('id');
        $where = array();
        if($sid){
            $where[] = ['sid' , '=' ,$sid];
        }

        $data = DB::table('vassess')->where($where)->paginate(10);
        $data->appends($request->all());
        return view('business.manage.comment_list',[
            'data' => $data,
            'sid' =>$sid
        ]);
    }

    /**
     * 付款二维码
     **/
    public function getList(Request $request) {
        //var_dump(__METHOD__); exit();

        $store_id = intval($request->input('id'));

        $qrcode = DB::table('qrcode')->where('store_id', $store_id)->first();

        if(empty($qrcode)){
            //获取店铺ID和手机号
            $store = DB::table('ruzhu_merchant_basic')->where('id', $store_id)->first();
            require __DIR__.'/../phpqrcode/index.php';

            $rootUrl = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/';
            $qrcodeImgRelativeDir = './images/qrcode/';
            $pngTempDir = $rootUrl.$qrcodeImgRelativeDir;
            //var_dump($pngTempDir); exit();
            $backDir    = $pngTempDir . '/back/';
            $iconImgPath = $backDir . 'icon.png';

            $PhpQRCode = new \PhpQRCode();
            $PhpQRCode->set('pngTempDir', $pngTempDir);
            $PhpQRCode->set('matrixPointSize', 9);

            $types = array('qr_name_global' => '1', 'qr_name_stored' => '2', 'qr_name_global_plus' => '3');
            $qr_names = array();
            foreach($types as $key => $type){
                //生成付款二维码
                //$url = 's=MjY5_13618624473';
                $url = 'http://m.hlqqg168.com/scan.php?type=' . $type . '&s=' . base64_encode($store->id) .'_' . $store->corpman_mobile;
                //var_dump($url); exit();
                $name = $store->id . '_' . $type . '_' .  $store->corpman_mobile . '.png';
                $PhpQRCode->set('date',$url);
                $PhpQRCode->set('pngTempName', $name);
                $PhpQRCode->init();

                $backImgPath = $backDir . $type . '.png';
                $targetImg     = imagecreatefrompng($backImgPath);
                //$targetImg     = imagecreatefromstring(file_get_contents($backImgPath));
                $qrcodeImgPath = $pngTempDir . $name;
                $qrcodeImg     = imagecreatefrompng($qrcodeImgPath);
                //$qrcodeImg     = imagecreatefromstring(file_get_contents($qrcodeImgPath));
                list($qrcodeWidth, $qrcodeHight, $qrcodeType) = getimagesize($qrcodeImgPath);
                $iconImg = imagecreatefrompng($iconImgPath);
                list($iconWidth, $iconHight, $iconType) = getimagesize($iconImgPath);

                $width  = 175;
                $height = 370;
                imagecopymerge($targetImg, $qrcodeImg, $width, $height, 0, 0, $qrcodeWidth, $qrcodeHight, 100);
                //imagecopymerge($targetImg, $iconImg, $width + $qrcodeWidth/2 - $iconWidth/2, $height + $qrcodeHight/2 - $iconHight/2, 0, 0, $iconWidth, $iconHight, 100);

                // Save the image to file and free memory
                imagepng($targetImg, $qrcodeImgPath);
                imagedestroy($targetImg);
                imagedestroy($qrcodeImg);
                imagedestroy($iconImg);

                //$qr_names[$key] = $name;
                $qrcodeImgRelativePath = $qrcodeImgRelativeDir . $name;
                filedebug($qrcodeImgRelativePath);
                $server_path = curl_upfile($qrcodeImgRelativePath);
                filedebug($server_path);
                $qr_names[$key] = $server_path;
            }

            //生成二维码插入数据表
            $data = array(
                'id' => Qrcode::getNextSeq(),
                'store_id' => $store->id,
                'store_phone' => $store->corpman_mobile,
                'qr_name_global' => $qr_names['qr_name_global'],
                'qr_name_stored' => $qr_names['qr_name_stored'],
                'qr_name_global_plus' => $qr_names['qr_name_global_plus'],
            );
            $res = DB::table('qrcode')->insert($data);
        }else{
            $qr_names = array(
                'qr_name_global' => $qrcode->qr_name_global,
                'qr_name_stored' => $qrcode->qr_name_stored,
                'qr_name_global_plus' => $qrcode->qr_name_global_plus,
            );
        }

        //echo "<script>alert('二维码生成成功!')</script>";
        return view('business.manage.list', ['qr_names' => $qr_names, 'store_id' => $store_id]);
    }

    public function download(Request $request)
    {
        $store_id = intval($request->input('store_id'));
        $type = intval($request->input('type'));

        $key_lookup = array('1' => 'qr_name_global', '2' => 'qr_name_stored', '3' => 'qr_name_global_plus');
        if(!in_array($type, array('1', '2', '3'))){
            echo "<script>alert('图片不存在')</script>"; exit();
        }

        $qrcode = DB::table('qrcode')->where('store_id', $store_id)->first();
        //var_dump($qrcode); exit();
        if(empty($qrcode)){
            echo "<script>alert('图片不存在')</script>"; exit();
        }

        $key = $key_lookup[$type];
        $rootUrl = $_SERVER['DOCUMENT_ROOT'];
        $imgPath = $qrcode->{$key};
        //var_dump($imgPath); exit();

        $basename = basename($imgPath);
        header("Content-type: image/png");
        header("Content-Disposition:attachment;filename='" . $basename ."'");
// 	    $img = imagecreatefromstring(file_get_contents($imgPath));
// 	    imagepng($img);
// 	    imagedestroy($img);
        $img = file_get_contents($imgPath);
        echo $img;
        exit();
    }

}
