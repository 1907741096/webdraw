<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 16:02
 */

namespace app\common\model;

class Draw extends Base
{
    protected $name='draw';
    public function user(){
        return $this->belongsTo('user','user_id','id');
    }
    public function getAllDraws($count,$data){
        return $this->with('user')->where($data)->paginate($count);
    }
//    public function getThumbAttr($value,$data){
//        return config('setting.img_prefix').$value;
//    }
}