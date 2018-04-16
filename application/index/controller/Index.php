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
    public function save(){
        dump(request()->param());exit;
        if(!session('user')||session('user')==null){
            return json(['status'=>0,'message'=>'请先登录']);
        }
        $data=validate('Draw')->goCheck('add');
        $data=null;
        if(!is_array($data)) {
            $d=request()->param();
//            $d['thumb']='/static/image/face.png';
            $d['content']=json_encode($d['content']);
            $d['user_id'] = session('user')['id'];
            $d['status'] = 1;
            $d['create_time'] = time();
            $d['update_time'] = $d['create_time'];
            $id = model('Draw')->create($d);
            if ($id) {
                return json(['status' => 1, 'message' => '保存成功']);
            } else {
                return json(['status' => 0, 'message' => '保存失败']);
            }
        }else{
            return json($data);
        }
    }
}
