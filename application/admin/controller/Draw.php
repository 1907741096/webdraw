<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2018/4/4
 * Time: 17:18
 */

namespace app\admin\controller;


class Draw extends Base
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
        $draws=model('draw')->getAllDraws(config('setting.page_count'),$data);

        $status['status']=array('eq',1);
        $this->assign('user_id',$user_id);
        $this->assign('draws',$draws);
        $this->assign('nav','draw');
        return $this->fetch();
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