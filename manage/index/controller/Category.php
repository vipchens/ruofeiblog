<?php
namespace app\index\controller;

use think\Db;

class Category extends Islogin
{
    public function index()
    {
        $data = Db::name('category')->order('order','desc')->select();
        $data = tree($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function addlink()
    {
        $data = Db::name('category')->order('order','desc')->select();
        $data = tree($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function add(){
        $data = Db::name('category')->select();
        $data = tree($data);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function show()
    {
        $cate = Db::name('category')->where('id','<>',input('id'))->order('order','desc')->select();
        $cate = tree($cate);
        $data = Db::name('category')->find(input('id'));
        $this->assign('cate',$cate);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function showlink()
    {
        $cate = Db::name('category')->where('id','<>',input('id'))->select();
        $cate = tree($cate);

        $data = Db::name('category')->find(input('id'));

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

        if(input('link')){
            optionlog('更新 连接:'.input('link'));
        }else{
            optionlog('更新 栏目id:'.input('id'));
        }

        $this->redirect('category/index');
    }

    public function insert(){
        $data = input('post.');

        Db::name('category')->insert($data);

        if(input('link')){
            optionlog('添加 连接:'.input('cate_name').'---'.input('link'));
        }else{
            optionlog('添加 栏目:'.input('cate_name'));
        }

        $this->redirect('category/index');
    }
    public function isDisplay()
    {
        $data = input('param.');
        Db::name('category')->update($data);

        if(input('is_show')==0){
            optionlog('隐藏分类id:'.input('id'));
        }else{
            optionlog('显示分类id:'.input('id'));
        }

        $this->redirect('category/index');
    }

    public function delete(){
        $data = input('post.');
        Db::name('category')->delete($data);

        if(input('type')==4){
            optionlog('删除连接id:'.input('id'));
        }else{
            optionlog('删除分类id:'.input('id'));
        }
    }
}
