<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Models\TbuyPayType;
use App\Models\TbuyPayTypeGroup;
use App\Models\TbuyProductClass;
use App\Models\TbuyProductPayType;
use App\Models\TbuyProductPropertyValue;
use App\Models\TbuyProperty;
use App\Models\TbuyPropertyValue;
use App\Models\TbuySku;
use App\Models\VbuyProductImgModel;
use DB;
use Illuminate\Http\Request;

class AddController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        if(!empty($request->remarks)){

            $remarks = $request->remarks;
            $data = [];
            foreach($remarks as $key => $value){
                $data[] = [
                    'remarks' => $remarks[$key],
                    'sale_price' => empty($request->sale_price[$key]) ? 0 : $request->sale_price[$key],
                    'cost_price' => empty($request->cost_price[$key]) ? 0 : $request->cost_price[$key],
                    'market_price' => empty($request->market_price[$key]) ? 0 : $request->market_price[$key],
                    'stock' => empty($request->stock[$key]) ? 0 : $request->stock[$key],
                    'hl_integral' => empty($request->hl_integral[$key]) ? 0 : $request->hl_integral[$key],
                    'pv' => empty($request->pv[$key]) ? 0 : $request->pv[$key],
                    'ga_integral' => empty($request->ga_integral[$key]) ? 0 : $request->ga_integral[$key],
                    'haolian' => empty($request->haolian[$key]) ? 0 : $request->haolian[$key],
                ];
            }

            $_SESSION['product']['sku'] = $data;
            return redirect('product/add/step3');
        }

        if(!isset($_SESSION['product']['select_class'])){
            return redirect('product/add');
        }

        $class_id = $_SESSION['product']['select_class'];

        $prop_lists = DB::table('tbuy_property')->where("class_id", $class_id)->orWhere('property_id',1)->where('status',1)->get();
        foreach($prop_lists as &$value){
            $value->property_value = DB::table('tbuy_property_value')->where('property_id',$value->property_id)->where('status',1)->get(['value_id','value_text']);
        }

        return view('product.add.step2',['prop_lists'=>$prop_lists,'product'=>$_SESSION['product']]);
    }

    public function step3(Request $request){
        if(!empty($request->all())){
            $data = $request->all();
            unset($data['_token']);
            $_SESSION['product'] = array_merge($_SESSION['product'],$data);
            return redirect('product/add/step4');
        }
        return view('product.add.step3');
    }

    public function step4(Request $request){
        if(!empty($request->all())){
            $data = $request->all();
            unset($data['_token']);
            $_SESSION['product'] = array_merge($_SESSION['product'],$data);
            return redirect('product/add/store');
        }

        $pay_type_group = TbuyPayTypeGroup::where(['status' => 1])->orderBy('id', 'asc')->get();
        return view('product.add.step4', ['pay_type_group'=>$pay_type_group]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        return redirect()->route('product/add/save')->with('success','新增商品成功！');

        $params = $_SESSION['product'];
        $sku_lists = $params['sku'];


        DB::beginTransaction();
        try{

            $status = array();
            $product_id = Product::getNextSeq();
            $data = array(
                'product_id' => $product_id,
                'status' => 3,  //禁用=0,启用=1,删除=2,审核中=3
                'up_status' => 0, //未上架=0,已上架=1,超级店长商品=2,套餐商品=3
                'class_id' => $params['select_class']?:0, //商品分类ID
                'remarks' => $params['remarks'], //商品备注
                'details' => empty($params['content']) ? '' : htmlspecialchars_decode($params['content']), //商品详情
                'store_id' => 1, //所属小店
                'product_name' => $params['product_name'], //商品名称
                'weight' => 0, //抵扣比例
                'is_virtual' => 0, //是否实物 0-实物  1-虚拟
                'unit_id' => 1,//单位1件
                'supplier_id' => isset($params['supplier_id']) ? (int)$params['supplier_id'] : 0, //供应商id
            );
            $status[] = Product::insert($data);

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
                    'product_id' 	=> $product_id,
                    'stock'			=> $val['stock'],
                    'price'			=> $val['sale_price'],
                    'market_price'	=> $val['market_price'],
                    'remarks'		=> $sku_ramark,
                    'cost_price'	=> $val['cost_price'],
                    'sku_no' 		=> '7000'.$product_id,
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
            if(isset($params['class_id'])){
                $con = [
                    'id' => TbuyProductClass::getNextSeq(),
                    'product_id' => $product_id,
                    'class_id' => $params['class_id']?:0,
                ];
                $status[] = TbuyProductClass::insert($con);
            }

            // tbuy_product_property_value
            foreach ($sku_lists as $k => $v) {
                $prop = explode(',', $v['remarks']);
                foreach ($prop as $kk => $vv) {
                    $prop_remarks = strstr($vv, '@',true);
                    $property_value_id = ltrim(strstr($vv, '@'),'@');
                    $con = [
                        'id' => TbuyProductPropertyValue::getNextSeq('seq_tbuy_product_prop_value'),
                        'product_id' => $product_id,
                        'property_value_id' => $property_value_id,
                        'sku' => $v['sku_id'],
                        'status' => 1,
                        'remarks' => $prop_remarks,
                    ];
                    $status[] = TbuyProductPropertyValue::insert($con);
                }
            }

            // 插入支付方式
            $con = [
                'id' => TbuyProductPayType::getNextSeq(),
                'product_id' => $product_id,
                'pay_type_id' => $params['pay_type_id'],
                'remarks' => '',
            ];
            $status[] = TbuyProductPayType::insert($con);

            //增加Img
            foreach ($params['pic'] as $key=>$val){
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
                unset($_SESSION['product']);
                return redirect()->route('product/add/save')->with('success','新增商品成功！');
            }else{
                throw new \Exception("添加失败");

            }
        }catch (\Exception $e){
            DB::rollback();
            p($e->getMessage());
//            $this->ajaxError($e->getMessage());
        }
    }

    public function save(){
        return view('product.add.save');
    }

    /**
     * 支付方式
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function paytype(Request $request){
        if($request->isMethod('post'))
        {
            $data = $request->all();
            $paytype_name 	= $request->input('paytype_name');
            $percent		= $request->input('percent');
            $fact 			= $request->input('fact');

            if(!$paytype_name || empty($percent) || empty($fact)){
                return $this->ajaxError('缺少必填参数');
            }

            $add_data = array(
                'GROUP_NAME'	=> $paytype_name,
                'STATUS'		=> 1,
                'CREATE_TIME'	=> date('Y-m-d H:i:s'),

            );
            $pay_type = array();
            $pay_type_amount = array();
            foreach ($percent as $key => $val) {
                if($val){
                    $pay_type[$key] = floatval($val);
                }
                if($fact[$key]){
                    $pay_type_amount[$key] = floatval($fact[$key]);
                }
            }

            if(empty($pay_type) && empty($pay_type_amount)){
                return $this->ajaxError('请配置金额');
            }

            $add_data['PAY_TYPE'] = $pay_type?json_encode($pay_type):'';
            $add_data['PAY_TYPE_AMOUNT'] = $pay_type_amount?json_encode($pay_type_amount):'';

            $add_data['id'] = TbuyPayTypeGroup::getNextSeq();
            $res = TbuyPayTypeGroup::insert($add_data);
            if(!$res){
                return $this->ajaxError('数据添加失败,请重试');
            }
            return $this->ajaxSuccess('添加成功');

        }else
        {
            $paytype = TbuyPayType::where(['status' => 1])->orderBy('id', 'asc')->get();
            return view('product.add.paytype', ['paytype' => $paytype]);
        }
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

    /**
     * 添加属性值
     * @param Request $request
     * @return mixed
     */
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
