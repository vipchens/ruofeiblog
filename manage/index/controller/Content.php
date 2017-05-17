<?php
namespace app\index\controller;

use think\Db;

class Content extends Islogin
{

    public function index()
    {
        $conditions = '';
        session('cate_id',null);
        $data = Db::name('content')->order('id','desc')
            ->paginate(8);

        $count = Db::name('content')->order('order','desc')->count();

        $cate = Db::name('category')->order('order','desc')
            ->where('cate_type','<>','4')->select();
        $cate = tree($cate);

        $this->assign('conditions',$conditions);

        $this->assign('count',$count);
        $this->assign('cate',$cate);
        $this->assign('data',$data);

        return $this->fetch();
    }
    public function cateList()
    {
        $conditions = '';

        $id = input('id');
        if($id){
            session('cate_id',$_GET['id']);
        }
        $data = Db::name('content')
            ->where('cate_id',session('cate_id'))
            ->order('order','desc')
            ->paginate(8);
        $count = Db::name('content')
            ->where('cate_id',session('cate_id'))->order('order','desc')->count();
        $cate = Db::name('category')->order('order','desc')
            ->where('cate_type','<>','4')->select();
        $cate = tree($cate);

        $this->assign('conditions',$conditions);

        $this->assign('count',$count);
        $this->assign('cate',$cate);
        $this->assign('data',$data);

        return $this->fetch('content/index');
    }
    public function add(){
        $cate = Db::name('category')
            ->where('cate_type','<>','4')
            ->field('id,fid,cate_name')->select();
        $cate = tree($cate);
        $this->assign('cate',$cate);
        return $this->fetch('add_m');
    }
    public function show()
    {
        $cate = Db::name('category')->where('cate_type','<>','4')->select();
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
        Db::name('content')->update($data);

        optionlog('更新了数据 - '.input('id'));

        $this->redirect('content/index');
    }

    public function batch_move()
    {
        $where['id'] = array('in',$_POST['id']);

        $result = Db::name('content')->where($where)->setField('cate_id',$_POST['cate_id']);
        optionlog('批量 移动 数据id'.implode(',',$_POST['id']).'到栏目id:'.$_POST['cate_id']);

        if($result){
            return json(array('code'=>1,'msg'=>'操作成功！'));
        }
        return json(array('code'=>0,'msg'=>'操作失败！'));
    }

    public function batch_copy()
    {
        $where['id'] = array('in',$_POST['id']);
        $data = Db::name('content')->where($where)->select();

        foreach ($data as $vo){
            $insert['title'] = $vo['title'];
            $insert['content'] = $vo['content'];
            $insert['cate_id'] = $_POST['cate_id'];
            $insert['images'] = $vo['images'];
            $insert['desc'] = $vo['desc'];
            $insert['views'] = $vo['views'];
            $insert['order'] = $vo['order'];
            $insert['is_show'] = $vo['is_show'];
            $insert['keywords'] = $vo['keywords'];
            $insert['description'] = $vo['description'];
            $insert['create_time'] = $vo['create_time'];
            $insert['update_time'] = $vo['update_time'];

            Db::name('content')->insert($insert);
        }
        optionlog('批量 复制 数据id'.implode(',',$_POST['id']).'到栏目id:'.$_POST['cate_id']);
        return json(array('code'=>1,'msg'=>'操作成功！'));
    }

    public function batch_delete()
    {
        $where['id'] = array('in',$_POST['id']);
        $result = Db::name('content')->where($where)->delete();
        optionlog('批量 删除 数据id'.implode(',',$_POST['id']));

        if($result)
            return json(array('code'=>1,'msg'=>'操作成功！'));
        return json(array('code'=>0,'msg'=>'操作失败！'));
    }

    public function batch_show()
    {
        $where['id'] = array('in',$_POST['id']);
        $result = Db::name('content')->where($where)->setField('is_show','1');

        optionlog('批量 显示 数据id'.implode(',',$_POST['id']));

        if($result)
            return json(array('code'=>1,'msg'=>'操作成功！'));
        return json(array('code'=>0,'msg'=>'操作失败！'));
    }

    public function batch_hide()
    {
        $where['id'] = array('in',$_POST['id']);
        $result = Db::name('content')->where($where)->setField('is_show','0');
        optionlog('批量 隐藏 数据id'.implode(',',$_POST['id']));
        if($result)
            return json(array('code'=>1,'msg'=>'操作成功！'));
        return json(array('code'=>0,'msg'=>'操作失败！'));
    }

    public function insert(){

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

        Db::name('content')->insert($data);

        optionlog('添加了文章 '.$data['title']);

        $this->redirect('content/index');
    }

    public function search()
    {

//        第一页正常
        $list = input('list');
        $list = explode('|',$list);
        $order[$list[0]] = $list[1];

        if(session('cate_id')){
            $where['cate_id'] = session('cate_id');
        }else{
            $where = '';
        }

        if(input('is_show')!='all'){
            $where['is_show'] = input('is_show');
        }


        $conditions = input('post.');


        $data = Db::name('content')
            ->where('title','like','%'.input('keywords').'%')
            ->where($where)
            ->order($order)->paginate(800);

        $count = Db::name('content')
            ->where('title','like','%'.input('keywords').'%')
            ->where($where)
            ->count();

        $cate = Db::name('category')->order('order','desc')
            ->where('cate_type','<>','4')->select();
        $cate = tree($cate);

        $this->assign('conditions',$conditions);
        $this->assign('count',$count);
        $this->assign('cate',$cate);
        $this->assign('data',$data);

        return $this->fetch('content/index');
    }

    public function isDisplay()
    {
        $data = input('param.');
        Db::name('content')->update($data);

        if(input('is_show')==0){
            optionlog('隐藏数据id:'.input('id'));
        }else{
            optionlog('显示数据id:'.input('id'));
        }

        $this->redirect('content/index');
    }

    public function delete(){
        $data = input('post.');
        Db::name('content')->delete($data);
        optionlog('删除了数据id:'.$data['id']);
    }
}
