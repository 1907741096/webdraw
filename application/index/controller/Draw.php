<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 17:04
 */

namespace app\index\controller;
use think\Controller;

class Draw extends Controller
{
    protected static $status = array('eq',1);
    public function index($page=1){
        $data['status']=self::$status;
        if(input('title')){
            $data['title']=array('like','%'.input('title').'%');
        }
        $this->assign('title',input('title'));
//        $draws=model('draw')->getAllDraws(config('setting.page_count'),$data);
        $draws=model('draw')->where($data)->select();
        $count=model('draw')->where($data)->count();
        $this->assign('count',ceil($count/config('setting.page_count')));
        $this->assign('draws',$draws);
        $this->assign('page',$page);
        $this->assign('menu','draw');
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
        $this->assign('new',$new);
        return $this->fetch();
    }
}