<?php

/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/14
 * Time: 15:51
 */
namespace app\admin\controller;
use think\Controller;

class News extends Base
{
    public function index(){
        $data['status']=array('neq',-1);
        $users=model('user')->all();
        $this->assign('users',$users);
        $user_id='';
        if(input('user_id')) {
            $data['user_id']=input('user_id');
            $user_id=$data['user_id'];
        }
        $news=model('news')->getAllNews(config('setting.page_count'),$data);

        $status['status']=array('eq',1);
        $this->assign('user_id',$user_id);
        $this->assign('news',$news);
        $this->assign('nav','news');
        return $this->fetch();
    }
    public function add(){
        $status['status']=array('eq',1);
        $users=model('user')->all();
        $this->assign('users',$users);
        $news='';$user_id='';
        if(!is_array(validate('News')->goCheck('id'))){
            $news=model('News')->find(input('id'));
            $user_id=$news['user_id'];
        }
        $this->assign('user_id',$user_id);
        $this->assign('news',$news);
        $this->assign('nav','addnews');
        return $this->fetch();
    }
    public function save(){
        if(input('id')){
            $data=validate('News')->goCheck('edit');
            if(!is_array($data)){
                $d=request()->param();
                $d['update_time']=time();
                $news=model('News')->isUpdate(true)->save($d);
                if($news){
                    return json(['status'=>1,'message'=>'修改成功']);
                }else{
                    return json(['status'=>0,'message'=>'修改失败']);
                }
            }else{
                return json($data);
            }
        }else{
            $data=validate('News')->goCheck('add');
            if(!is_array($data)){
                $d=request()->param();
                $d['create_time']=time();
                $d['update_time']=$d['create_time'];
                $menu=model('News')->create($d);
                if($menu){
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
        $data=validate('News')->goCheck('status');
        if(!is_array($data)){
            $news=model('News')->isUpdate(true)->save(request()->param());
            if($news){
                return json(['status'=>1,'message'=>'操作成功']);
            }else{
                return json(['status'=>0,'message'=>'操作失败']);
            }
        }else{
            return json($data);
        }
    }
}