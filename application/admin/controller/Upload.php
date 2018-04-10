<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/17
 * Time: 17:59
 */

namespace app\admin\controller;

use think\Controller;
use think\File;

class Upload extends Base
{
    public function image(){
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