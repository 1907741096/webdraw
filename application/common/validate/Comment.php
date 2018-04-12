<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Comment extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['news_id','require|isPositiveInteger','请选择文章|文章选择错误'],
        ['content','require','请填写评论内容'],
        ['status','require|number|between:-1,1','状态错误|状态错误|状态错误']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['news_id','content'],
        'edit'=>['id','news_id','content'],
        'status'=>['id','status']
    ];
}