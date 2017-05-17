<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Db;
use think\Request;


// 应用公共文件


/*
 * 无限级别分类
 *
 * $data:   待分类的所有数据
 * $html:   分类的前缀符号,例如'|-'
 * $spear:  分级中使用的分割符号，例如'&nbsp;'
 * $fid:    父级ID
 * level:   层级关系
 *
 */
function tree($data,$html='|-',$spear='&nbsp;&nbsp;',$fid='0',$level='0'){
    $array = array();
    foreach ($data as $val){
        if($fid == $val['fid']){
            if($val['fid']==0){
                $val['html'] = '';
            }else{
                $val['html'] = $html;
            }
            $val['spear'] = str_repeat($spear,$level*2);

            $array[] = $val;
            //与之前的$array数据合并
            $array = array_merge($array,tree($data,$html,$spear,$val['id'],$level+1));
        }
    }
    return $array;
}

/*
 * optionlog记录
 *
 * $data:   操作信息
 *
 */

function optionlog($val){

    $request = Request::instance();

    $data['user_id'] = session('id');
    $data['action'] = $val;
    $data['ip'] = getIPaddress();
    $data['ip_address'] = taobaoIP($data['ip']);
    Db::name('log')->insert($data);
}



function getIPaddress(){
    $IPaddress='';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    return $IPaddress;
}

function taobaoIP($clientIP){
    $taobaoIP = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$clientIP;

    $IPinfo = json_decode(file_get_contents($taobaoIP));
    if($IPinfo->code != 1){
        $province = $IPinfo->data->region;
        $city = $IPinfo->data->city;
        $data = $province.$city;
        return $data;
    };
    return '';
}





function weekWeather(){
    //未来一周天气api
    $url = 'http://aider.meizu.com/app/weather/listWeather?cityIds=';
    $data = Db::name('admin')->find(session('id'));
    $url = $url.$data["city_code"];

    //get方式使用api
    $file_contents = file_get_contents($url);

//    return json_decode($file_contents,true);

    return $file_contents;

    //post方式使用api
//    $ch = curl_init ();
//    curl_setopt ( $ch, CURLOPT_URL, $url );
//    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
//    curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
//    curl_setopt ( $ch, CURLOPT_POST, 1 ); //启用POST提交
//    $file_contents = curl_exec ( $ch );
//    curl_close ( $ch );


}



/*
 *
 * 每日一文API
 *
 *
 */
function oneDay($type=''){
    if($type==null){
        $url = 'https://interface.meiriyiwen.com/article/today?dev=1';
    }elseif($type == 'random'){
        $url = 'https://interface.meiriyiwen.com/article/random?dev=1';
    }

    //get方式获取
    $file_contents = file_get_contents($url);
    return $file_contents;
}