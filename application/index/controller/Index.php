<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('menu','index');
        $this->assign('title',input('title'));
        $this->assign('menu','index');
        return $this->fetch();
    }
    public function openImage(){
        $file=request()->file('file');
        $info=$file->move('upload'); //给定一个目录
        if($info&&$info->getPathname()){
            return json([
                'status'=>1,
                'message'=>'success',
                'src'=>config('setting.img_prefix').$info->getPathname(),
                'thumb'=>config('setting.img_prefix').$info->getPathname()
            ]);
        }else{
            return json([
                'status'=>0,
                'message'=>'error',
            ]);
        }
    }
}
