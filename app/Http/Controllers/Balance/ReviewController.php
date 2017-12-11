<?php

namespace App\Http\Controllers\Balance;

use App\Http\Controllers\BaseController;
use App\Models\Supplier;
use App\Models\TbuyDzd;
use App\Models\TbuyDzdDetail;
use App\Models\TbuyDzdDetailS;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends BaseController
{
    /**
     * T+1复审列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus1Review(){
        if(request()->get('export')=='true'){
            $content=with(new TbuyDzd())->queryByIds(explode(',',request()->input('details_list')));
            TbuyDzd::exportFileNormal('结算单导出表'.date('Y-m-d'),[
                '序号','结算单ID','结算单简称','结算单类型','初审人','复审人','财务审核','实付金额','生成时间'
            ],$content);
        }
        $sort=!empty(request()->get('sort'))?request()->get('sort'):'asc';
        $pageSize=!empty(request()->get('pagesize'))?request()->get('pagesize'):10;
        $res=DB::table('tbuy_dzd')
            ->where('dzd_status',1)
            ->where('dzd_type',1)
            ->orderBy('tbuy_dzd.trial_time',$sort);//选择对账单类型为1
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
        return view('balance.review.t_plus1_review',['data'=>$res]);
    }

    /**
     * T+1复审详情
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus1ReviewDetail(){
        if(request()->isXmlHttpRequest()){
            //ajax请求
            if(!request()->has('dzd_id')){
                return response()->json(['status'=>0,'msg'=>'缺少参数','data'=>'']);
            }
            DB::beginTransaction();
            //对账单状态更新为2
            $res=DB::table('tbuy_dzd')
                ->where('dzd_id',request()->input('dzd_id'))
                ->update([
                    'dzd_status'=>2,
                    'review_name'=>request()->input('review_name'),
                    'review_remark'=>request()->input('review_remark'),
                    'review_time'=>date('Y-m-d H:i:s')
                ]);
            if($res){
                //子订单状态更改为IS_BALANCE=4 2017-10-18 16:52:05
                $details_id=DB::table('tbuy_dzd_detail')->where('dzd_id',request()->input('dzd_id'))->pluck('details_id');
                if(empty($details_id)){
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'子订单缺失','data'=>'']);
                }
                DB::table('tbuy_order_details')->whereIn('details_id',$details_id)->update([
                    'is_balance'=>4
                ]);
                DB::commit();
                return response()->json(['status'=>1,'msg'=>'更新成功','data'=>'']);
            }else{
                DB::rollBack();
                return response()->json(['status'=>0,'msg'=>'保存失败,请稍后再试','data'=>'']);
            }
        }else{
            $dzdId=request()->get('dzd_id');
            if(empty($dzdId)){
                return abort(403,'对账单ID不存在');
            }
            //get请求
            $baseInfo=with(new TbuyDzd())->getBaseInfo($dzdId);
            $dzdDetail=with(new TbuyDzdDetail())->getDetail($dzdId);
            return view('balance.review.t_plus1_review_detail',['baseInfo'=>$baseInfo,'dzdDetail'=>$dzdDetail]);
        }
    }

    /**
     * T+7复审列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus7Review(){
        if(request()->get('export')=='true'){
            $content=with(new TbuyDzd())->queryByIds(explode(',',request()->input('details_list')));
            TbuyDzd::exportFileNormal('结算单导出表'.date('Y-m-d'),[
                '序号','结算单ID','结算单简称','结算单类型','初审人','复审人','财务审核','实付金额','生成时间'
            ],$content);
        }
        $sort=!empty(request()->get('sort'))?request()->get('sort'):'asc';
        $pageSize=!empty(request()->get('pagesize'))?request()->get('pagesize'):10;
        $res=DB::table('tbuy_dzd')
            ->where('dzd_status',1)
            ->where('dzd_type',2)
            ->orderBy('tbuy_dzd.trial_time',$sort);//选择对账单类型为2
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
        return view('balance.review.t_plus7_review',['data'=>$res]);
    }

    /**
     *  T+7复审详情
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tPlus7ReviewDetail(){
        if(request()->isXmlHttpRequest()){
            //ajax请求
            if(!request()->has('dzd_id')){
                return response()->json(['status'=>0,'msg'=>'缺少参数','data'=>'']);
            }
            //对账单状态更新为2
            DB::beginTransaction();
            $res=DB::table('tbuy_dzd')
                ->where('dzd_id',request()->input('dzd_id'))
                ->update([
                    'dzd_status'=>2,
                    'review_name'=>request()->input('review_name'),
                    'review_remark'=>request()->input('review_remark'),
                    'review_time'=>date('Y-m-d H:i:s')
                ]);
            //子订单状态更改为IS_BALANCE=4
            if($res){
                //子订单状态更改为IS_BALANCE=4 2017-10-18 16:52:05
                $ids=DB::table('tbuy_dzd_detail_s')->where('dzd_id',request()->input('dzd_id'))->pluck('details_ids');
                $tmp=[];
                foreach($ids as $v){
                    $tmp=array_merge($tmp,explode(',',$v));
                }
                if(empty($tmp)){
                    DB::rollBack();
                    return response()->json(['status'=>0,'msg'=>'子订单ID参数无效','data'=>'']);
                }
                DB::table('tbuy_order_details')->whereIn('details_id',$tmp)->update([
                    'is_balance'=>4
                ]);
                DB::commit();
                return response()->json(['status'=>1,'msg'=>'更新成功','data'=>'']);
            }else{
                DB::rollBack();
                return response()->json(['status'=>0,'msg'=>'保存失败,请稍后再试','data'=>'']);
            }
        }else{
            $dzdId=request()->get('dzd_id');
            if(empty($dzdId)){
                return abort(403,'对账单ID不存在');
            }
            //get请求
            $baseInfo=with(new TbuyDzd())->getBaseInfo($dzdId);
            $dzdDetail=with(new TbuyDzdDetailS())->getDetail($dzdId);
            foreach ($dzdDetail as $v){
                $v->transfer_str=Supplier::parseTransferType($v->transfer_type);
            }
            return view('balance.review.t_plus7_review_detail',['baseInfo'=>$baseInfo,'dzdDetail'=>$dzdDetail]);
        }
    }
}


































