<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Store extends Base {

    /**
     * 查询指定ID号的店铺信息
     * @param `id` 店铺ID号
     */
    public function get_store_by_id($id) {
        if(!$id) {
            return false;
        }
        return DB::table('tosm_store')->where('sid',$id)->first();
    }
}