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
}
