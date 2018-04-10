<?php

/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 15:51
 */
namespace app\admin\controller;
use think\Controller;

class User extends Base
{
    public function index(){
        $data['status']=array('neq',-1);
        if(input('type')&&input('content')) {
            $data[input('type')]=array('like','%'.input('content').'%');
        }
        $this->assign('type',input('type'));
        $this->assign('content',input('content'));

        $users=model('user')->getDatas(config('setting.page_count'),$data);
        $this->assign('users',$users);
        $this->assign('nav','user');
        return $this->fetch();
    }
    public function add(){
        $user='';
        if(!is_array(validate('User')->goCheck('id'))){
            $user=model('User')->find(input('id'));
        }
        $this->assign('user',$user);
        $this->assign('nav','adduser');
        return $this->fetch();
    }
    public function save(){
        if(input('password')==''){
            return json(['status'=>0,'message'=>'请输入密码']);
        }
        if(input('password1')==''||input('password')!=input('password1')){
            return json(['status'=>0,'message'=>'两次密码不一致']);
        }
        $d=request()->param();
        unset($d['password1']);
        $d['create_time']=time();
        $d['password']=md5(config('setting.md5_pre').$d['password']);
        if(input('id')){
            $data=validate('User')->goCheck('edit');
            if(!is_array($data)){
                $user=model('User')->isUpdate(true)->save($d);
                if($user){
                    return json(['status'=>1,'message'=>'修改成功']);
                }else{
                    return json(['status'=>0,'message'=>'修改失败']);
                }
            }else{
                return json($data);
            }
        }else{
            $data=validate('User')->goCheck('add');
            if(!is_array($data)){
                $user=model('User')->create($d);
                if($user){
                    return json(['status'=>1,'message'=>'添加成功']);
                }else{
                    return json(['status'=>0,'message'=>'添加失败']);
                }
            }else{
                return json($data);
            }
            return json($data);
        }
    }
    public function status(){
        $data=validate('User')->goCheck('status');
        if(!is_array($data)){
            $user=model('User')->isUpdate(true)->save(request()->param());
            if($user){
                return json(['status'=>1,'message'=>'操作成功']);
            }else{
                return json(['status'=>0,'message'=>'操作失败']);
            }
        }else{
            return json($data);
        }
    }
}