<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class UsersController extends Controller {
	/**
	 * 后台登录页面
	 * @param Request $request
	 * @return mixed
	 */
	public function login(Request $request){

		if($request->isMethod('post')){

			$username = $request->input('username');
			$password = $request->input('password');
			$userInfo = Account::where(['username' => $username])->first();

			if(!$userInfo){
				ajax_error('用户名不存在');
			}

			if(!$userInfo->status){
				ajax_error('该用户被禁用，请联系管理员！');
			}

			$md5_password = md5($password);

			if($md5_password != $userInfo->passwd){
				ajax_error('密码不正确');
			}else{
				Session::put('user_id',$userInfo->id);
				Session::put('user_info',$userInfo->toArray());
				Session::save();
				ajax_success('登录成功');
			}
		}
		return view('users.login');
	}

	/**
	 * 后台账号退出页面
	 */
	public function logout(){
		Session::forget('user_id');
		Session::forget('user_info');
		return redirect()->route('users/login');
	}

}














