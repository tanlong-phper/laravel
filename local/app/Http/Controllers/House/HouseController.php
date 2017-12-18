<?php

namespace App\Http\Controllers\House;
use App\Http\Controllers\BaseController;
use App\Models\House_message;
use App\Models\House_image;
use App\Models\Landlord_message;
use App\Models\House_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use DB;
class HouseController extends BaseController {
	/**
	 * 房源列表
	 */
	public function houseLister() {
		$houseMessage = new House_message();
		$gather = $houseMessage->orderBy('msgid','desc')->paginate(10);
		return view('house.houseLister',['houseObj'=>$gather]);
	}
	/**
	 *房源添加
	 */
	public function houseAdd() {
		$houseType = new House_type();
		$optionStr = $houseType->showOptionGetName();

		return view('house.houseAdd',['optionStr'=>$optionStr]);
	}
	/**
	 *房源添加表单提交
	 */
	public function save(Request $param) {
		$houseData = Input::all();
		$houseData['rim_message'] = isset($houseData['peripheral_information']) ? implode(',',$houseData['peripheral_information']) : '';
		$houseData['house_facility'] = isset($houseData['house_facility']) ? implode(',',$houseData['house_facility']) : '';
		$houseData['landlord_id'] = $houseData['landlord_identity'];
		$houseData['serial_number'] = '';
		$houseData['intermediary_id'] = Session::get('user_id') ? Session::get('user_id') : '';
		unset( $houseData['_token'],
				$houseData['peripheral_information'],
				$houseData['landlord_name'],
				$houseData['landlord_identity'],
				$houseData['landlord_email'],
				$houseData['landlord_phone'],
				$houseData['landlord_sex'],
				$houseData['landlord_site'],
				$houseData['landlord_remark'],
		        $houseData['upload']);
		$houseMessage = new House_message();



		$houseId = $houseMessage->insertGetId($houseData);  //保存
		$files = $param->file('upload');
		if ($houseId) {
			$landlordMessage = new Landlord_message();
			$landlordDate = [
					'intermediary_id' => Session::get('user_id') ? Session::get('user_id') : '',//房源中介ID
					'landlord_name' => $param->landlord_name,      //房东姓名
					'landlord_identity' => $param->landlord_identity,  //房东证件ID
					'landlord_email' => $param->landlord_email,    //房东邮箱
					'landlord_phone' => $param->landlord_phone,    //房东联系号码
					'landlord_sex' => $param->landlord_sex,        //房东性别
					'landlord_site' => $param->landlord_site,      //房东联系地址
					'landlord_remark' => $param->landlord_remark,    //房东备注
					'house_id' => $houseId                          //房源ID
			];
			$landlordId = $landlordMessage->insertGetId($landlordDate);
			if ($landlordId && $files) {
				foreach ($files as $file) {
					$houseImage = new House_image();
					$imagename = $file->store('','local');
					if ($imagename) {
						$houseImage->house_msg_id = $houseId;
						$houseImage->house_imagename = $imagename;
						$houseImage->save();
					}
				}
				return redirect('house/houseAdd')->with('success','新增房源成功！');
			} elseif ($landlordId) {
				return redirect('house/houseAdd')->with('success','新增房源成功！未上传房源图片');
			}
		} else {
			echo "<script>alert('添加失败');history.go(-1);</script>";
		}
	}
	/**
	 *房源更新列表
	 */
	public function updateList() {
		$houseMessage = new House_message();
		$gather = $houseMessage->orderBy('msgid','desc')->paginate(10);
		return view('house.updateList',['houseObj'=>$gather]);
	}

	/**
	 *房源修改详细页
	 */
	public function detail($id) {
		$houseType = new House_type();
		$optionStr = $houseType->showOptionGetName();
		$houseMsg = DB::table('house_message')
				->join('landlord_message', 'house_message.msgid', '=', 'landlord_message.house_id')
				->select('house_message.*', 'landlord_message.*')
				->where('msgid',$id)
				->first();
		$houseImg = new House_image();
		$imgArr = $houseImg->where('house_msg_id','=',$id)->get();
		return view('house.updateDetail',['houseMsg'=>$houseMsg,'imgArr'=>$imgArr,'optionStr'=>$optionStr]);
	}

	/**
	 *Ajax请求删除图片
	 */
	public function del() {
		$id = $_GET['id'];
		$houseImg = new House_image();
		$houseImgs = $houseImg->where('imgid',$id)->first();
		$imagename = $houseImgs->house_imagename;
		@unlink('./uploads/'.$imagename);
		$re = $houseImg->where('imgid',$id)->delete();
		if ($re) {
			return '1';
		} else {
			return '0';
		}
	}
	/**
	 *房源信息修改
	 */
	public function uSave(Request $param) {
		$msgId = $param->msgId;
		$landId = $param->landId;
		$houseData = [
				'house_name' => htmlspecialchars($param->house_name),           //房源名称
				'house_location' => htmlspecialchars($param->house_location),   //房源地址
				'house_structure' => htmlspecialchars($param->house_structure), //房源结构
				'house_price' => htmlspecialchars($param->house_price),         //房租价格
				'house_size' => htmlspecialchars($param->house_size),           //房子大小/平方米
				'house_type' => htmlspecialchars($param->house_type),           //房源类型
				'house_facility' => implode(',',$param->house_facility),          //房屋设备 数组转字符串
				'house_keyword' => htmlspecialchars($param->house_keyword),     //房源信息关键字
				'house_brief' => htmlspecialchars($param->house_brief),         //房源信息简介
				'house_rise' => htmlspecialchars($param->house_rise),           //房源起租期
				'house_duration' => htmlspecialchars($param->house_duration),   //房源租期时长
				'house_status' => $param->house_status,                         //房屋状态
				'landlord_id' => htmlspecialchars($param->landlord_identity)//房东身份ID
		];
		DB::table('house_message')->where('msgid', $msgId)->update($houseData);
		$landlordDate = [
				'landlord_name' => htmlspecialchars($param->landlord_name),      //房东姓名
				'landlord_identity' => htmlspecialchars($param->landlord_identity),         //房东证件ID
				'landlord_email' => htmlspecialchars($param->landlord_email),    //房东邮箱
				'landlord_phone' => htmlspecialchars($param->landlord_phone),    //房东联系号码
				'landlord_sex' => htmlspecialchars($param->landlord_sex),        //房东性别
				'landlord_site' => htmlspecialchars($param->landlord_site),      //房东联系地址
				'landlord_remark' => htmlspecialchars($param->landlord_remark),    //房东备注
				'house_id' => $msgId
		];
		DB::table('landlord_message')->where('landid', $landId)->update($landlordDate);
		$files = $param->file('upload');
		if ($files) {
			foreach ($files as $file) {
				$houseImage = new House_image();
				$imageName = $file->store('','local');
				if ($imageName) {
					$houseImage->house_msg_id = $msgId;
					$houseImage->house_imagename = $imageName;
					$houseImage->save();
				}
			}
		}
		return redirect('house/updateList/detail/'.$msgId)->with('success','更新成功！');
	}
	/**
	 *房源详细信息
	 */
	public function houseDetail($id) {
		$houseMsg = DB::table('house_message')
				->join('landlord_message', 'house_message.msgid', '=', 'landlord_message.house_id')
				->select('house_message.*', 'landlord_message.*')
				->where('msgid',$id)
				->first();
		$houseImg = new House_image();
		$imgArr = $houseImg->where('house_msg_id','=',$id)->get();
		return view('house.houseDetail',['houseMsg'=>$houseMsg,'imgArr'=>$imgArr]);
	}
 }