<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class User extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['name','require|max:30','昵称不能为空|昵称过长'],
        ['username','require|max:30|ifLoginUser','请填写账号|账号不超过30个字符|账号已注册'],
        ['status','require|number|between:-1,1','状态不合法|状态不合法|状态不合法']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['name','username'],
        'edit'=>['id','name'],
        'status'=>['id','status']
    ];
}