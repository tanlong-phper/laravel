<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BaseController extends Controller
{
    protected $user_id;

    public function __construct()
    {
        $this->middleware(function($request,$next){

            $user_info = $request->session()->get('user_info');
            //没登录返回登录页
            if(empty($user_info['id'])){
                return redirect()->route('users/login');
            }
            //取出菜单和用户的权限
            $menu_lists = DB::table('menus')->where('status','<>',0)->get()->toArray();
            $role = DB::table('roles')->where('id',$user_info['role_id'])->value('menu_role_id');
            $role = explode(',',$role);

            $url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : $_SERVER['REQUEST_URI'];
            //存在参数就截取掉
            if(strpos($url,'?') !== false){
                $url = strstr($url, '?', true);
            }

            //菜单子目录定位了二级目录
            $explode = explode('/', $url);
            if(count($explode) > 3){
                $url = '/'.$explode[1].'/'.$explode[2];
            }
            if($url == '/'){
                $url = '/index.php';
            }

            //判断权限和控制菜单
            $if_url = 0;
            foreach($menu_lists as $key => $values){
                if(!in_array($values['id'],$role)){
                    unset($menu_lists[$key]);
                }else{
                    if($values['url'] == $url){
                        $if_url = 1;
                    }
                }
            }
            $allow_url = ['home/upload_image'];
            if(in_array(trim($url,'/'),$allow_url)){
                $if_url = 1;
            }

            //没有权限返回首页
            if($if_url == 0 && $url != '/index.php'){
                return redirect('/');
            }

            $menu_lists =_tree_hTree(_tree_sort($menu_lists,'sort_number'));
            //视图间共享数据
            view()->share('__menu_lists__',$menu_lists);


            //显示二级目录的名字
            $current_menu = DB::table('menus')->where('url',$url)->first();
            if(!empty($current_menu)){
                view()->share('__current_menu__',$current_menu);
                if($current_menu->pid != 0){
                    $parent_menu = DB::table('menus')->where('id',$current_menu->pid)->first();
                    view()->share('__parent_menu__',$parent_menu);
                }
            }

            view()->share('__user_info__',$user_info);
            return $next($request);
        });
    }


    protected function getOrderStatus($status = ''){
        $order_status = DB::table('order_status')->pluck('order_status','id')->toArray();
        if($status !== ''){
            return isset($order_status[$status]) ? $order_status[$status] : '';
        }
        return $order_status;
    }

    /**
     * 获取所有菜单
     * @param bool $tree   是否转换成树形
     * @return array
     */
    protected function getAllMenu($tree = false){
        $list = DB::table('menus')->where('status','<>',0)->get()->toArray();
        if($tree){
            $list =_tree_hTree(_tree_sort($list,'sort_number'));
        }
        return $list;
    }

    protected function ajaxSuccess($msg = '操作成功',$url = '',$data = []){
        $return = ['status'=>1,'url'=>$url,'data'=>$data,'info'=>$msg];
        return response()->json($return);
    }
    protected function ajaxError($msg = '操作失败',$url = '',$data = []){
        $return = ['status'=>0,'url'=>$url,'data'=>$data,'info'=>$msg];
        return response()->json($return);
    }

    public function getSelectList($table, $pid = 0, &$result=[], $spac = -2){
        $spac += 2;
        $list = DB::table($table)->where(['pid'=>$pid,'status'=>1])->get(['id','name','pid']);
        $list = objectToArray($list);
        foreach($list as $val){
            $val['name'] = str_repeat('&nbsp;',$spac).$val['name'];
            $result[] = $val;
            $this->getSelectList($table, $val['id'], $result, $spac);
        }
        return $result;
    }

    protected function getArea(){
        $areas = DB::table('TNET_AREA')->where('parent_id','<>',0)->get();
        $areas = objectToArray($areas);
        return genTree($areas, 'area_id','parent_id');
    }

    // 上传一张图片公共方法 name="image"
    public function upload_image(Request $request){
        $file_path = '';
        base64_upload('images','image',function($data) use(&$file_path){
            $file_path = curl_upfile($data[0]);
        });
        $this->ajaxSuccess('图片上传成功',$file_path);
    }

}














