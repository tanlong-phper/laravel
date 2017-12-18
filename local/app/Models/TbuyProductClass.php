<?php
namespace App\Models;


class TbuyProductClass extends Base {
	protected $table = 'tbuy_product_class';
	
	// 获取秒杀类目下的商品
	public function scopeMiaosha($query){
		$pluck = $query->where(['class_id'=>111097])->pluck('product_id')->toArray();
		return $pluck;
	}
}