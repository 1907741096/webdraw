<?php

/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 15:51
 */
namespace app\admin\controller;
use think\Controller;

class Admin extends Base
{
    public function __construct(){
        parent::__construct();
        if(session('admin')['power']!=1){
            echo "<script>alert('当前管理员没有权限');</script>";
            jump($_SERVER['HTTP_REFERER']);
        }
    }
    public function index(){
        $data['status']=array('neq',-1);
        $admins=model('admin')->getDatas(config('setting.page_count'),$data);
        $this->assign('admins',$admins);
        $this->assign('nav','admin');
        return $this->fetch();
    }
    public function add(){
        $admin='';
        if(!is_array(validate('Admin')->goCheck('id'))){
            $admin=model('Admin')->find(input('id'));
        }
        $this->assign('admin',$admin);
        $this->assign('nav','addadmin');
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
            $data=validate('Admin')->goCheck('edit');
            if(!is_array($data)){
                $admin=model('Admin')->isUpdate(true)->save($d);
                if($admin){
                    return json(['status'=>1,'message'=>'修改成功']);
                }else{
                    return json(['status'=>0,'message'=>'修改失败']);
                }
            }else{
                return json($data);
            }
        }else{
            $data=validate('Admin')->goCheck('add');
            if(!is_array($data)){
                $admin=model('Admin')->create($d);
                if($admin){
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
        if(session('admin')['id']!=1){
            return json(['status'=>0,'message'=>'当前管理员没有权限']);
        }
        $data=validate('Admin')->goCheck('status');
        if(!is_array($data)){
            $admin=model('Admin')->isUpdate(true)->save(request()->param());
            if($admin){
                return json(['status'=>1,'message'=>'操作成功']);
            }else{
                return json(['status'=>0,'message'=>'操作失败']);
            }
        }else{
            return json($data);
        }
    }
}