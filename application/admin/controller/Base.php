<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/20
 * Time: 20:13
 */

namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->_init();
    }

    private function _init() {
        $isLogin = $this->isLogin();
        if(!$isLogin) {
            jump('/webdraw/public/index.php/admin/login');
        }
    }

    public function isLogin() {
        $admin = $this->getLoginUser();
        if($admin && $admin!=null) {
            return true;
        }
        return false;
    }

    public function getLoginUser() {
        return session("admin");
    }
}