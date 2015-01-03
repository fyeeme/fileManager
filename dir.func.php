<?php
/**
 * Created by PhpStorm.
 * User: arno
 * Date: 14-12-28
 * Time: 上午12:27
 */

function readDirectory($path){
    $handle = opendir($path);

   while(($item = readdir($handle)) !== false ){
       if($item != "." && $item != ".."){
           if(is_file($path."/".$item)){
               $arr['file'][] = $item;
           }
           if(is_dir($path."/".$item)){
               $arr['dir'][] = $item;
           }
       }
     };
    closedir($path);
    return $arr;
}


/**
 * @param $dirname 文件夹全路径
 */
function createFolder($dirname){
    if(!isvalidate($dirname)){
        if(!file_exists($dirname)){
            if(mkdir($dirname,0777,true)){
                $msg = "文件夹创建成功";
            }else{
                $msg = "文件夹创建失败";
            }
        }else{
            $msg ="文件夹已存在";
        }
    }else{
        $msg = "非法文件夹名称";
    }
    return $msg;
}
//$//path ='file';
//print_r(readDirectory($path));


