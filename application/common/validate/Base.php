<?php
    /**
     * Created by PhpStorm.
     * User: 王振远
     * Date: 2017/5/13
     * Time: 18:47
     */

namespace app\common\validate;

use think\Validate;

class Base extends Validate{
    public function goCheck($scene){
        //获取http传入的参数，对这些参数做校验
        //$request=Request::instance();
        $params=request()->param();
        $result=$this->scene($scene)->check($params);
        if(!$result){
            $e =[
                'status'=>0,
                'message'=>$this->error,
            ];
            return $e;
        }else{
            return true;
        }
    }
    protected function isPositiveInteger($value,$rule='',$data='',$field=''){
        if(is_numeric($value)&&is_int($value+0)&&($value+0)>0){
            return true;
        }
        return false;
        //return $field.'必须是正整数';
    }
    protected function isNotEmpty($value,$rule='',$data='',$field=''){
        if(empty($value)){
            return false;
        }else{
            return true;
        }
    }
    public function ifLoginUser($value,$rule='',$data='',$field=''){
        $d['username']=$value;
        $user=model('User')->where($d)->select();
        if(!$user->isEmpty()){
            return false;
        }else{
            return true;
        }
    }
    public function ifLoginAdmin($value,$rule='',$data='',$field=''){
        $d['username']=$value;
        $admin=model('Admin')->all($d);
        if(!$admin->isEmpty()){
            return false;
        }else{
            return true;
        }
    }
}