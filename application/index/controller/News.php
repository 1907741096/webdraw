<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 17:04
 */

namespace app\index\controller;


class News extends Common
{
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
        $msg=validate('comment')->gocheck('add');
        if(!is_array($msg)){
            $d=request()->param();
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
}