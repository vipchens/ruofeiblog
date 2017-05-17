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
        $weather = weekWeather();
        $data = json_decode($weather,true);

        $oneDay = json_decode(oneDay(),true);

        //获取该客户端的ip
//        $data['ip'] = getIPaddress();
        //获取该客户端所在的城市
//        $data['ipaddress'] = taobaoIP($data['ip']);

//        $this->assign('data',$data);

        $weekarray=array("日","一","二","三","四","五","六"); //先定义一个数组

        $xingqi = "星期".$weekarray[date("w")];

        $this->assign('data',$data['value']);
        $this->assign('one',$oneDay);
        $this->assign('x',$xingqi);

        return $this->fetch();
    }

    public function random()
    {
        $oneDay = json_decode(oneDay('random'),true);
        return $oneDay;
    }
}
