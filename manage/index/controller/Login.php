<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{
    public function index()
    {
        if(!session('id'))
            return $this->fetch();
        $this->redirect('index/index');
    }

    public function login()
    {
        $data = input('post.');

        //判断验证码

        $admin = Db::name('admin')->where(['username'=>$data['username'],'password'=>md5($data['password'])])->select();
        if(!$admin){
            return json(array('code'=>0,'msg'=>'您的帐号或密码不正确'));
        }
        session('id',$admin[0]['id']);

        return json(array('code'=>200,'msg'=>'登录成功'));
    }

    public function logout()
    {
        //退出清楚session
        session(null);
        $this->redirect('index/login');
    }
}
