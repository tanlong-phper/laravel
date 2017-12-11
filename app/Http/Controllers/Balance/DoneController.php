<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\Models\TbuyDzd;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DoneController extends BaseController
{
    /**
     * 已结算列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settledList(){
        if(request()->get('export')=='true'){
            $content=with(new TbuyDzd())->queryByIds(explode(',',request()->input('details_list')));
            TbuyDzd::exportFileNormal('结算单导出表'.date('Y-m-d'),[
                '序号','结算单ID','结算单简称','结算单类型','初审人','复审人','财务审核','实付金额','生成时间'
            ],$content);
        }
        $sort=!empty(request()->get('sort'))?request()->get('sort'):'asc';
        $pageSize=!empty(request()->get('pagesize'))?request()->get('pagesize'):10;
        $dzdType=request()->get('dzd_type',1);
        //将第二阶段的对账单提取出来
        $res=DB::table('tbuy_dzd')
            ->where('dzd_status',3)
            ->where('dzd_type',$dzdType)
            ->orderByDesc('tbuy_dzd.trial_time',$sort);
        if(request()->has('unusual')){
            $res->where('unusual',request()->get('unusual'));
        }
        if(request()->has('s_time')){
            $res->where('tbuy_dzd.trial_time','>=',request()->get('s_time'));
        }
        if(request()->has('e_time')){
            $res->where('tbuy_dzd.trial_time','<=',request()->get('e_time'));
        }
        if(request()->has('keyname') and request()->has('keyval')){
            switch (request()->get('keyname')){
                case 'dzd_id':
                    if(!is_numeric(request()->get('keyval'))){break;}//防止报错
                    $res->where('tbuy_dzd.dzd_id',request()->get('keyval'));
                    break;
                case 'table_name';
                    $res->where('tbuy_dzd.table_name','like','%'.request()->get('keyval').'%');
                    break;
                case 'trial_name':
                    $res->where('tbuy_dzd.trial_name','like','%'.request()->get('keyval').'%');
                    break;
                default;
            }
        }
        $res=$res->paginate($pageSize);
        return view('balance.done.settled_list',['data'=>$res]);
    }
}
