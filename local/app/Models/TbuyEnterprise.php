<?php
namespace App\Models;


class TbuyEnterprise extends Base {
	protected $table = 'tbuy_enterprise';

	protected $error_msg = '';

	public function getError()
	{
		return $this->error_msg;
	}

	public function scopeGetPasswd($query, $id, $pwd){
		if(empty($pwd) || empty($id)){
			return false;
		}
		$safe_code = '6A61750BB30490604B7DF6FD8DA9FA8E';
		$md5 = md5($safe_code.$id.$pwd);
		return "2".$md5;
	}


	public function validator($data)
	{
		$validator = \Validator::make($data ,[
		    'merchant_name'		=> 'required',
		    'merchant_address'	=> 'required',
		    'rebate'			=> 'required',
		    'nodecode'			=> 'required',
		    'merchant_type'		=> 'required',
		    'corpman_name'		=> 'required',
		    'corpman_id'		=> 'required',
		    'corpman_mobile'	=> 'required',
		    'bank_account_name'	=> 'required',
		    'bank_name'			=> 'required',
		    'bank_branch'		=> 'required',
		    'bank_account_no'	=> 'required',
		    'business_license'	=> 'required',
		    'contact_name'		=> 'required',
		    'contact_mobile'	=> 'required',
		    'passwd'			=> 'sometimes|required|min:6|max:32',
            'province'          => 'required',
            'city'              => 'required',
            'region'            => 'required',
		],[
		    'merchant_name.required'		=> '店铺名称必填',
		    'merchant_address.required'		=> '详细地址必填',
		    'rebate.required'				=> '商家折扣必填',
		    'nodecode.required'				=> '注册账号必填',
		    'merchant_type.required'		=> '经营性质必填',
		    'corpman_name.required'			=> '法人姓名必填',
		    'corpman_id.required'			=> '法人身份证证必填',
		    'corpman_mobile.required'		=> '法人联系方式必填',
		    'bank_account_name.required'	=> '开户户名必填',
		    'bank_name.required'			=> '开户银行必填',
		    'bank_branch.required'			=> '开户行支行必填',
		    'bank_account_no.required'		=> '开户行账号必填',
		    'business_license.required'		=> '营业执照必填',
		    'contact_name.required'			=> '联系人必填',
		    'contact_mobile.required'		=> '联系人电话必填',
		    'passwd.required'				=> '密码必填',
		    'passwd.min'					=> '密码长度最低6位字符',
		    'passwd.max'					=> '密码长度最多32位字符',
            'province.required'             => '省必选',
            'city.required'                 => '市必选',
            'region.required'               => '区必选',

		]);

		if($validator->fails())
		{
			$this->error_msg = $validator->errors()->first();
			return false;
		}
		return true;
	}

	

}