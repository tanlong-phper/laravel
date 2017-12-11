<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26 0026
 * Time: 14:06
 */

namespace App\Models;

//use Maatwebsite\Excel\Facades\Excel;



use Maatwebsite\Excel\Facades\Excel;

class TbuyDzd extends Base
{

    /**
     * 对账单主表
     *
     * @var string
     */
    protected $table = 'tbuy_dzd';

    protected $primaryKey='dzd_id';

    /**
     * T+7格式化成EXCEL数组
     *
     * @param $res
     * @return array
     */
    public static function parseToExcelForS($res)
    {
        $data=[];
        foreach ($res as $key=>$item){
            $data[]=[
                $key+1,
                $item->supplier_name,
                    $item->details['settle_price'],
                    $item->details['total_sales'],
                    $item->details['service_charge'],
                    ' '.$item->bank_no,
                    $item->bank_name,
                    $item->cardholder,
                    self::transferTypeToStr([$item->transfer_type])
            ];
        }
        return $data;
    }

    /**
     * 转账类型转换成字符串
     *
     * @param $transfer_type
     * @return string
     */
    public static function transferTypeToStr($transfer_type){
        switch ($transfer_type){
            case 1:
                return '行内转账';
            case 2:
                return '同城跨行';
            case 3:
                return '异地跨行';
            default:
                return '';
        }
    }

    /**
     * T+1格式化成EXCEL数组
     *
     * @param $res
     * @return array
     */
    public static function parseToExcelForO($res)
    {
        $data=[];
        foreach ($res as $key=>$item){
            $data[]=[
                $key+1,
                $item->details_id,
                $item->nodecode,
                $item->consignee_name,
                $item->mobile_no,
                $item->country.$item->province.$item->city.$item->region.$item->address,
                $item->product_name,
                $item->price,
                $item->buy_count,
                $item->cost_price*$item->buy_count,
                $item->amount,
                $item->supplier_name,
                $item->pay_time,
                @implode(PHP_EOL,$item->pay_type_str)
            ];
        }
        return $data;
    }

    /**
     * 获得一个对账单的基本信息
     *
     * @param $dzdId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getBaseInfo($dzdId){
        return $this->find($dzdId);
    }

    /**
     * 根据结算单ID导出EXCEL
     *
     * @param $dzdId
     */
    public function exportExcel($dzdId){
        $baseInfo=$this->getBaseInfo($dzdId);
            if($baseInfo->dzd_type==1){
//                T+1格式查询子表信息
                $dzdDetail=with(new TbuyDzdDetail())->getDetail($dzdId);
                $data=[];
                foreach ($dzdDetail as $key=>$val){
                    $data[]=[
                        $key+1,
                        $val->details_id,
                        $val->nodecode,
                        $val->consignee_name.PHP_EOL.$val->mobile_no.PHP_EOL.$val->all_address,
                        $val->product_name,
                        $val->cost_price,
                        $val->buy_count,
                        $val->buy_count*$val->cost_price,
                        $val->amount,
                        str_replace('订单明细','',$baseInfo->table_name),
                        $val->pay_time,
                        @implode('<br>',json_decode($val->pay_type_str,true)),
                        $val->remark
                    ];
                }
                Excel::create($baseInfo->table_name,function ($excel) use($baseInfo,$data){
                    $excel->sheet('T+1结算单',function ($sheet) use($baseInfo,$data){
                        $sheet->rows(array(
                            array($baseInfo->table_name),
                            array($baseInfo->date_str),
                            array('序号','','付款人电话','收货人信息','商品名称','成本价','数量','结算总价','销售金额','供应商简称','付款时间','支付方式','备注'),
                        ));//同时增加多行
                        $sheet->rows($data);
                        $sheet->mergeCells('A1:M1');//合并
                        $sheet->mergeCells('A2:M2');//合并
                        $sheet->setBorder('A3:M'.(count($data)+3), 'thin');//设置外边框
//                $sheet->setAutoSize(true);
                        $sheet->setWidth([
                            'A'=>'20',
                            'B'=>'20',
                            'C'=>'20',
                            'D'=>'20',
                            'E'=>'20',
                            'F'=>'20',
                            'G'=>'20',
                            'H'=>'20',
                            'I'=>'20',
                            'J'=>'20',
                            'K'=>'20',
                            'L'=>'20',
                            'M'=>'20'
                        ]);
                        $sheet->cells('A1:M2',function ($cells){
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        $sheet->appendRow(array(
                            '核准:', '','财务核查:',$baseInfo->finance_name,'运营复审:',$baseInfo->review_name,'运营初审:',$baseInfo->trial_name,'制表:','',$baseInfo->trial_time
                        ));//在最后增加一行数据
                    });
                })->download('xls');
            }else{
//                T+7格式查询子表信息
                $baseInfo=$this->getBaseInfo($dzdId);
                $dzdDetail=with(new TbuyDzdDetailS())->getDetail($dzdId);
                foreach ($dzdDetail as $v){
                    $v->transfer_str=Supplier::parseTransferType($v->transfer_type);
                }
                $data=[];
                foreach ($dzdDetail as $key=>$val){
                    $data[]=[
                        $key+1,
                        $val->supplier_name,
                        $val->finance_charge,
                        $val->service_charge,
                        $val->finance_charge-$val->service_charge,
                        $val->total_sales,
                        ' '.strval($val->bank_no),
                        $val->bank_name,
                        $val->cardholder,
                        $val->transfer_str,
                        $val->remark
                    ];
                }
                Excel::create($baseInfo->table_name,function ($excel) use($baseInfo,$data){
                    $excel->sheet('T+7结算单',function ($sheet) use($baseInfo,$data){
                        $sheet->rows(array(
                            array($baseInfo->table_name),
                            array($baseInfo->date_str),
                            array('序号','供应商简称','应付金额','手续费','实付金额','销售总额','公司账号','开户行','户名','银行转账类别','备注'),
                        ));//同时增加多行
                        $sheet->rows($data);
                        $sheet->mergeCells('A1:K1');//合并
                        $sheet->mergeCells('A2:K2');//合并
                        $sheet->setBorder('A3:K'.(count($data)+3), 'thin');//设置外边框
//                $sheet->setAutoSize(true);
                        $sheet->setWidth([
                            'A'=>'20',
                            'B'=>'20',
                            'C'=>'20',
                            'D'=>'20',
                            'E'=>'20',
                            'F'=>'20',
                            'G'=>'20',
                            'H'=>'20',
                            'I'=>'20',
                            'J'=>'20',
                            'K'=>'20',
                        ]);
                        $sheet->cells('A1:K2',function ($cells){
                            $cells->setAlignment('center');
                            $cells->setValignment('center');
                        });
                        $sheet->appendRow(array(
                            '核准:', '','财务核查:',$baseInfo->finance_name,'运营复审:',$baseInfo->review_name,'运营初审:',$baseInfo->trial_name,'制表:','',$baseInfo->trial_time
                        ));//在最后增加一行数据
                    });
                })->download('xls');
            }
    }

    /**
     * 通用导出EXCEL
     *
     * @param $fileName
     * @param array $firstRow
     * @param array $content
     */
    public static function exportFileNormal($fileName,array $firstRow,array $content){
        Excel::create($fileName,function ($excel) use($firstRow,$content){
            $excel->sheet('导出表',function ($sheet) use($firstRow,$content){
                $sheet->rows(array(
                    $firstRow
                ));
                //同时增加多行
                $sheet->rows($content);
                $sheet->setAutoSize(true);
            });
        })->download('xls');
        exit();
    }

    /**
     * 根据ID获取一批结算单数组
     *
     * @param array $ids
     * @return array
     */
    public function queryByIds(array $ids){
        $res=$this->whereIn('dzd_id',$ids)
            ->get();
        $data=[];
        foreach ($res as $k=>$v){
            $data[]=[
                $k+1,
                $v->dzd_id,
                $v->table_name.$v->date_str,
                $v->unusual==1?'异常结算':'正常结算',
                $v->trial_name,
                $v->review_name,
                $v->finance_name,
                $v->settle_price-$v->service_charge,
                $v->trial_time
            ];
        }
        return $data;
    }

}