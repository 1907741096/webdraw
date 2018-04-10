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
        $this->assign('menu','news');
        $this->assign('new',$new);
        return $this->fetch();
    }
}