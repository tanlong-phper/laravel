<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseController;
use App\Models\FinanceSupplier;
use App\Models\TbuyEnterprise;
use App\Models\TbuyEnterpriseApply;
use App\Models\TnetArea;
use DB;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    /**
     * 共享店铺列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $status = (int)$request->input('status');
        $keywords = $request->input('keywords');
        $query = TbuyEnterpriseApply::orderBy('id', 'desc');

        unset($_REQUEST['_token']);

        $where = [];
        if($status){
            $where['status'] = $status;
        }

        if($keywords){
            $where[] = ['merchant_name', 'like', "%$keywords%"];
        }

        if(!empty($where)){
            $query->where($where);
        }
        $list = $query->paginate(20);

        return view('store.review.index', ['list' => $list, 'status' => $status, 'keywords' => $keywords]);
    }

    /**
     * @Desc     入驻审核
     * @DateTime 2017-08-15
     * @param    [参数]
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function applyAction(Request $request)
    {

        $id = (int)$request->input('id');

        $data = TbuyEnterpriseApply::where(['id' => $id])->first();

        if($request->isMethod('post')) {
            $status = (int)$request->input('status');
            $remarks = $request->input('remarks');
            if($data['status'] != 1){
                return $this->ajaxError('该信息已审核');
            }
            $res = TbuyEnterpriseApply::where(['id' => $id])->update([
                'status' => $status,
                'verify_time' => date('Y-m-d H:i:s'),
                'remark'	=> $remarks??'',
                'verify_id'	=> 1
            ]);
            if(!$res){
                return $this->ajaxError('数据更新失败');
            }

            return $this->ajaxSuccess('操作成功', url('store/review'));

        }else {
            return view('store.review.apply_action', ['data' => $data, 'id' => $id]);
        }

    }

    /**
     * 导出入驻信息
     * @param Request $request
     */
    public function exportExcel(Request $request){
        $status = (int)$request->input('status');
        $keywords = $request->input('keywords');
        $query = TbuyEnterpriseApply::orderBy('id', 'desc');

        unset($_REQUEST['_token']);

        $where = [];
        if($status){
            $where['status'] = $status;
        }

        if($keywords){
            $where[] = ['merchant_name', 'like', "%$keywords%"];
        }

        if(!empty($where)){
            $query->where($where);
        }
        $list = $query->get(['id','merchant_name','merchant_address','nodecode','rebate','contact_name','contact_mobile','status','verify_id','verify_time','remark','create_time'])->toArray();

        $title = ['ID编号','店铺名称','店铺详细地址','注册账号','让利折扣','对接人姓名','对接人手机号','状态','审核人员ID','审核时间','备注','申请时间'];

        exportData($title, $list,'店铺列表');
    }

    /**
     * @Desc     操作入驻
     * @DateTime 2017-08-15
     * @param    Request $request [description]
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View [type]              [description]
     */
    public function apply_enter(Request $request)
    {

        if($request->isMethod('post'))
        {
            $data = $request->all();


            $model = new TbuyEnterprise;
            if(!$model->validator($data)){
                $this->ajaxError($model->getError());
            }
            if(TbuyEnterprise::where(['nodecode' => $data['nodecode']])->first())
            {
                $this->ajaxError('入驻失败，账号已存在');
            }
            DB::beginTransaction();//开启事务
            $data['id'] = TbuyEnterprise::getNextSeq();
            $data['passwd'] = TbuyEnterprise::GetPasswd($data['id'], $data['passwd']);
            $apply_id = $data['apply_id'];
            unset($data['_token']);
            unset($data['apply_id']);
            unset($data['s']);
            $res = TbuyEnterprise::insertGetId($data);//获得店铺ID
            TbuyEnterpriseApply::where(['id' => $apply_id])->update(['is_enter' => 1]);

            if(!$res)
            {
                DB::rollBack();
                $this->ajaxError('数据更新失败，请重试');
            }
            //同时增加一个同名的供应商  2017-10-14 14:19:54 新增功能
            $supplier_id=FinanceSupplier::getNextSeq();
            FinanceSupplier::insert([
                'supplier_id'=>$supplier_id,
                'supplier_name'=>$data['merchant_name'],
                'c_time'=>date('Y-m-d H:i:s'),
                'name'=>$data['bank_account_name'],
                'id_card'=>$data['corpman_id'],
                'bank_name'=>$data['bank_name'],
                'bank_no'=>$data['bank_account_no'],
                'exo'=>$data['merchant_name'],
                'telphone'=>$data['merchant_name'],
                'address'=>$data['merchant_address'],
                'shortname'=>$data['merchant_name'],
                'passwd'=>$data['passwd'],//TODO 如何加密?
                'cardholder'=>$data['bank_account_name'],
                'mobile'=>$data['contact_mobile'],
                'email'=>$data['contact_email'],
                'province'=>$data['province'],
                'city'=>$data['city'],
                'is_full'=>0,
                'status'=>0,
                'contact_name'=>$data['contact_name'],
            ]);
            TbuyEnterprise::where(['id'=>$res])->update([
                'supplier_id'=>$supplier_id
            ]);
            DB::commit();
            $this->ajaxSuccess('入驻成功');

        }else
        {
            $city=[];
            $region=[];
            $areaModel=new TnetArea();
            $id = (int)$request->input('id');
            $data = TbuyEnterpriseApply::where(['id' => $id])->first();
            if(!empty($data->province)){
                $city=$areaModel->getChild($data->province);
            }
            if(!empty($data->city)){
                $region=$areaModel->getChild($data->city);
            }
            $province=$areaModel->getChild();//获得省级列表

            return view('admin.enterprise.apply_enter', ['data' => $data, 'id' => $id,'province'=>$province,'city'=>$city,'region'=>$region]);
        }

    }
}
