<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\BaseController;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends BaseController
{
    /**
     * 入驻审核列表
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
            $condition[] = ['manage_cate', 'like', '%'.$keywords.'%'];
        }
        switch ($status) {
            case '0':
                $condition[] = ['status','=',0];
                break;
            case '1':
                $condition[] = ['status','=',1];
                break;
            case '2':
                $condition[] = ['status','=',2];
                break;
            default:
                $condition[] = ['status','=',1];
                break;
        }

        //查询管理员审核信息
        $data = DB::table('oshop_apply_detail')->where($condition)->orderBy('id','desc')->paginate(20);

        //展示模板
        return view('business.review.index',[
            'data' => $data,
            'keywords' => $keywords
        ]);
    }

    /**
     * 管理员审核页面
     **/
    public function apply_action(Request $request){
        $id = $request->input('id');
        return view('business.review.apply_action',['id' => $id]);
    }

    //审核通过提交
    public function apply_save(Request $request) {
        $remarks = $request->input('remarks');
        $status = $request->input('status');
        $id = $request->input('id');
        if($status==1){
            $data['status'] = 2;
            $data['remarks'] = $remarks;
            $data['audit_id'] = 1;
            $data['audit_time'] = date('Y-m-d H:i:s',time());
        }else{
            $data['status'] = 0;
            $data['remarks'] = $remarks;
            $data['audit_id'] = 1;
            $data['audit_time'] = date('Y-m-d H:i:s',time());
        }

        $result = DB::table('oshop_apply_detail')->where('id',$id)->update($data);
        echo 1;
    }

    /**
     * 入驻编辑
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply_edit(Request $request){

        if($request->isMethod('post')){
            $data = array();
            $id = $request->input('id');
            if(!$id){
                return $this->ajaxError('ID为空');
            }
            if($manage_cate = $request->input('manage_cate')){
                $data['manage_cate'] = $manage_cate;
            }else{
                return $this->ajaxError('店铺经营类别不能为空');
            }
            if($pic1 = $request->input('pic1')){
                $data['shop_photo'] = $pic1;
            }else{
                return $this->ajaxError('门店照片不能为空');
            }
            if($pic2 = $request->input('pic2')){
                $data['business_license'] = $pic2;
            }else{
                return $this->ajaxError('营业执照图片不能为空');
            }

            if($shop_address = $request->input('shop_address')){ //地址
                $data['shop_address'] = $shop_address;
            }
            if($login_account = $request->input('login_account')){ //注册账号
                $data['login_account'] = $login_account;
            }
            if($profit_percent = $request->input('profit_percent')){  //让利折扣
                $data['profit_percent'] = $profit_percent;
            }
            if($corporation_name = $request->input('corporation_name')){ //法人用户名
                $data['corporation_name'] = $corporation_name;
            }
            if($corporation_idcard = $request->input('corporation_idcard')){ //法人身份证
                $data['corporation_idcard'] = $corporation_idcard;
            }
            if($corporation_phone = $request->input('corporation_phone')){ //法人手机号
                $data['corporation_phone'] = $corporation_phone;
            }
            if($bank_name = $request->input('bank_name')){ //法人银行开户名
                $data['bank_name'] = $bank_name;
            }
            if($bank_cate = $request->input('bank_cate')){ //银行名称
                $data['bank_cate'] = $bank_cate;
            }
            if($bank_branch = $request->input('bank_branch')){//银行支行
                $data['bank_branch'] = $bank_branch;
            }
            if($bank_card_no = $request->input('bank_card_no')){ //银行卡号
                $data['bank_card_no'] = $bank_card_no;
            }
            if($master_name = $request->input('master_name')){ //对接人用户名
                $data['master_name'] = $master_name;
            }
            if($master_phone = $request->input('master_phone')){ //对接人手机
                $data['master_phone'] = $master_phone;
            }
            if($master_email = $request->input('master_email')){ //对接人邮箱
                $data['master_email'] = $master_email;
            }
            if($master_qq = $request->input('master_qq')){ //对接人QQ
                $data['master_qq'] = $master_qq;
            }

            $result = DB::table('oshop_apply_detail')->where('id',$id)->update($data);

            if(!$result){
                return $this->ajaxError('修改失败');
            }
            return $this->ajaxSuccess('修改成功');
        }

        $id = $request->input('id');
        //编辑页面信息
        $productInfo = DB::table('oshop_apply_detail')->where('id',$id)->first(); //申请信息

        return view('business.review.apply_edit',[
            'data' => $productInfo,
            'action' => 'apply_edit'
        ]);
    }


}
