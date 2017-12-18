<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/20 0020
 * Time: 15:58
 */

namespace App\Models;


class Article extends Base
{

    protected $table = 'tpc_article';//文章表

    /**
     *  获得文章列表
     *
     * @param array $search
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLists(array $search = [])
    {
        $query = $this->orderBy('istop', 'desc')
            ->orderBy('create_time', 'desc')
            ->select('id', 'sortrank', 'title', 'tviews', 'create_time', 'status', 'istop');
        if (array_key_exists('keyword', $search) and !empty($search['keyword'])) {
            $query->where('title', 'like', '%' . $search['keyword'] . '%');
        }
        return $query->paginate(15);
    }

    /**
     * 获取一篇文章详情
     *
     * @param array $get
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getOne($get)
    {
        return $this->where([
            'id' => $get
        ])->first();
    }

    /**
     * 更新一篇文章详情
     *
     * @param array $input
     * @return bool
     */
    public function updateOne(array $input)
    {
        if (!array_key_exists('article_id', $input)) {
            return false;
        }
        $where = [
            'id' => $input['article_id']
        ];
        unset($input['S']);
        unset($input['article_id']);
        unset($input['id']);
        unset($input['_token']);
        $input['update_time']=date('Y-m-d H:i:s');
        return $this->where($where)->update($input);

    }

    /**
     * 新增一篇文章详情
     *
     * @param array $input
     * @return bool
     */
    public function addOne(array $input)
    {
        unset($input['S']);
        unset($input['article_id']);
        unset($input['_token']);
        $input['typeid'] = 0;//类型暂时屏蔽
        $input['id'] = self::getNextSeq();
        return $this->insert($input);
    }

}