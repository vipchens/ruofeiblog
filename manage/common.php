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