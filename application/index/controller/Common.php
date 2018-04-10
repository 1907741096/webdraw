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
        $user='';
        if(session('user')&&session('user')!=null){
            $user='1';
        }
        $this->assign('user',$user);
        $this->assign('menu_id','');
        $this->assign('menu','index');
        $this->assign('title',input('title'));
    }
    public function getCommendNews(){
        $data['status']=self::$status;
        $data['position_id']=2;
        $CommendNews=model('Content')->where($data)->order('listorder desc')->limit(5)->select();
        $this->assign('CommendNews',$CommendNews);
    }
    public function getAllStyle(){
        $styles=model('Style')->all(['status'=>self::$status]);
        $this->assign('styles',$styles);
    }
    public function puterror($message){
        $this->assign('message',$message);
        return $this->fetch('Common/error');
    }
    public function getStyle(){
        $style_id=config('setting.default_style');
        if(session('style_id')&&session('style_id')!=null){
            $style_id=session('style_id');
        }
        if(session('user')&&session('user')!=null){
            $id=session('user')['id'];
            $user=model('User')->find($id);
            $style_id=$user['style_id'];
        }
        $style=model('Style')->find($style_id);
        $this->assign('styleThumb',$style['thumb']);
        $this->assign('styleAddress',$style['address']);
    }
}