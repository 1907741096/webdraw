<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/20
 * Time: 20:23
 */

namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
    public function index(){
        if(session('admin')&&session('admin')!=null){
            jump('/webdraw/public/index.php/index');
        }
        return $this->fetch();
    }
    public function checklogin(){
        $msg=$this->check(input());
        if(is_array($msg)){
            return json($msg);
        }else{
            return json(['status'=>1,'message'=>'登录成功']);
        }
    }
    public function loginout(){
        session('admin', null);
        jump('/webdraw/public/index.php/admin/login');
    }
    public function check($data){
        if(!$data['username']||trim($data['username'])==''){
            return ['status'=>0,'message'=>'请输入账号'];
        }
        if(!$data['password']||trim($data['password'])==''){
            return ['status'=>0,'message'=>'请输入密码'];
        }
        $admin=model('Admin')->get(['username'=>$data['username']]);
        if(!$admin){
            return ['status'=>0,'message'=>'账号不存在'];
        }
        if($admin['status']!=1){
            return ['status'=>0,'message'=>'账号已被冻结，请联系总管理员'];
        }
        if($admin['password']!=md5(config('setting.md5_pre').$data['password'])){
            return ['status'=>0,'message'=>'密码错误'];
        }
        session('admin', $admin);
        $a['lastlogin_time']=time();
        $a['id']=$admin['id'];
        model("Admin")->isUpdate(true)->save($a);
        return true;
    }
}