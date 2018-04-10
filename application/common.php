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
    function getCommonStatus($status){
        if($status==1){
            $message="<text style='color:mediumseagreen'>开启</text>";
        }elseif($status==0){
            $message="<text style='color:#999'>关闭</text>";
        }else{
            $message="<text style='color:red'>已删除</text>";
        }
        return $message;
    }
    function getPower($power){
        if($power==1){
            return '总管理员';
        }else{
            return '管理员';
        }
    }
    function jump($url){
        echo "<meta http-equiv='refresh' content='0;url=".$url."'/>";
        exit;
    }
    function gettitle($title){
        if(mb_strlen($title)<=10){
            return $title;
        }else{
            return mb_substr($title, 0,10,'utf-8').'...';
        }
    }
    function getcontent($content){
        if(mb_strlen($content)<=100){
            return $content;
        }else{
            return mb_substr($content, 0,100,'utf-8').'...';
        }
    }
