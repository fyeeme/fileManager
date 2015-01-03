<?php
/**
 * Created by PhpStorm.
 * User: arno
 * Date: 15-1-1
 * Time: 下午1:18
 */

//提示操作信息，并跳转
function alertMsg($msg,$url){
    echo "<script type='text/javascript'>alert('{$msg}');window.location.href='{$url}'</script>";
}

//获取文件后缀名
function get_extension($file)
{
    return strtolower(pathinfo($file, PATHINFO_EXTENSION));
}

//创建唯一名称
function getUniqidName(){
    $length =10;
    return substr(md5(uniqid(microtime(true),tru)),0, $length);
}