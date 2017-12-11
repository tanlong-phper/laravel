<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/7 0007
 * Time: 14:05
 */

namespace App\Models;


class TnetArea extends Base
{
    /**
     * 省市地三级联动
     *
     * @var string
     */
    protected $table = 'tnet_area';

    /**
     * 寻找某父ID下的所有子ID
     *
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getChild($parentId=1){
        return $this->where('parent_id',$parentId)
            ->select('area_id',
                'area_name',
                'parent_id')
            ->orderBy('area_order','asc')
            ->get();
    }
}