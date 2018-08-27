<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/24
 * Time: 16:50
 */

namespace app\index\controller;


use think\Controller;

class Common extends Controller
{
    protected static $status = array('eq',1);
    public function __construct(){
        parent::__construct();
        $this->assign('menu','index');
        $this->assign('title',input('title'));
        if(!session('user')||session('user')==null){
            jump('/webdraw/public/index.php/index/login');
        }
        $user_id=session('user')['id'];
        $this->assign('user_id',$user_id);
    }
    public function puterror($message){
        $this->assign('message',$message);
        return $this->fetch('Common/error');
    }
}