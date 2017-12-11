<?php
namespace App\Models;

class FinanceSupplier extends Base {
	protected $table = 'finance_supplier';
	

	public function scopeGetPasswd($query, $id, $pwd){
		if(empty($pwd) || empty($id)){
			return '';
		}
		$safe_code = '6A61750BB30490604B7DF6FD8DA9FA8E';
		$md5 = md5($safe_code.$id.$pwd);
		return "2".$md5;
	}
}