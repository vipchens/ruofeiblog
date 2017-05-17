<?php
namespace app\index\controller;

use think\Db;

class Admin extends Islogin
{
    public function index()
    {
        $data = Db::name('admin')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function add(){
        return $this->fetch();
    }

    public function show()
    {
        $data = Db::name('admin')
            ->where('id',input('id'))->select();

        $this->assign('data',$data[0]);
        return $this->fetch();
    }
    public function update()
    {
        if($password = input('password')){
            $md5 = md5($password);
        }
        $newPassword = input('newPassword');

        $data['id'] = input('id');
        $data['nickname'] = input('nickname');

        if ($md5){
            $old = Db::name('admin')->find(input('id'));
            if($md5 != $old['password']){
                $this->error('原密码不正确！');
            }
            $data['password'] = md5($newPassword);
        }

        Db::name('admin')->update($data);

        optionlog('修改帐号（'.input('id').'）信息');

        $this->redirect('index');
    }

    public function insert()
    {
        $data = input('post.');
        $count = Db::name('admin')->where('username',$data['username'])->select();

        if($count){
            $this->error('帐号已存在！');
        }

        $admin['username'] = $data['username'];
        $admin['nickname'] = $data['nickname'];
        $admin['password'] = md5($data['password']);
        $admin['create_time'] = $data['create_time'];

        Db::name('admin')->insert($admin);

        optionlog('创建了帐号：'.$admin['nickname'].'('.$admin['username'].')');

        $this->redirect('admin/index');
    }

    public function option()
    {
        $data = Db::name('admin')->find(session('id'));
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function option_update()
    {
        $data = input('post.');

        $list = explode('|',input('cityInfo'));

        $update['id'] = $data['id'];
        $update['email'] = $data['email'];
        $update['footer_info'] = $data['footer_info'];
        $update['personal'] = $data['personal'];
        $update['city_code']  = $list[0];
        $update['city']  = $list[1];

        Db::name('admin')->update($update);

        optionlog('更新博主信息');
        $this->redirect('admin/option');
    }

    public function delete(){
        $data = input('post.');
        Db::name('admin')->delete($data);
        optionlog('删除账号id：'.input('id'));

    }

    public function city()
    {
        $data = input('val');

        return Db::name('city_code')
            ->where('city','like','%'.$data.'%')
            ->field('city,code')
            ->select();
    }
}
