<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use App\Models\TbuyProperty;
use App\Models\TbuyPropertyValue;
use DB;
use Illuminate\Http\Request;

class AddController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $supplier_list=DB::table('finance_supplier')->get();
        $class = $this->get_class(1);
        $class_sub = [];
        $class_id_f = 0;
        return view('product.add.index',['supplier_list'=>$supplier_list,'class'=> $class,'class_sub'=> $class_sub,'class_id_f'=>$class_id_f]);
    }
    /*
	 * 多级分类选择
	 */
    public function get_class($father_id){
        $where=array(
            'class_type'=>0,
            'father_id'=>$father_id,
        );
        $data=DB::table('tbuy_class')->where($where)->select('class_id','class_name')->get();
        return $data;
    }

    /*
	 * 多级分类选择
	 */
    public function product_class(Request $request){
        $parents_id=$request->input('parents_id');
        $where=array(
            'status'=>1,
            'class_type'=>0,
            'father_id'=>$parents_id?$parents_id:1
        );
        $data=DB::table('tbuy_class')->where($where)->select('class_id','class_name')->get();
        return $this->ajaxSuccess('成功','',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function step1(Request $request){
        $_SESSION['product'] = $request->all();
        return redirect('product/add/step2');
    }

    public function step2(Request $request){

        if(!empty($request->all())){
            $_SESSION['product'] = $request->all();
            return redirect('product/add/step3');
        }

        $class_id = $_SESSION['product']['select_class'];

        $prop_lists = DB::table('tbuy_property')->where("class_id", $class_id)->where('status',1)->get();
        foreach($prop_lists as &$value){
            $value->property_value = DB::table('tbuy_property_value')->where('property_id',$value->property_id)->where('status',1)->get(['value_id','value_text']);
        }

        return view('product.add.step2',['prop_lists'=>$prop_lists,'product'=>$_SESSION['product']]);
    }

    public function property($class_id)
    {

        $list = DB::table('tbuy_property')->where("class_id", $class_id)->orWhere(['property_id' => 1])->orderBy('property_id', 'asc')->get();

        return [
            'list' => $list,
            'class_id' => $class_id
        ];
    }

    /**
     * 添加商品属性
     * @param Request $request
     * @return mixed
     */
    public function addProp(Request $request){

        $name = trim($request->input('name'));
        $class_id = (int)$request->input('class_id');

        if(!$name) return $this->ajaxError('请填写属性');
        if(!$class_id) return $this->ajaxError('未选择分类');
        if(DB::table('tbuy_property')->where(array('property_name' => $name, 'class_id' => $class_id))->first())
            return $this->ajaxError('属性名称已经存在');

        $id = TbuyProperty::getNextSeq();

        $data = array(
            'property_id'	=> $id,
            'property_name'	=> $name,
            'class_id'		=> $class_id,
            'status'		=> 1,
            'char_code'		=> zh2pinyin($name),
        );

        if(!TbuyProperty::insert($data))
            return $this->ajaxError('操作失败');

        $data = $id;
        return $this->ajaxSuccess('已添加','',$data);
    }

    public function addPropValue(Request $request)
    {
        $name = trim($request->input('name'));
        $property_id = (int)$request->input('property_id');

        if(!$name) return $this->ajaxError('请填写属性值');
        if(!$property_id) return $this->ajaxError('未选择属性');


        if(TbuyPropertyValue::where(array('value_text' => $name, 'property_id' => $property_id))->first())
            $this->ajaxError('规格名称已经存在');

        $id = TbuyPropertyValue::getNextSeq();
        $data = array(
            'value_id'		=> $id,
            'value_text'	=> $name,
            'property_id'	=> $property_id,
            'status'		=> 1,
        );

        if(!TbuyPropertyValue::insert($data)){
            return $this->ajaxError('操作失败');
        }

        $data['value_id'] = $id;
        return $this->ajaxSuccess('已添加','',$data);
    }

    public function step3(Request $request){
        $_SESSION['product'] = $request->all();
        return redirect('add/step4');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $product_name = $request->input('product_name');

        //防止多次点击
        // if(S('Admin_product_add_option_ajax'.$product_name)){
        // 	$this->json_error('您提交太频繁，请稍微休息下！');
        // }
        // S('Admin_product_add_option_ajax'.$product_name,$product_name,3);

        if(!$product_name){
            $this->ajax_error('产品名称不能为空');
        }

        $property_remarks = $request->input('property_remarks');
        $property_remarks2 = $request->input('property_remarks2');

        if(empty($property_remarks)){
            $this->ajax_error('请添加商品属性');
        }


        $pay_type_id = $request->input('pay_type_id');

        if(!$pay_type_id){
            $this->ajax_error('请选择支付方式');
        }

        $price 			= $request->input('price');//出售价
        $market_price 	= $request->input('market_price');//市场价
        $cost_price 	= $request->input('cost_price');//成本价
        $stock 			= $request->input('stock');//库存
        $hl_integral 	= $request->input('hl_integral');//储值
        $pv 			= $request->input('pv');//PV
        $ga_integral 	= $request->input('ga_integral');//GA
        $gastore_integral 	= $request->input('gastore_integral');//储值GA
        $offline_shop_percent 	= $request->input('offline_shop_percent');//消费营行百分比


        $details = $request->input('content');//图文详情
        $params = $request->all();

        $row = [];
        $arr = array();
        foreach($property_remarks as $k=>$v){
            $row['remarks'] 		= $v;
            $row['remarks2'] 		= $property_remarks2[$k];
            $row['price'] 			= $price[$k];
            $row['market_price'] 	= $market_price[$k];
            $row['cost_price'] 		= $cost_price[$k];
            $row['stock'] 			= $stock[$k];
            $row['hl_integral'] 	= $hl_integral[$k];
            $row['pv'] 				= $pv[$k];
            $row['ga_integral'] 	= $ga_integral[$k];
            $row['gastore_integral'] 	= $gastore_integral[$k];
            $row['offline_shop_percent'] 	= $offline_shop_percent[$k];

            $arr[] 					= $row;
        }

        DB::beginTransaction();
        try{

            $status = array();
            $product_id = Product::getNextSeq();
            $data = array(
                'product_id' => $product_id,
                'status' => $params['status'],  //禁用=0,启用=1,删除=2
                'up_status' => $params['up_status'], //未上架=0,已上架=1,超级店长商品=2,套餐商品=3
                'class_id' => $params['class_id']??0, //商品分类ID
                'remarks' => $params['remarks'], //商品备注
                'details' => htmlspecialchars_decode($details), //商品详情
                'store_id' => 1, //所属小店
                'product_name' => $params['product_name'], //商品名称
                'weight' => 0, //抵扣比例
                'is_virtual' => 0, //是否实物 0-实物  1-虚拟
                'unit_id' => 1,//单位1件
                'supplier_id' => isset($params['supplier_id']) ? (int)$params['supplier_id'] : 0, //供应商id
            );
            $status[] = Product::insert($data);

            //添加商品属性值
            foreach($arr as &$val){
                $sku_id = TbuySku::getNextSeq();
                $sku_data = array(
                    'sku_id'		=> $sku_id,
                    'product_id' 	=> $product_id,
                    'stock'			=> $val['stock'],
                    'price'			=> $val['price'],
                    'market_price'	=> $val['market_price'],
                    'remarks'		=> $val['remarks'],
                    'cost_price'	=> $val['cost_price'],
                    'sku_no' 		=> '7000'.$product_id,
                    'introducer_pv_percent' => 0,
                    'hl_integral' 	=> $val['hl_integral'],
                    'pv' 			=> $val['pv'],
                    // 'pv_percent_19' => 100,
                    'ga_integral' 	=> $val['ga_integral'],
                    'gastore_integral' 	=> $val['gastore_integral'],
                    'offline_shop_percent' 	=> $val['offline_shop_percent'],

                );
                $status[] = DB::table('tbuy_sku')->insert($sku_data);
                $val['sku_id'] = $sku_id;
            }

            //tbuy_product_class 商品分类
            if(isset($params['class_id'])){
                $con = [
                    'id' => TbuyProductClass::getNextSeq(),
                    'product_id' => $product_id,
                    'class_id' => $params['class_id']??1000017,
                ];
                $status[] = TbuyProductClass::insert($con);
            }

            // tbuy_product_property_value
            foreach ($arr as $k => $v) {
                foreach (explode(',', $v['remarks2']) as $kk => $vv) {
                    $con = [
                        'id' => TbuyProductPropertyValue::getNextSeq('seq_tbuy_product_prop_value'),
                        'product_id' => $product_id,
                        'property_value_id' => explode('_', $vv)[1],
                        'sku' => $v['sku_id'],
                        'status' => 1,
                        'remarks' => explode('_', $vv)[0],
                    ];
                    $status[] = TbuyProductPropertyValue::insert($con);
                }
            }

            // 插入支付方式
            $con = [
                'id' => TbuyProductPayType::getNextSeq(),
                'product_id' => $product_id,
                'pay_type_id' => $pay_type_id,
                'remarks' => '',
            ];
            $status[] = TbuyProductPayType::insert($con);

            $pic  = $request->input('pic');
            if(empty($pic)){
                $this->ajax_error('请上传商品主图');
            }
            //增加Img
            foreach ($pic as $key=>$val){
                if(empty($val)) continue;
                $insert_img=array(
                    'id'=>VbuyProductImgModel::getNextSeq(),
                    'product_id'=>$product_id,
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
                $this->ajax_success('商品添加成功',$product_id);
            }else{
                throw new \Exception("添加失败");

            }
        }catch (\Exception $e){
            DB::rollback();
            $this->ajax_error($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
