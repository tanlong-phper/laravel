<?php
namespace App\Http\Controllers\Nation;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use DB;
use App\Models\Nation;
use App\Models\Province;

class NationController extends BaseController {
	/**
	 * index
	 */
	public function add() {
		$nation = new Nation();
		$nationArr = $nation->get();
		$province = new Province();
		$provinceArr = $province->get();
		return view('nation.add',['nationArr'=>$nationArr,'provinceArr'=>$provinceArr]);
	}

	/**
	 *国家
	 */
	public function state() {
		$data = Input::all();
		unset($data['_token']);
		$result = DB::table('nation')->insertGetId($data);
		if ($result) {
			return redirect('nation/add')->with('success','新增成功！');
		}
	}

	/**
	 *省份
	 */
	public function province() {
		$data = Input::all();
		unset($data['_token']);
		$result = DB::table('province')->insertGetId($data);
		if ($result) {
			return redirect('nation/add')->with('success','新增成功！');
		}
	}

	/**
	 *市区
	 */
	public function city() {
		$data = Input::all();
		unset($data['_token']);
		$result = DB::table('city')->insertGetId($data);
		if ($result) {
			return redirect('nation/add')->with('success','新增成功！');
		}
	}
}