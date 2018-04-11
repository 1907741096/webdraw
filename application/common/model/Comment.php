<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 16:02
 */

namespace app\common\model;

class Comment extends Base
{
    protected $name='comment';
    public function user(){
        return $this->belongsTo('user','user_id','id');
    }
    public function getAllComments($count,$data){
        return $this->with('user')->where($data)->order('id desc')->paginate($count);
    }
}