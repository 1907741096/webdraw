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
        $draws=model('draw')->where($data)->order('id desc')->select();
        $count=model('draw')->where($data)->count();
        $this->assign('count',ceil($count/config('setting.page_count')));
        $this->assign('draws',$draws);
        $this->assign('page',$page);
        $this->assign('menu','draw');
        return $this->fetch();
    }
    public function detail(){
        $msg=validate('draw')->gocheck('id');
        if(is_array($msg)){
            return $this->puterror($msg['message']);
        }
        $this->assign('title',input('title'));
        $id=input('id');
        $data['id']=$id;
        $draw=model('draw')->find($data);
        $this->assign('menu','draw');
        $this->assign('draw',$draw);
        return $this->fetch();
    }
    public function info(){
        $id=input('id');
        $data['id']=$id;
        $draw=model('draw')->find($data);
        $draw['content']=json_decode($draw['content']);
        return json($draw);
    }
    public function status(){
        $data=validate('Draw')->goCheck('status');
        if(!is_array($data)){
            $news=model('Draw')->isUpdate(true)->save(request()->param());
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