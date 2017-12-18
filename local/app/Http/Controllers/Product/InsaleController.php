<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\TbuyPayTypeGroup;
use App\Models\TbuyProductClass;
use App\Models\TbuyProductPayType;
use App\Models\TbuyProductPropertyValue;
use App\Models\TbuySku;
use App\Models\VbuyProductImgModel;
use DB;
use Illuminate\Http\Request;

class InsaleController extends BaseController
{
    /**
     * 在售商品列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $where = [];
        $tb_product = DB::table('tbuy_product');

        $class_sub = [];
        if(isset($request->search)){
            //查父级分类
            if(!empty($request->class1)){
                $class_sub = $class = DB::table('tbuy_class')->where('father_id',$request->class1)->get(['class_id','class_name'])->toArray();

                if($request->class2 == ''){
                    $class_sub_arr = [];
                    foreach($class as $v){
                        array_push($class_sub_arr, $v->class_id);
                    }
                    $tb_product->whereIn('class_id',$class_sub_arr);
                }else{
                    $tb_product->where('class_id',$request->class2);
                }

            }

            if(isset($request->up_status) && $request->up_status !== ''){
                $tb_product->where('up_status',$request->up_status);
            }

            if(isset($request->status) && $request->status !== ''){
                $tb_product->where('status',$request->status);
            }

            if($request->keyword){
                $tb_product->where($request->keyword_type , 'like' , '%'.$request->keyword.'%');
            }

            unset($_REQUEST['_token']);
        }

        $product = $tb_product->select('product_id','product_name','up_status','supplier_id','status')->orderBy('product_id', 'desc')->paginate(15);


        $month_date = date('Y-m-d',strtotime("-1 months", time()));

        foreach($product as &$value){

            $sku = DB::table('tbuy_sku')->select(DB::raw('SUM(stock) as total_stock, price'))->where('product_id', $value->product_id)->groupBy('product_id','price')->first();

            if(!empty($sku)){
                $value->total_stock = $sku->total_stock;
                $value->price = '&yen; '.$sku->price;
            }else{
                $value->total_stock = 0;
                $value->price = 0;
            }

            $value->img_url = DB::table('tbuy_product_img')->where(['product_id'=>$value->product_id,'is_default'=>1])->value('img_url');
            $sale = DB::table('tbuy_order_details')->where(['product_id'=>$value->product_id,['create_time','>=',$month_date]])->select(DB::raw('SUM(buy_count) as count'))->value('count');
            $value->sale = empty($sale) ? 0 : $sale;
            $value->supplier_name = DB::table('finance_supplier')->where('supplier_id', $value->supplier_id)->value('shortname');
        }


        $class = DB::table('tbuy_class')->where(['class_type'=>0, 'father_id'=>1])->select('class_id','class_name')->get();
//        $class_sub = DB::table('tbuy_class')->where(['class_type'=>0, 'father_id'=>$product->parent_class])->select('class_id','class_name')->get();

        return view('product.insale.index',['product'=>$product,'class'=>$class,'class_sub'=>$class_sub]);
    }

    /**
     * 编辑商品
     * @param $product_id
     * @return mixed
     */
    public function edit($product_id)
    {
        $supplier_list=DB::table('finance_supplier')->get();
        $class = DB::table('tbuy_class')->where(['class_type'=>0, 'father_id'=>1])->select('class_id','class_name')->get();
        $product = Product::where('product_id',$product_id)->first();
        $product->parent_class = DB::table('tbuy_class')->where('class_id', $product->class_id)->value('father_id');

        unset($_SESSION['update_product']);
        $_SESSION['update_product']['product'] = $product;

        $class_sub = DB::table('tbuy_class')->where(['class_type'=>0, 'father_id'=>$product->parent_class])->select('class_id','class_name')->get();
        return view('product.insale.edit',['product'=>$product,'supplier_list'=>$supplier_list,'class'=> $class,'class_sub'=> $class_sub]);
    }

    public function step1(Request $request){
        $_SESSION['update_product'] = array_merge($_SESSION['update_product'],$request->all());
        return redirect('product/insale/step2');
    }

    public function step2(Request $request){
        //提交处理
        if(!empty($request->all())){

            $remarks = $request->remarks;
            $data = [];
            foreach($remarks as $key => $value){
                $data[] = [
                    'remarks' => $remarks[$key],
                    'sale_price' => $request->sale_price[$key],
                    'cost_price' => $request->cost_price[$key],
                    'market_price' => $request->market_price[$key],
                    'stock' => $request->stock[$key],
                    'hl_integral' => $request->hl_integral[$key],
                    'pv' => $request->pv[$key],
                    'ga_integral' => $request->ga_integral[$key],
                    'haolian' => $request->haolian[$key],
                ];
            }

            $_SESSION['update_product']['sku'] = $data;
            return redirect('product/insale/step3');
        }


        $class_id = $_SESSION['update_product']['select_class'];
        $product_id = $_SESSION['update_product']['product']->product_id;

        $prop_lists = DB::table('tbuy_property')->where("class_id", $class_id)->orWhere('property_id',1)->where('status',1)->get();
        foreach($prop_lists as &$value){
            $value->property_value = DB::table('tbuy_property_value')->where('property_id',$value->property_id)->where('status',1)->get(['value_id','value_text']);
        }

        $property = DB::table('tbuy_product_property_value')->where('product_id', $product_id)->get(['property_value_id', 'remarks']);

        $sku = DB::table('tbuy_sku')->where('product_id', $product_id)->get();
        foreach($sku as $key => $value){
            $temp = explode(',',$value->remarks);
            $base_attr = '';
            foreach($temp as $k => $v){
                list(,$prop_value) = explode(':',$v);
                $base_attr .= $prop_value.'/';

                foreach($property as $k1 => $v1){
                    if($v == $v1->remarks){
                        $temp[$k] = $temp[$k].'@'.$v1->property_value_id;
                    }
                }
            }

            $sku[$key]->remark_temp = implode(',',$temp);
            $sku[$key]->base_attr = rtrim($base_attr, '/');
        }

        return view('product.insale.step2',['prop_lists'=>$prop_lists,'property'=>$property,'sku'=>$sku]);
    }

    public function step3(Request $request){
        if(!empty($request->all())){
            $data = $request->all();
            unset($data['_token']);
            $_SESSION['update_product'] = array_merge($_SESSION['update_product'],$data);
            return redirect('product/insale/step4');
        }

        $update_product = $_SESSION['update_product'];

        $product_id = $update_product['product']->product_id;
        $img_url = DB::table('tbuy_product_img')->where('product_id', $product_id)->get(['img_url']);

        return view('product.insale.step3',['img_url'=>$img_url, 'product'=>$update_product['product']]);
    }

    public function step4(Request $request){
        if(!empty($request->all())){
            $data = $request->all();
            unset($data['_token']);
            $_SESSION['update_product'] = array_merge($_SESSION['update_product'],$data);
            return redirect('product/insale/store');
        }
        $pay_type_id = TbuyProductPayType::where(['product_id' => $_SESSION['update_product']['product']->product_id])->value('pay_type_id');
        $pay_type_group = TbuyPayTypeGroup::where(['status' => 1])->orderBy('id', 'asc')->get();
        return view('product.insale.step4',['pay_type_group'=>$pay_type_group,'pay_type_id'=>$pay_type_id]);
    }

    /**
     * 更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $update_product = $_SESSION['update_product'];

        $product = $_SESSION['update_product']['product'];

        $params = $update_product;
        $sku_lists = $params['sku'];

        if(!isset($product->product_id)){
            return false;
        }

        DB::beginTransaction();
        try{

            $status = array();
            $data = array(
                'class_id' => $params['select_class']?:0, //商品分类ID
                'status' => 1,  //禁用=0,启用=1,删除=2,审核中=3
                'remarks' => $params['remarks'], //商品备注
                'details' => empty($params['content']) ? '' : htmlspecialchars_decode($params['content']), //商品详情
                'product_name' => $params['product_name'], //商品名称
                'supplier_id' => isset($params['supplier_id']) ? (int)$params['supplier_id'] : 0, //供应商id
            );
            $status[] = DB::table('tbuy_product')->where('product_id',$product->product_id)->update($data);

            DB::table('tbuy_sku')->where('product_id', $product->product_id)->delete();
            //添加商品属性值
            foreach($sku_lists as &$val){
                $sku_ramark = explode(',', $val['remarks']);
                foreach ($sku_ramark as $kk => $vv) {
                    $sku_ramark[$kk] = strstr($vv, '@', true);
                }
                $sku_ramark = implode(',',$sku_ramark);

                $sku_id = TbuySku::getNextSeq();
                $sku_data = array(
                    'sku_id'		=> $sku_id,
                    'product_id' 	=> $product->product_id,
                    'stock'			=> $val['stock'],
                    'price'			=> $val['sale_price'],
                    'market_price'	=> $val['market_price'],
                    'remarks'		=> $sku_ramark,
                    'cost_price'	=> $val['cost_price'],
                    'sku_no' 		=> '7000'.$product->product_id,
                    'introducer_pv_percent' => 0,
                    'hl_integral' 	=> $val['hl_integral'],
                    'pv' 			=> $val['pv'],
                    // 'pv_percent_19' => 100,
                    'ga_integral' 	=> $val['ga_integral'],
//                    'gastore_integral' 	=> $val['gastore_integral'],
//                    'offline_shop_percent' 	=> $val['offline_shop_percent'],
                );
                $status[] = DB::table('tbuy_sku')->insert($sku_data);
                $val['sku_id'] = $sku_id;
            }


            //tbuy_product_class 商品分类
            $pcid = TbuyProductClass::where(['product_id'=>$product->product_id,'class_id'=>$params['select_class']])->value('id');
            if($pcid){
                $status[] = TbuyProductClass::where(['product_id'=>$product->product_id])->update(['class_id'=>$params['select_class']]);
            }

            TbuyProductPropertyValue::where('product_id', $product->product_id)->delete();
            // tbuy_product_property_value
            foreach ($sku_lists as $k => $v) {
                $prop = explode(',', $v['remarks']);
                foreach ($prop as $kk => $vv) {
                    $prop_remarks = strstr($vv, '@',true);
                    $property_value_id = ltrim(strstr($vv, '@'),'@');
                    $con = [
                        'id' => TbuyProductPropertyValue::getNextSeq('seq_tbuy_product_prop_value'),
                        'product_id' => $product->product_id,
                        'property_value_id' => $property_value_id,
                        'sku' => $v['sku_id'],
                        'status' => 1,
                        'remarks' => $prop_remarks,
                    ];
                    $status[] = TbuyProductPropertyValue::insert($con);
                }
            }

            //修改支付方式
            //
            if($oldPayType = TbuyProductPayType::where(['product_id' => $product->product_id])->first())
            {
                $paytypeUpdate = [
                    'pay_type_id' => $params['pay_type_id'],
                ];
                if($params['pay_type_id'] != $oldPayType['pay_type_id']){
                    $paytypeUpdate['update_time'] = date('Y-m-d H:i:s');
                    $paytypeUpdate['before_pay_type_id'] = $oldPayType['pay_type_id'];
                }
                $status[] = TbuyProductPayType::where(['product_id' => $product->product_id])->update($paytypeUpdate);
            }else{

                // 插入支付方式
                $con = [
                    'id' => TbuyProductPayType::getNextSeq(),
                    'product_id' => $product->product_id,
                    'pay_type_id' => $params['pay_type_id'],
                    'remarks' => '',
                ];
                $status[] = TbuyProductPayType::insert($con);
            }


            VbuyProductImgModel::where('product_id', $product->product_id)->delete();
            //增加Img
            foreach ($params['pic'] as $key=>$val){
                if(empty($val)) continue;
                $insert_img=array(
                    'id'=>VbuyProductImgModel::getNextSeq(),
                    'product_id'=>$product->product_id,
                    'img_type'=>1,
                    'unique_code'=>md5($val),
                    'img_url'=>$val,
                    'is_default'=>($key==0?1:0),
                    'remarks'=>$params['product_name'],
                    'create_time'=>date('Y-m-d H:i:s'),
                );
                $status[] = VbuyProductImgModel::insert($insert_img);
            }
            if(checkTrans($status)){
                DB::commit();
                unset($_SESSION['update_product']);
                return redirect()->route('product/insale/save')->with('success','修改商品成功！');
            }else{
                throw new \Exception("添加失败");
            }
        }catch (\Exception $e){
            DB::rollback();
            p($e->getMessage());
//            $this->ajax_error($e->getMessage());
        }
    }

    /**
     * 保存的页面
     * @return mixed
     */
    public function save(){
        return view('product.insale.save');
    }

    /**
     * 更新是否上架
     * @param $product_id
     * @param $status
     * @return mixed
     */
    public function updateStatus($product_id, $status){
        $status = !$status;
        $rs = DB::table('tbuy_product')->where('product_id',$product_id)->update(['up_status'=>$status]);

        if($rs){
            return $this->ajaxSuccess('修改成功！', url('product/insale').'?'.http_build_query($_REQUEST));
        }else{
            return $this->ajaxSuccess('修改失败！', url('product/insale'));
        }
    }

    /**
     * 批量下架产品
     * @param Request $request
     */
    public function productDown(Request $request)
    {
        if(!empty($request->ids)){
            $ids = explode(',',rtrim($request->ids,','));
            DB::table('tbuy_product')->whereIn('product_id',$ids)->update(['up_status'=>0]);
        }




    }
}
