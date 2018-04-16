<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Draw extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['status','require|number|between:-1,1','状态错误|状态错误|状态错误'],
        ['title','require','请输入绘图名称'],
        ['thumb','require','绘图地址异常'],
    ];
    protected $scene=[
        'id'=>['id'],
        'status'=>['id','status'],
        'add'=>['title'],
    ];
}