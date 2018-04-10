<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 18:24
 */

namespace app\index\controller;


use think\Controller;

class Login extends Controller
{
    public function index(){
        if(session('user')&&session('user')!=null){
            jump('/erciyuan/public/Index.php/Index');
        }else{
            $style_id=config('setting.default_style');
            if(session('style_id')&&session('style_id')!=null){
                $style_id=session('style_id');
            }
            $style=model('Style')->find($style_id);
            $this->assign('styleAddress',$style['address']);
            return $this->fetch();
        }
    }
    public function checklogin(){
        $msg=$this->check(input());
        if(is_array($msg)){
            return json($msg);
        }else{
            return json(['status'=>1,'message'=>'登录成功']);
        }
    }
    public function check($data){
        if(!$data['username']||trim($data['username'])==''){
            return ['status'=>0,'message'=>'请输入账号'];
        }
        if(!$data['password']||trim($data['password'])==''){
            return ['status'=>0,'message'=>'请输入密码'];
        }
        $user=model('User')->get(['username'=>$data['username']]);
        if(!$user){
            return ['status'=>0,'message'=>'账号不存在'];
        }
        if($user['status']!=1){
            return ['status'=>0,'message'=>'账号已被冻结，请联系总管理员'];
        }
        if($user['password']!=md5(config('setting.md5_pre').$data['password'])){
            return ['status'=>0,'message'=>'密码错误'];
        }
        session('user', $user);
        $a['lastlogin_time']=time();
        $u['id']=$user['id'];
        model("User")->isUpdate(true)->save($u);
        return true;
    }
    public function loginout(){
        session('user', null);
        if(session('user')==null){
            return json(['status'=>1,'message'=>'退出成功','jump_url'=>$_SERVER['HTTP_REFERER']]);
        }else {
            return json(['status' => 1, 'message' => '退出失败']);
        }
    }
}