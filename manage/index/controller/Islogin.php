<?php
namespace app\index\controller;

use think\Controller;

class Islogin extends Controller
{
    public function _initialize()
    {
       if(!session('id')){
        $this->error('请先登录系统！！',url('/index/login/index'));
       }
    }
}

