<?php
namespace app\index\controller;

use think\Db;

class Content extends Islogin
{
    public function index()
    {
        $data = Db::name('content')->select();

        $cate = Db::name('category')->order('order','desc')->where('cate_type','<>','4')->select();
        $cate = tree($cate);

        $this->assign('cate',$cate);
        $this->assign('data',$data);

        return $this->fetch();
    }
    public function addlink()
    {

        $data = Db::name('content')->order('order','desc')->select();
        $data = tree($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function add(){
        $data = Db::name('content')->select();
        $data = tree($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function show()
    {
        $cate = Db::name('content')->where('id','<>',input('id'))->order('order','desc')->select();
        $cate = tree($cate);
        $data = Db::name('content')->find(input('id'));
        $this->assign('cate',$cate);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function update()
    {
        $data = input('post.');
        $file = request()->file('images');
        if($file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'uploads');
            if($info){
                $images = $info->getSaveName();
            }else{
                // 上传失败获取错误信息
                $images = $file->getError();
            }
            $data['images']=$images;
        }else{
            $data['images']='';
        }
        Db::name('category')->update($data);
        $this->redirect('category/index');
    }

    public function insert(){
        $data = input('post.');

        Db::name('content')->insert($data);
        $this->redirect('category/index');
    }
    public function isDisplay()
    {
        $data = input('param.');
        Db::name('content')->update($data);
        $this->redirect('category/index');
    }

    public function delete(){
        $data = input('post.');
        Db::name('content')->delete($data);
    }
}
