<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Place extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['name','require|max:30','场景名称不能为空|场景名称过长'],
        ['status','require|number|between:-1,1','状态不合法']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['name'],
        'edit'=>['id','name'],
        'status'=>['id','status']
    ];
}