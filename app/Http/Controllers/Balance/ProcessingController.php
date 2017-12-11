<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\Models\TbuyDzd;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcessingController extends BaseController
{
    /**
     * 结算中列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inSettlementList(){
        if(request()->isXmlHttpRequest()){
            DB::beginTransaction();
            $res1=DB::table('tbuy_dzd')->where(['dzd_id'=>request()->input('dzd_id')])->update([
                'dzd_status'=>3,//置为第三阶段
                'finance_remark'=>request()->input('finance_remark'),//财务备注
                'finance_time'=>date('Y-m-d H:i:s'),//财务标记时间
                'finance_name'=>request()->input('finance_name')//财务审核人员姓名
            ]);
            //判断是T+1 or T+7 的订单
            $dzd_type=DB::table('tbuy_dzd')->where(['dzd_id'=>request()->input('dzd_id')])->pluck('dzd_type')->pop();
            if($dzd_type==1){
//                T+1类型
                $ids=DB::table('tbuy_dzd_detail')->where('dzd_id',request()->input('dzd_id'))->pluck('details_id');
                //子订单中的结算状态变更为5
                $res2=DB::table('tbuy_order_details')->whereIn('details_id',$ids)->update([
                    'is_balance'=>5
                ]);
                if($res1 and $res2){
                    DB::commit();
                    return response()->json(['status'=>1,'msg'=>'标记成功','data'=>'']);
                }else{
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'标记失败,请重试','data'=>'']);
                }
            }else{
//                T+7类型
                $ids=DB::table('tbuy_dzd_detail_s')->where('dzd_id',request()->input('dzd_id'))->pluck('details_ids');
                $tmp=[];
                foreach($ids as $v){
                    $tmp=array_merge($tmp,explode(',',$v));
                }
                if(empty($tmp)){
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'子订单ID参数无效','data'=>'']);
                }
                $res2=DB::table('tbuy_order_details')->whereIn('details_id',$tmp)->update([
                    'is_balance'=>5
                ]);
                if($res1 and $res2){
                    DB::commit();
                    return response()->json(['status'=>1,'msg'=>'标记成功','data'=>'']);
                }else{
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'标记失败,请重试','data'=>'']);
                }
            }
        }else{
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
                ->where('dzd_status',2)
                ->where('dzd_type',$dzdType)
                ->orderBy('tbuy_dzd.trial_time',$sort);
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
            return view('balance.processing.in_settlement_list',['data'=>$res]);
        }
    }
}
