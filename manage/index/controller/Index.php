<?php
namespace app\index\controller;

use think\Db;
class Index extends Islogin
{
    public function index()
    {
        $id = session('id');
        $data = Db::name('admin')->find($id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function welcome()
    {
        return $this->fetch();
    }
}
