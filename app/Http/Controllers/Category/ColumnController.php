<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/17 0017
 * Time: 11:43
 */
namespace App\Http\Controllers\Category;

use App\Http\Controllers\BaseController;
use App\Models\TbuyClass;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ColumnController extends BaseController
{

    private $cateStatus = ['禁用','启用','删除'];
    private $class_type = ['网页链接','商品分类','商品栏目','广告栏目'];

    public function index(Request $request){

        $query = DB::table('TBUY_CLASS');

        if(isset($request->search)){
            if($request->status != -1){
                $query->where('status',$request->status);
            }
        }

        $class_lists = $query->orderBy('sort_number')->get()->toArray();

        $class_lists = genTree($class_lists,'class_id','father_id');

        return view('category/column_index', ['tree'=>$class_lists,'cateStatus'=>$this->cateStatus]);
    }

    public function tree($tree = null){
        return view('category/tree',['tree'=>$tree]);
    }

    public function create($pid){
        $parent_cate = DB::table('TBUY_CLASS')->where('class_id',$pid)->first();
        return view('category/column_create',['parent_cate'=>$parent_cate,'class_type'=>$this->class_type,'cateStatus'=>$this->cateStatus]);
    }

    public function store(Request $request){
        $class_id = TbuyClass::getNextSeq();

        $path = 'category';
        $name = $class_id.'.jpg';
        if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
            $photo = $request->file('image_url');
            $extension = $photo->extension();
            $store_result = $photo->storeAs($path, $name);
        }

        $data = [
            'class_id'=>$class_id,
            'father_id'=>$request->pid,
            'class_type'=>$request->class_type,
            'status'=>$request->status,
            'sort_number'=>empty($request->sort_number) ? 0 : $request->sort_number,
            'class_name'=>$request->class_name,
            'web_url'=>$request->web_url,
            'image_url'=>$path.'/'.$name,
            'remarks'=>$request->remarks,
            'char_code'=>zh2pinyin($request->class_name),
            'create_time'=>date('Y-m-d H:i:s')
        ];
        $rs = TbuyClass::insert($data);
        if($rs){
            return redirect()->route('column/index')->with('success','新增分类成功！');
        }else{
            return redirect()->route('column/create',['pid'=>$request->pid])->with('error','新增分类失败！');
        }
    }

    /* 编辑分类 */
    public function edit(Request $request,$id = ''){
        if($request->isMethod('post')){ //提交表单
            if(!empty($request->class_id)){
                $data = ['sort_number'=>$request->sort_number, 'class_name'=>$request->class_name];
                $rs = DB::table('TBUY_CLASS')->where('class_id',$request->class_id)->update($data);
                if($rs){
                    return $this->ajaxSuccess('编辑成功!');
                }else{
                    return $this->ajaxError('编辑失败!');
                }
            }
        } else {
            $class_info = DB::table('TBUY_CLASS')->where('class_id',$id)->first();
            $parent_cate = DB::table('TBUY_CLASS')->where('class_id',$class_info->father_id)->first();
            return view('category/column_edit', ['class_info'=>$class_info,'class_type'=>$this->class_type,'parent_cate'=>$parent_cate,'cateStatus'=>$this->cateStatus]);
        }
    }

    public function update(Request $request){
        $class_id = $request->class_id;
        $path = 'category';
        $name = $class_id.'.jpg';
        if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
            $photo = $request->file('image_url');
            $extension = $photo->extension();
//            $store_result = $photo->store('photo');
            $store_result = $photo->storeAs($path, $name);
        }
        $data = [
//            'father_id'=>$request->father_id,
            'class_type'=>$request->class_type,
            'status'=>$request->status,
            'sort_number'=>empty($request->sort_number) ? 0 : $request->sort_number,
            'class_name'=>$request->class_name,
            'web_url'=>$request->web_url,
            'image_url'=>$path.'/'.$name,
            'remarks'=>$request->remarks,
            'char_code'=>'ABC',
            'create_time'=>date('Y-m-d H:i:s')
        ];

        $rs = TbuyClass::where('class_id',$class_id)->update($data);
        if($rs){
            return redirect()->route('column/index')->with('success','编辑分类成功！');
        }else{
            return redirect()->route('column/update')->with('error','编辑分类失败！');
        }
    }

    public function show($class_id){
        $class_info = DB::table('TBUY_CLASS')->where('class_id',$class_id)->first();
        $parent_cate = DB::table('TBUY_CLASS')->where('class_id',$class_info->father_id)->first();
        return view('category/column_show', ['class_info'=>$class_info,'class_type'=>$this->class_type,'parent_cate'=>$parent_cate,'cateStatus'=>$this->cateStatus]);
    }



}




















