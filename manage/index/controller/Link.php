<?php
namespace app\index\controller;

use think\Db;
class Link extends Islogin
{
    public function index()
    {
        $data = Db::name('link')->order('order','desc')->paginate(8);
        $count = Db::name('link')->count();

        $this->assign('data',$data);
        $this->assign('count',$count);
        return $this->fetch();
    }
    public function add(){
        return $this->fetch();
    }
    public function show()
    {
        $data = Db::name('link')->find(input('id'));
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update()
    {
        $data = input('post.');
        $file = request()->file('icon');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'uploads');
            if($info){
                $images = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $images = $file->getError();
            }
            $data['icon']=$images;
        }else{
            $data['icon']='';
        }
        Db::name('link')->update($data);

        optionlog('更新 友情链接:'.input('title'));
        $this->redirect('link/index');
    }

    public function insert(){
        $data = input('post.');
        $file = request()->file('icon');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'uploads');
            if($info){
                $images = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $images = $file->getError();
            }
            $data['icon']=$images;
        }else{
            $data['icon']='';
        }

        Db::name('link')->insert($data);

        optionlog('添加 友情链接:'.input('title'));

        $this->redirect('link/index');
    }
    public function isDisplay()
    {
        $data = input('param.');
        Db::name('link')->update($data);

        if(input('is_show')==0){
            optionlog('隐藏分类id:'.input('id'));
        }else{
            optionlog('显示分类id:'.input('id'));
        }

        $this->redirect('link/index');
    }

    public function delete(){
        Db::name('link')->delete(input('id'));
        optionlog('删除友情链接:'.input('id'));
        $this->redirect('link/index');
    }
}
