<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 16:03
 */

namespace app\common\model;


use think\Model;

class Base extends Model
{
    public function getDatas($page,$data){
        return $this->where($data)->paginate($page);
    }
}