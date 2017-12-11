<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\BaseController;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends BaseController
{
    public $account_status = ['禁用','启用'];

    private $account;

    public function __construct(){
        parent::__construct();
        $this->account = new Account;
    }

    public function index(Request $request){
        $where = [];
        if(isset($request->search)){
            if(!empty($request->department_id)){
                $where['department_id'] = $request->department_id;
            }
            if(!empty($request->role_id)){
                $where['role_id'] = $request->role_id;
            }
            if($request->status != ''){
                $where['status'] = $request->status;
            }
            if(!empty($request->keyword)){
                $where[$request->keyword_type] = ['LIKE' => '%'.$request->keyword.'%' ];;
            }
        }
        if(!empty($where)){
            $account_lists = Account::where($where)->orderBy('id')->paginate(10);
        }else{
            $account_lists = Account::orderBy('id')->paginate(10);
        }

        foreach($account_lists as &$value){
            $value->parse_department_id = DB::table('departments')->where('id',$value->department_id)->value('name');
            $value->parse_role_id = DB::table('roles')->where('id',$value->role_id)->value('name');
            $value->parse_status = isset($this->account_status[$value->status]) ? $this->account_status[$value->status] : '未知';

            if($value->pid != 0){
                $value->parent_depart_name  = Account::where('id',$value->pid)->value('name');
            }
        }
        $departmentList = $this->getSelectList('departments');
        $roleList = $this->getSelectList('roles');

        return view('account.account_index', ['account_lists' => $account_lists, 'departmentList'=>$departmentList,'roleList'=>$roleList]);
    }

    public function create(){
        $roles = Account::where('status',1)->get(['id','name']);
        $departmentList = $this->getSelectList('departments');
        $roleList = $this->getSelectList('roles');
        return view('account.account_create', ['roles'=>$roles, 'departmentList'=>$departmentList,'roleList'=>$roleList]);
    }

    public function store(Request $request){
        $id = Account::getNextSeq();
        $data = [
            'id' => $id,
            'name' => $request->name,
            'username' => $request->username,
            'passwd' => md5($request->passwd),
            'status' => $request->status,
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
        ];

        $rs = Account::insert($data);
        if($rs){
            return $this->ajaxSuccess('新增账号成功！', url('/account/user'));
        }else{
            return $this->ajaxSuccess('新增账号失败！', url('/account/user/create'));
        }
    }

    public function edit($id){
        $lists = Account::find($id);
        $departmentList = $this->getSelectList('departments');
        $roleList = $this->getSelectList('roles');
        return view('account.account_edit',['lists'=>$lists, 'departmentList'=>$departmentList,'roleList'=>$roleList]);
    }

    public function update(Request $request){

        $data['name'] = $request->name;
        $data['username'] = $request->username;
        $data['status'] = $request->status;
        $data['department_id'] = $request->department_id;
        $data['role_id'] = $request->role_id;

        Account::where('id', $request->id)
            ->update($data);

        return $this->ajaxSuccess('编辑账号成功！', url('/account/user'));

    }

    public function updateStatus(Request $request){

        if($request->id == session('user_id')){
            return $this->ajaxError('不允许操作当前登录用户！', url('/account/role'));
        }
        
        $data['status'] = !$request->status;

        $rs = Account::where('id', $request->id)
            ->update($data);
        if($rs){
            return $this->ajaxSuccess('操作成功！', url('/account/user'));
        }
        return $this->ajaxError('操作失败！', url('/account/user'));
    }

    public function destroy($id){
        $rs = Account::destroy($id);
        if($rs){
            return $this->ajaxSuccess('删除账号成功！', url('/account/user'));
        }
        return $this->ajaxError('删除账号失败！', url('/account/user'));
    }
}
