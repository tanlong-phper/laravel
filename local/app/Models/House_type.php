<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House_type extends Base
{
	public $table = 'house_type';

	public function recursion($pid = 0, $num = 0) {
		$optionStr = '';
		$arr = $this->where('pid', $pid)->get();
		$gang = str_repeat('&nbsp;&nbsp;&nbsp;', $num);
		$num++;
		foreach ($arr as $value) {
			$name = $value->name;
			$pid = $value->id;
			$optionStr .= "<option value='{$pid}'>{$gang}{$name}</option>";
			$optionStr .= $this->recursion($pid,$num);
		}
		return $optionStr;
	}

	public function showOptionGetName($pid = 0, $num = 0) {
		$optionStr = '';
		$arr = $this->where('pid', $pid)->get();
		$gang = str_repeat('&nbsp;&nbsp;&nbsp;', $num);
		$num++;
		foreach ($arr as $value) {
			$name = $value->name;
			$pid = $value->id;
			$optionStr .= "<option value='{$name}'>{$name}</option>";
			$optionStr .= $this->recursion($pid,$num);
		}
		return $optionStr;
	}

	function showOptionVstr($pid = 0,$num = 0,$fstr = '') {
		$optionStr = "";
		$arr = $this->where('pid',$pid)->get();
		$gangStr = str_repeat('&nbsp;&nbsp;&nbsp;', $num);
		$num++;
		foreach ($arr as $ob) {
			$typeName = $ob->name;
			$fstrn = $fstr.">".$typeName;
			$typeId = $ob->id;
			$optionStr .= "<option value='{$fstr}>{$typeName}'>{$gangStr}{$typeName}</option>";
			$sonStr = $this->showOptionVstr($typeId,$num,$fstrn);
			$optionStr .= $sonStr;
		}
		return $optionStr;
	}

	public function recursionArr($pid = 0) {
		$array = [];
		$arr = $this->where('pid',$pid)->get();
		foreach ($arr as $value) {
			if ($value->pid == $pid) {
				$value['child'] = $this->recursionArr($value->id);
				$array[] = $value;
			}
		}
		return $array;
	}
}
