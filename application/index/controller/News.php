<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 17:04
 */

namespace app\index\controller;
use think\Controller;

class News extends Controller
{
    protected static $status = array('eq',1);
    public function index($page=1){
        $data['status']=self::$status;
        if(input('title')){
            $data['title']=array('like','%'.input('title').'%');
        }
        $this->assign('title',input('title'));
        $news=model('news')->getAllNews(config('setting.page_count'),$data);
        $count=model('news')->where($data)->count();
        $this->assign('count',ceil($count/config('setting.page_count')));
        $this->assign('news',$news);
        $this->assign('page',$page);
        $this->assign('tags',config('tags'));
        $this->assign('menu','news');
        return $this->fetch();
    }
    public function detail(){
        $msg=validate('news')->gocheck('id');
        if(is_array($msg)){
            return $this->puterror($msg['message']);
        }
        $this->assign('title',input('title'));
        $id=input('id');
        $data['status']=self::$status;
        $data['id']=$id;
        $new=model('news')->find($data);
        $comments=model('comment')->getAllComments(config('setting.page_count'),['news_id'=>$id]);
        $this->assign('comments',$comments);
        $this->assign('tags',config('tags'));
        $this->assign('menu','news');
        $this->assign('new',$new);
        return $this->fetch();
    }
    public function comment(){
        if(!session('user')||session('user')==null){
            return json(['status'=>0,'message'=>'请先登录']);
        }
        $msg=validate('comment')->gocheck('add');
        if(!is_array($msg)){
            $d=request()->param();
            $d['user_id']=session('user')['id'];
            $d['create_time']=time();
            $d['update_time']=$d['create_time'];
            $comment=model('Comment')->create($d);
            if($comment){
                return json(['status'=>1,'message'=>'添加成功']);
            }else{
                return json(['status'=>0,'message'=>'添加失败']);
            }
        }else{
            return json($msg);
        }
        return json($msg);
    }
    public function save(){
        if(!session('user')||session('user')==null){
            return json(['status'=>0,'message'=>'请先登录']);
        }
        $data=validate('News')->goCheck('user-add');
        if(!is_array($data)){
            $d=request()->param();
            $d['user_id']=session('user')['id'];
            $d['status']=1;
            $d['create_time']=time();
            $d['update_time']=$d['create_time'];
            $id=model('News')->create($d);
            if($id){
                return json(['status'=>1,'message'=>'添加成功']);
            }else{
                return json(['status'=>0,'message'=>'添加失败']);
            }
        }else{
            return json($data);
        }
        return json($data);
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