<?php
namespace app\index\controller;

use think\Db;

class Option extends Islogin
{
    public function index()
    {
        $data = Db::name('option')->find(1);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update()
    {
        $data = input('post.');

        Db::name('option')->update($data);

        optionlog('更新网站站点设置');

        $this->redirect('index');
    }
}
