<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\BaseController;
use App\Models\TbuyEnterprise;
use App\Models\TnetArea;
use Illuminate\Http\Request;

class ManageController extends BaseController
{
    /**
     * 店铺管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $status = (int)$request->input('status');
        $keywords = $request->input('keywords');
        $query = TbuyEnterprise::orderBy('id', 'desc');

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

        return view('store.manage.index', ['list' => $list, 'status' => $status, 'keywords' => $keywords]);
    }

    /**
     * 编辑店铺信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {

        if($request->isMethod('post'))
        {

            $data = $request->all();

            if(!$data['passwd']){
                unset($data['passwd']);
            }
            $model = new TbuyEnterprise;
            if(!$model->validator($data)){
                return $this->ajaxError($model->getError());
            }


            $enterprise = TbuyEnterprise::where(['id' => $data['id']])->first();
            if(empty($enterprise)){
                return $this->ajaxError('更新失败，数据不存在');
            }

            $checkNodecode = TbuyEnterprise::where(['nodecode' => $data['nodecode'], ['id', '<>', $data['id']]])->first();

            if($checkNodecode){
                return $this->ajaxError('更新失败，账号已存在');
            }

            if(isset($data['passwd'])){
                $data['passwd'] = TbuyEnterprise::GetPasswd($data['id'], $data['passwd']);
            }

            unset($data['_token']);
            $res = TbuyEnterprise::where(['id' => $data['id']])->update($data);

            if(!$res)
            {
                return $this->ajaxError('数据更新失败，请重试');
            }

            return $this->ajaxSuccess('编辑成功');
        }else
        {
            $id = (int)$request->input('id');
            $city=[];
            $region=[];
            $areaModel=new TnetArea();
            $data = TbuyEnterprise::where(['id' => $id])->first();
            if(!empty($data->province)){
                $city=$areaModel->getChild($data->province);
            }
            if(!empty($data->city)){
                $region=$areaModel->getChild($data->city);
            }
            $province=$areaModel->getChild();//获得省级列表

            return view('store.manage.edit', ['data' => $data,'province'=>$province,'city'=>$city,'region'=>$region]);
        }

    }

    /**
     * @param Request $request
     */
    public function enable(Request $request)
    {
        $id = (int)$request->input('id');
        $model = TbuyEnterprise::where(['id' => $id])->first();
        if(empty($model)){
            return $this->ajaxError('数据不存在');
        }
        $status = $model->status == 1 ? 2 : 1;
        $model->status = $status;
        $res = $model->update();
        if(!$res) return $this->ajaxError();
        return $this->ajaxSuccess();
    }


}
