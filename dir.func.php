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

function dirsize($path){
    $handler = opendir($path);
    $sum =0;
    global $sum;
    while(($item = readdir($handler)) !== false){
        $fp = $path."/".$item;
        if($item !="." && $item !=".."){
            if(is_file($fp)){
                $sum += filesize($fp);
            }
            if(is_dir($fp)){
                $func = __FUNCTION__;
                $func($fp);
            }
        }
    }
    closedir($path);
    return $sum;
    // return transByte($sum);
}
//$path ='file';
//print_r(readDirectory($path));
//echo dirsize($path);


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

/**
 * 复制文件夹
 * @param $srcFile
 * @param $disFile
 * @return string
 */
function copyFolder($srcFile, $disFile){

    if(is_file($srcFile)){
        copy($srcFile, $disFile."/".basename($srcFile));
    }else{
        $handler = opendir($srcFile);
        if(!file_exists($disFile)){
            mkdir($disFile,0777,true);
        }
        while(($item = readdir($handler)) !== false){
            if($item != "." && $item != ".."){
                if(is_file($srcFile."/".$item)){
                    copy($srcFile."/".$item, $disFile."/".basename($item));
                }
                if(is_dir($srcFile."/".$item)){
                    $func = __FUNCTION__;
                    $func($srcFile."/".$item, $disFile."/".$item);
                }
            }
        }
        closedir($srcFile);
    }
    return "复制成功";
}


/**
 * 重命名
 * @param $src
 * @param $dis
 * @return string
 */
function renameFolder($src, $dis){
    if(!isvalidate(basename($dis))){
        if(!file_exists($dis)){
            if(rename($src,$dis)){
                $msg = "重命名成功";
            }else{
                $msg="重命名失败";
            }
        }else{
            $msg = "存在同名的文件夹";
        }
    }else{
        $msg="非法文件夹名称";
    }
    return $msg;
}

/**
 * 剪切文件夹
 * @param $srcPath
 * @param $distPath
 */
function cutFolder($srcPath, $distPath){
    if(file_exists($distPath)){
        if( (is_dir($distPath))){
            if(!file_exists($distPath."/".basename($srcPath))){
                if(rename($srcPath, $distPath."/".basename($srcPath))){
                    $msg ="复制成功";
                }else{
                    $msg="复制失败";
                }
            }else{
                $msg ="已存在同名文件夹";
            }
        }else{
            $msg = "只能复制到文件";
        }
    }else{
        $msg = "目标文件夹不存在";
    }
    return $msg;
}


function deleteFolder($filePath){
    if(file_exists($filePath)){
        $handler = opendir($filePath);
        while(($item =readdir($handler))!== false){
            if($item !="." && $item !=".."){
                if(is_file($filePath."/".$item)){
                    unlink($filePath."/".$item);
                }else{
                    deleteFolder($filePath."/".$item);
                }
            }
        }
        closedir($handler);
        rmdir($filePath);
        $msg ="文件夹删除成功";
    }else{
        $msg = "目标文件夹不存在";
    }
    return $msg;
}
