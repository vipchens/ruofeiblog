<?php
namespace app\index\controller;

use think\Db;

class Log extends Islogin
{
    public function index()
    {
        $data = Db::name('log')->order('id','desc')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
}
