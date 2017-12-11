<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20 0020
 * Time: 15:28
 */

namespace app\Http\Controllers\Article;

use App\Http\Controllers\BaseController;
use App\Models\Article;

class ArticleController extends BaseController
{
    const PREFIX = 'article.';

    /**
     * 文章列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function articleLists()
    {
        $model=new Article();
        $data=$model->getLists(request()->input());
        unset($_REQUEST['article_id']);
        return view(self::PREFIX . 'article_lists',['data'=>$data]);
    }

    /**
     *  新增或编辑文章
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editArticle()
    {
        $model=new Article();
        if(request()->isXmlHttpRequest()){
            if(empty(request()->input('article_id'))){
                //新增
                $res=$model->addOne(request()->input());
            }else{
                //编辑
                $res=$model->updateOne(request()->input());
            }
            if($res){
                return response()->json([
                    'status'=>1,
                    'msg'=>'保存成功',
                    'data'=>''
                ]);
            }else{
                return response()->json([
                    'status'=>0,
                    'msg'=>'保存失败',
                    'data'=>''
                ]);
            }
        }
        $data=[];
        if(request()->has('article_id') and !empty(request()->get('article_id'))){
            //查询文章详情
            $data=$model->getOne(request()->get('article_id'));
        }
        return view(self::PREFIX . 'edit_article',['data'=>$data]);
    }

}