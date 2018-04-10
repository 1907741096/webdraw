<?php
namespace app\index\controller;

class Index extends Common
{
    public function index()
    {
        $data['status']=self::$status;
        $this->assign('menu','index');
        return $this->fetch();
    }
}
