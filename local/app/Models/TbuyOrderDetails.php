<?php
namespace App\Models;


class TbuyOrderDetails extends Base {
	protected $table = 'tbuy_order_details';
	

	public function scopeCheckSupplierId($query, $details_id, $supplier_id)
	{
		$product_id = $this->where(['details_id' => $details_id])->value('product_id');

		$product_supplier_id = Product::where(['product_id' => $product_id])->value('supplier_id');

		if($product_supplier_id != $supplier_id) return false;

		return true;
	}
}