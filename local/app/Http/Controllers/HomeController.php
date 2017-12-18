<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends BaseController
{
    //
    public function index(){
        return view('home');
    }

    // 上传一张图片公共方法 name="image"
    public function upload_image(Request $request){
        $file_path = '';
        base64_upload('images','image',function($data) use(&$file_path){
            $file_path = curl_upfile($data[0]);
        });
        return $this->ajaxSuccess('图片上传成功','',$file_path);
    }

    /**
     * 该方法只适用百度webuploder多图片上传插件使用
     * @param Request $request
     * @return int|string
     */
    public function upload(Request $request){
        $file = $request->file('file');
        if($file->isValid()){
            $ext = ['jpeg','jpg','gif','gpeg','png'];
            if(in_array( strtolower($file->extension()),$ext)) {
                $file_path = 'upload/'.date('Y-m-d').'/';
                if(!is_dir($file_path)){
                    mkdir($file_path,0777,true);//递归模式
                }
                $extArray = explode('.',$file->getClientOriginalName());
                $extArray = array_reverse($extArray);
                //文件后缀名 $file->extension获取的jpg为jpeg类型
                $extName = strtolower(array_shift($extArray));
                $file_name = date('Ymd') . time() . rand(100000, 999999) . uniqid().'.'.$extName;
                if ($file->move($file_path, $file_name)) {
                    $file_path_name =  './'.$file_path.$file_name;
                    filedebug($file_path.$file_name);
                    $server_path = curl_upfile($file_path_name);
                    filedebug($server_path);
                    exit('{"jsonrpc" : "2.0", "result" : "上传成功", "id" : "id" ,"url" : "'.$server_path.'"}');
                } else {
                    exit('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "上传失败"}, "id" : "id"}');
                }

            }else{
                exit('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "非法类型文件"}, "id" : "id"}');
            }
        }
        exit('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "上传失败"}, "id" : "id"}');
    }


    /**
     * html5上传图片
     * @author liuwei
     * @dateTime 2015-11-02T15:53:58+0800
     * @param    [type]                   $name [description]
     * @return   [type]                         [description]
     */
    public function uploadPictureHtml5($name='Picture',$limit=''){
        $uploadDir = '/public/uploads/'.$name.'/';
        $date = date('Y-m-d');
        $uploadDir = $uploadDir.$date.'/';
       
        is_file($uploadDir) or mkdir($uploadDir,0700,true);
        $fileTypes = array('jpg', 'jpeg', 'gif', 'png'); // Allowed file extensions

        if (!empty($_FILES) ) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $size       = $_FILES['Filedata']['size'];         //上传图片大小
            $pass = true;   //图片大小限制 默认通过
            if($limit){ //限制大小 kb
                $limit = $limit * 1024;
                if($size > $limit){
                    $pass = false;
                }
            }
            // Validate the filetype
            $fileParts = pathinfo($_FILES['Filedata']['name']);
            $ext = strtolower($fileParts['extension']);
            if (in_array($ext , $fileTypes) && $pass) {
                // Save the file
                $savename = uniqid() .'.'. $ext;
                $targetFile = $uploadDir . $savename;
                move_uploaded_file($tempFile, $targetFile);

                $info['savepath'] = $name.'/'.$date.'/'.$savename;
                $info['src'] = $targetFile;
                $return['status'] = 1;
                $return['info'] = $info;
            }else if(!$pass){
                $return['status'] = 0;
                $return['info'] = '图片大小超过限制';
            }else {
                // The file type wasn't allowed
                $return['status'] = 0;
                $return['info'] = 'Invalid file type.';
            }
        }else{
            $return['status'] = 0;
            $return['info'] = 'empty.';
        }
        return json_encode($return);
//        return response()->json($return);
    }
}
