<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Content extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['status','require|number|between:-1,1','状态错误|状态错误|状态错误'],
        ['position_id','require|isPositiveInteger','请选择推荐位|请选择推荐位'],
        ['push','require|isNotEmpty','请选择推荐文章|请选择推荐文章'],
        ['listorder','require|isNotEmpty','排序错误|排序错误']
    ];
    protected $scene=[
        'id'=>['id'],
        'status'=>['id','status'],
        'add'=>['position_id','push'],
        'listorder'=>['listorder']
    ];
}