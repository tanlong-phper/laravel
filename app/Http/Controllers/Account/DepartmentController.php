<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\BaseController;
use App\Models\AdminDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DepartmentController extends BaseController
{
    public $deportment_status = ['禁用','启用'];

    private $department;

    public function __construct(){
        parent::__construct();

        $this->department = new AdminDepartment;
    }

    public function index(){
        $department_lists = DB::table('ADMIN_DEPARTMENTS')->paginate(10);
        foreach($department_lists as &$value){
            $rs = DB::table('ADMIN_DEPARTMENTS')->where('id',$value->pid)->first(['name']);
            $value->parent_depart_name = empty($rs) ? '无' : $rs->name;
            $value->parse_status = $this->deportment_status[$value->status];
        }
        return view('account.department_index', ['department_lists' => $department_lists]);
    }

    public function create(){
        $departments = AdminDepartment::where('status',1)->get(['id','name']);
        return view('account.department_create', ['areas'=>$this->getArea(),'departments'=>$departments]);
    }
    
    public function store(Request $request){

        $id = AdminDepartment::getNextSeq();
        $data = [
            'id' => $id,
            'name' => $request->name,
            'pid' => $request->pid,
            'status' => $request->status,
            'area_role' => empty($request->area) ? '' : implode(',', $request->area),
        ];

        $rs = AdminDepartment::insert($data);
        if($rs){
            return $this->ajaxSuccess('新增部门成功！', url('/account/department'));
        }else{
            return $this->ajaxSuccess('新增部门失败！', url('/account/department/create'));
        }
    }

    public function edit($id){
        $lists = AdminDepartment::find($id);
        $departments = AdminDepartment::where('status',1)->get(['id','name']);
        return view('account.department_edit',['lists'=>$lists,'departments'=>$departments,'areas'=>$this->getArea()]);
    }

    public function update(Request $request){
        $data['name'] = $request->name;
        $data['pid'] = $request->pid;
        $data['status'] = $request->status;
        $data['area_role'] = empty($request->area) ? '' : implode(',', $request->area);

        AdminDepartment::where('id', $request->id)
            ->update($data);

        return $this->ajaxSuccess('编辑部门成功！', url('/account/department'));

    }

    public function updateStatus(Request $request){
        $data['status'] = !$request->status;

        $rs = AdminDepartment::where('id', $request->id)
            ->update($data);
        if($rs){
            return $this->ajaxSuccess('操作成功！', url('/account/department'));
        }
        return $this->ajaxError('操作失败！', url('/account/department'));
    }

    public function destroy($id){
        $rs = AdminDepartment::destroy($id);
        if($rs){
            return $this->ajaxSuccess('删除部门成功！', url('/account/department'));
        }
        return $this->ajaxError('删除部门失败！', url('/account/department'));
    }
}





















