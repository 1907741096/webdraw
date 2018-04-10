<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class Style extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['name','require|max:30','主题名称不能为空|主题名称过长'],
        ['color','require|max:30','主题色不能为空|主题色过长'],
        ['address','require','请上传主题文件'],
        ['status','require|number|between:-1,1','状态不合法']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['name','color','address'],
        'edit'=>['id','name','color','address'],
        'status'=>['id','status']
    ];
}