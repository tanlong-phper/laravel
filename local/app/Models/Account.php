<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Base
{

//    public $table = 'admin_accounts';


    /*public function getDepartmentIdAttribute($value){
        return DB::table('ADMIN_DEPARTMENTS')->where('id',$value)->value('name');
    }

    public function getRoleIdAttribute($value){
        return DB::table('ADMIN_ROLES')->where('id',$value)->value('name');
    }

    public function getStatusAttribute($value){
        $status = ['禁用','启用'];
        $return = isset($status[$value]) ? $status[$value] : '未知';
        return $return;
    }*/
}












