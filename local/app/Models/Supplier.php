<?php

namespace App\Models;


class Supplier extends Base
{

    protected $table = 'finance_supplier';

    /**
     * 返回银行转账类型对应的字符串
     *
     * @param $type
     * @return string
     */
    public static function parseTransferType($type)
    {
        switch ($type) {
            case 1:
                $str = '行内转账';
                break;
            case 2;
                $str = '同城跨行';
                break;
            case 3:
                $str = '异地跨行';
                break;
            default:
                $str = '未定义';
        }
        return $str;
    }
}