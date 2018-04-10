<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2018/4/4
 * Time: 17:18
 */

namespace app\admin\controller;


class Content extends Base
{
    public function index(){
        $data['status']=array('neq',-1);
        $users=model('user')->all();
        $this->assign('users',$users);
        $user_id='';
        if(input('user_id')) {
            $data['user_id']=input('user_id');
            $user_id=$data['user_id'];
        }
        $contents=model('content')->getAllContents(config('setting.page_count'),$data);

        $status['status']=array('eq',1);
        $this->assign('user_id',$user_id);
        $this->assign('contents',$contents);
        $this->assign('nav','content');
        return $this->fetch();
    }
}