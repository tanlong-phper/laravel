<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\BaseController;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    public $role_status = ['禁用','启用'];

    private $role;

    public function __construct(){
        parent::__construct();
        $this->role = new AdminRole;
    }

    public function index(){
        $role_lists = AdminRole::paginate(10);

        foreach($role_lists as &$value){
            $value->department_name = DB::table('ADMIN_DEPARTMENTS')->where('id',$value->department_id)->value('name');
            $value->parse_status = $this->role_status[$value->status];
            if($value->pid != 0){
                $rs = AdminRole::where('id',$value->pid)->first(['name']);
                $value->parent_depart_name = $rs->name;
            }
        }
        return view('account.role_index', ['role_lists' => $role_lists]);
    }

    public function create(){
        $roles = AdminRole::where('status',1)->get(['id','name']);
        $departmentList = $this->getSelectList('ADMIN_DEPARTMENTS');
        $menu_lists = $this->getAllMenu(true);
        return view('account.role_create', ['menu_lists'=>$menu_lists,'roles'=>$roles, 'departmentList'=>$departmentList]);
    }

    public function store(Request $request){
        $id = AdminRole::getNextSeq();
        $data = [
            'id' =>$id,
            'name' =>$request->name,
            'pid' =>$request->pid,
            'status' =>$request->status,
            'department_id' =>$request->department_id,
            'menu_role_id' =>empty($request->menu_role_id) ? '' : implode(',', $request->menu_role_id)
        ];
        $rs = AdminRole::insert($data);
        if($rs){
            return $this->ajaxSuccess('新增角色成功！', url('/account/role'));
        }else{
            return $this->ajaxSuccess('新增角色失败！', url('/account/role/create'));
        }
    }

    public function edit($id){
        $lists = AdminRole::find($id);
        $roles = AdminRole::where('status',1)->get(['id','name']);
        $departmentList = $this->getSelectList('ADMIN_DEPARTMENTS');
        $menu_lists = $this->getAllMenu(true);
        $lists->menu_role_id_arr = explode(',',$lists->menu_role_id);
        return view('account.role_edit',['menu_lists'=>$menu_lists,'lists'=>$lists,'roles'=>$roles, 'departmentList'=>$departmentList]);
    }

    public function update(Request $request){

        $data['name'] = $request->name;
        $data['pid'] = $request->pid;
        $data['status'] = $request->status;
        $data['department_id'] = $request->department_id;
        $data['menu_role_id'] = empty($request->menu_role_id) ? '' : implode(',', $request->menu_role_id);

        AdminRole::where('id', $request->id)->update($data);

        return $this->ajaxSuccess('编辑角色成功！', url('/account/role'));

    }

    public function updateStatus(Request $request){

        if($request->id == session('user_id')){
            return $this->ajaxError('不允许操作当前登录用户！', url('/account/role'));
        }

        $data['status'] = !$request->status;

        $rs = AdminRole::where('id', $request->id)
            ->update($data);
        if($rs){
            return $this->ajaxSuccess('操作成功！', url('/account/role'));
        }
        return $this->ajaxError('操作失败！', url('/account/role'));
    }

    public function destroy($id){
        $rs = AdminRole::destroy($id);
        if($rs){
            return $this->ajaxSuccess('删除角色成功！', url('/account/role'));
        }
        return $this->ajaxError('删除角色失败！', url('/account/role'));
    }
}
