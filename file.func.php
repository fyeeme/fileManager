<?php
/**
 * Created by PhpStorm.
 * User: arno
 * Date: 14-12-28
 * Time: 下午11:17
 */
//bytes/KB/MB/GB/TB/EB
/**
 * 转换字节大小
 * @param $size
 * @return string
 */
function transByte($size){
    $arr= array("B", "KB","MB", "GB", "TB", "EB");
    $i = 0;
    while($size >=1024){
        $size/=1024;
        $i++;
    }
    return round($size, 2).$arr[$i];
}

/**
 * 创建文件
 * @param $fileName 文件名
 * @return string
 */
function createFile($fileName){
    if(!isvalidate($fileName)){
        if(!file_exists($fileName)){
            if(touch($fileName)){
                return "文件创建成功";
            }else{
                return "文件创建失败";
            }
        }else{
            return "文件名已经存在，请重新命名";
        }
    }else{
        return "文件名不能包含非法字符";
    }
}

function renameFile($oldName, $newName){
    $newName = dirname($oldName)."/".$newName;
    if(!file_exists($oldName)){
        $msg = "原文件不存在，重命名失败";
    }elseif( $oldName ==$newName){
       $msg ="文件名不能和原文见名相同";
    }elseif(!file_exists($newName)){
        if(isvalidate($newName)){
           $msg ="文件名不能包含非法字符";
        }else if(rename($oldName, $newName)){
            $msg = "重命名成功";
        }else{
            $msg = "重命名失败";
        }
    }else{
        $msg = "文件名已经存在";
    }
    return $msg;
}

function deleteFile($fileName){
    if(file_exists($fileName)){
        if(unlink($fileName)){
          $msg = "删除成功";
        }else{
          $msg ="删除失败";
        }
    }else if(is_dir($fileName)){

    }
    return $msg;
}

function copyFile($oldFile, $newFIle){
    if(file_exists($newFIle)){

        if(!file_exists($newFIle."/".basename($oldFile))){
            if(copy($oldFile,$newFIle."/".basename($oldFile))){
                $msg = "文件复制成功";
            }else{
                $msg="文件复制失败";
            }
        }else{
            $msg ="该目录下已经存在相同的文件";
        }
    }else{
        $msg ='目标文件夹不存在';
    }
    return $msg;
}

function downloadFile($fileName){
    header("content-disposition:attachment;filename=".basename($fileName));
    header("content-length:".filesize($fileName));
    readfile($fileName);
}

function uploadFile($file, $path){
    $allowSuffix = array('gif', 'jpeg', 'jpg', 'png', 'text', 'php');
    $maxSize = 1024*1024*10; //10M

    //判断错误号
    if($file['error'] ==UPLOAD_ERR_OK){
        if(is_uploaded_file($file['tmp_name'])){
            echo 1;
            $suffix = get_extension($file['name']);
            $unipid =getUniqidName();
            $destination = $path."/".pathinfo($file['name'],PATHINFO_FILENAME)."_".$unipid.".".$suffix;
            if(in_array($suffix,$allowSuffix)){
                if($file['size'] <= $maxSize){
                    if(move_uploaded_file($file['tmp_name'],$destination)){
                        $msg = "文件上传成功";
                    }else{
                        $msg = "文件上传失败";
                    }
                }else{
                    $msg = "文件大小不能超过10M";
                }
            }else{
                $msg = "非法文件类型";
            }
        }else{
            $msg = "文件不是通过HTTP POST方式上传的";
        }
    }else{
        switch($file['error']){
            case 1:
                $msg = "超过了配置文件的大小";
                break;
            case 2:
                $msg = "超过了表单允许接收数据的大小";
                break;
            case 3:
                $msg ="文件没有上传完成";
                break;
            case 4:
                $msg ="没有文件被上传";
                break;
        }
    }
    return $msg;
}

function cuteFile($src, $dist){
    if(!isvalidate($dist)){
        if(!file_exists($dist."/".basename($src))){
            if(rename($src, $dist."/".basename($src))){
                $msg ="剪切成功";
            }else{
                $msg="剪切失败";
            }
        }else{
            $msg = "已存在相同的文件";
        }
    }else{
        $msg = "不能包含非法字符";
    }
    return $msg;

}

function isvalidate($param){
    $p = '/[\/,\*,<>,\?\|]/';
    if(preg_match($p, basename($param))){
        return true;
    }
    return false;
}
