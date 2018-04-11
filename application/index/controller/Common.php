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
        $user_id='';
        if(!session('user')||session('user')==null){
            $user_id='1';
        }
        $this->assign('user_id',$user_id);
        $this->assign('menu','index');
        $this->assign('title',input('title'));
    }
    public function getCommendNews(){
        $data['status']=self::$status;
        $data['position_id']=2;
        $CommendNews=model('Content')->where($data)->order('listorder desc')->limit(5)->select();
        $this->assign('CommendNews',$CommendNews);
    }
    public function puterror($message){
        $this->assign('message',$message);
        return $this->fetch('Common/error');
    }
}