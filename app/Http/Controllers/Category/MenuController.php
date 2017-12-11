<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\BaseController;
use App\Models\Menu;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends BaseController
{
    //
    private $cateStatus = ['禁用','启用'];

    public function index(Request $request){
        $menu_list = DB::table('menus')->orderBy('sort_number')->get()->toArray();
        $menu_list = genTree($menu_list);
        return view('category/menu_index', ['tree'=>$menu_list,'cateStatus'=>$this->cateStatus]);
    }

    public function tree($tree = null){
        return view('category/tree',['tree'=>$tree]);
    }

    public function updateStatus(Request $request){
        $status = $request->status;
        $id = $request->id;
        if(!isset($id)) return;

        $rs = DB::table('menus')->where('id', $id)->update(['status'=> !$status]);
        return $this->ajaxSuccess('状态更改成功！','',['status'=>$status,'id'=>$id]);
    }

    public function create($pid = 0){
        $parent_cate = [];
        $menu_list = [];

        if($pid == 0){
            $menu_list = DB::table('menus')->where('pid',0)->orderBy('sort_number')->get(['id','name']);
        }else{
            $parent_cate = DB::table('menus')->where('id',$pid)->first();
        }

        return view('category/menu_create',['parent_cate'=>$parent_cate,'menu_list'=>$menu_list]);
    }

    public function store(Request $request){
        $data = $request->input();
        unset($data['_token']);

        $rs = Menu::insert($data);
        if($rs){
            return redirect()->route('column/menu/index')->with('success','新增菜单成功！');
        }else{
            return redirect()->route('column/menu/create',['pid'=>$request->pid])->with('error','新增菜单失败！');
        }
    }

    public function edit(Request $request){
        if(!empty($request->id)){
            $data = ['sort_number'=>$request->sort_number, 'name'=>$request->name,'url'=>$request->url];
            $rs = DB::table('menus')->where('id',$request->id)->update($data);
            if($rs){
                return $this->ajaxSuccess('编辑成功!');
            }else{
                return $this->ajaxError('编辑失败!');
            }
        }
    }

    public function destroy($id){
        $rs = Menu::where('id', $id)->delete();
        if($rs){
            return $this->ajaxSuccess('删除菜单成功！', url('/category/menu'));
        }
        return $this->ajaxError('删除菜单失败！', url('/category/menu'));
    }
}
















