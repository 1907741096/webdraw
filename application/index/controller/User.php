<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 17:04
 */

namespace app\index\controller;


class User extends Common
{

    public function index($type='news'){
        $data['status']=self::$status;
        $data['user_id']=session('user')['id'];
        if(input('title')){
            $data['title']=array('like','%'.input('title').'%');
        }
        $this->assign('title',input('title'));
        $user=model('user')->find($data['user_id']);
        $this->assign('user',$user);
        if($type=='news'){
            $datas=model('news')->where($data)->select();
            $count=model('news')->where($data)->count();
        }else{
            $datas=model('draw')->where($data)->select();
            $count=model('draw')->where($data)->count();
        }
        $this->assign('type',$type);
        $this->assign('datas',$datas);
        $this->assign('count',$count);
        $this->assign('menu','user');
        return $this->fetch();
    }
    public function save(){
        if(!session('user')||session('user')==null){
            return json(['status'=>0,'message'=>'请先登录']);
        }
        $data=validate('User')->goCheck('user-edit');
        if(!is_array($data)){
            $d=request()->param();
            $d['id']=session('user')['id'];
            $id=model('User')->isUpdate(true)->save($d);
            if($id){
                return json(['status'=>0,'message'=>'保存成功']);
            }else{
                return json(['status'=>0,'message'=>'保存失败']);
            }
        }else{
            return json($data);
        }
    }
}