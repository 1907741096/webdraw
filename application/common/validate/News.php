<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/15
 * Time: 16:53
 */

namespace app\common\validate;


class News extends Base
{
    protected $rule=[
        'id'=>'require|isPositiveInteger',
        ['user_id','require|isPositiveInteger','请选择用户|用户选择错误'],
        ['title','require|max:45','文章标题不能为空|文章标题过长'],
        ['tags','require|isPositiveInteger','请选择标签|标签选择错误'],
        ['thumb','require','请上传封面图'],
        ['content','require','请填写文章内容'],
        ['status','require|number|between:-1,1','状态错误|状态错误|状态错误']
    ];
    protected $scene=[
        'id'=>['id'],
        'add'=>['user_id','tags','title','thumb','content'],
        'user-add'=>['title','tags','thumb','content'],
        'edit'=>['id','user_id','title','thumb','content'],
        'status'=>['id','status']
    ];
}