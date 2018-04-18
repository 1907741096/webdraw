<?php
/**
 * Created by PhpStorm.
 * User: 王振远
 * Date: 2017/7/25
 * Time: 18:24
 */

namespace app\index\controller;


use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if (session('user') && session('user') != null) {
            jump('/Index');
        } else {
            return $this->fetch('/login/login');
        }
    }

    public function reg()
    {
        if (session('user') && session('user') != null) {
            jump('/Index');
        } else {
            return $this->fetch('/login/register');
        }
    }

    public function checklogin()
    {
        $msg = $this->check(input());
        if (is_array($msg)) {
            return json($msg);
        } else {
            return json(['status' => 1, 'message' => '登录成功']);
        }
    }

    public function register()
    {
        $data = input();
        if (!$data['username'] || trim($data['username']) == '') {
            return json(['status' => 0, 'message' => '请输入账号']);
        }
        if (!$data['password'] || trim($data['password']) == '') {
            return json(['status' => 0, 'message' => '请输入密码']);
        }
        if (trim($data['password']) != trim($data['repassword'])) {
            return json(['status' => 0, 'message' => '两次密码不一致']);
        }
        $user = model('User')->get(['username' => $data['username']]);
        if ($user) {
            return json(['status' => 0, 'message' => '账号已注册']);
        }
        $u['lastlogin_time'] = time();
        $u['create_time'] = time();
        $u['username'] = $data['username'];
        $u['password'] = md5(config('setting.md5_pre') . $data['password']);
        $id = model("User")->isUpdate(false)->save($u);
        if ($id) {
            return json(['status' => 1, 'message' => '注册成功']);
        } else {
            return json(['status' => 0, 'message' => '注册失败']);
        }

    }

    public function check($data)
    {
        if (!$data['username'] || trim($data['username']) == '') {
            return ['status' => 0, 'message' => '请输入账号'];
        }
        if (!$data['password'] || trim($data['password']) == '') {
            return ['status' => 0, 'message' => '请输入密码'];
        }
        $user = model('User')->get(['username' => $data['username']]);
        if (!$user) {
            return ['status' => 0, 'message' => '账号不存在'];
        }
        if ($user['status'] != 1) {
            return ['status' => 0, 'message' => '账号已被冻结，请联系总管理员'];
        }
        if ($user['password'] != md5(config('setting.md5_pre') . $data['password'])) {
            return ['status' => 0, 'message' => '密码错误'];
        }
        session('user', $user);
        $u['lastlogin_time'] = time();
        $u['id'] = $user['id'];
        model("User")->isUpdate(true)->save($u);
        return true;
    }

    public function loginout()
    {
        session('user', null);
        if (session('user') == null) {
            return json(['status' => 1, 'message' => '退出成功', 'jump_url' => $_SERVER['HTTP_REFERER']]);
        } else {
            return json(['status' => 0, 'message' => '退出失败']);
        }
    }
}