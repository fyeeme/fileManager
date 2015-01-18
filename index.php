<?php
/**
 * Created by PhpStorm.
 * User: arno
 * Date: 14-12-28
 * Time: 下午9:08
 */
require_once 'dir.func.php';
require_once 'file.func.php';
require_once 'comm.func.php';
$path = "file";
$path = $_REQUEST['path']?$_REQUEST['path']:$path;  //从哪个目录读取文件
$mode = $_REQUEST['mode'];
$filename = $_REQUEST['filename'];
$dirname = $_REQUEST['dirname'];

$arr = readDirectory($path);
if(!$arr){
    echo "<script>alert('没有文件或者目录!!!')</script>";
}
$url = "index.php?path={$path}";

#主目录操作
if($mode == "创建文件"){
    $msg =createFile($path."/".$filename);
   alertMsg($msg, $url);
}
else if($mode =='创建文件夹'){
    $msg= createFolder($path."/".$dirname);
    alertMsg($msg, $url);
}
else if($mode =='上传文件'){
    $file = $_FILES['myFile'];
    $msg = uploadFile($file, $path);
    alertMsg($msg, $url);
}
#主目录操作结束

#################文件操作####################

#查看文件
else if($mode == 'viewFile'){
    $content = file_get_contents($filename);
    if(strlen($content)){
        $content=  highlight_string($content, true);
        echo "<table><tr><td>{$content}</td></tr></table>";
    }else{
        $msg = "文件没有内容，请先进行编辑";
        alertMsg($msg, $url);
        //echo "<script>alert('没有文件或者目录!!!')</script>";
    }
}
#修改文件
else if($mode =='editFile'){
    $content = file_get_contents($filename);
    //  echo $content;
    $str = " <form action='index.php?mode=doEditFile' method ='post'>
            <input type='hidden' name ='filename' value ='{$filename}' />
            <input type='hidden' name='path' value='{$path}' />
            <textarea name='content' clos='190' rows='10' >{$content}</textarea>
            <input type='submit' value='确定' />
        </form>";
    echo $str;
}
else if($mode == 'doEditFile'){
    $content =$_REQUEST['content'];
    if(strlen($content)){
        if(file_put_contents($filename,$content)){
            $msg = "并保成功";
            alertMsg($msg, $url);
        }else{
            $msg = "并保失败";
            alertMsg($msg, $url);
        }
    }else{
        $msg = "文件没有内容，请先进行编辑";
        alertMsg($msg, $url);
    }
}
#重命名文件
else if($mode =='renameFile'){
    $str = " <form action='index.php?mode=doRenameFile' method ='post'>
            <input type='hidden' name ='filename' value ='{$filename}' />
            <input type='hidden' name='path' value='{$path}' />
            <label>请输入名称</label>
            <input type='name'   name='newName'  placeholder='请输入重命名'>
            <input type='submit' value='确定' />
        </form>";
    echo $str;
}
else if($mode =='doRenameFile'){
    $newFilename = $_REQUEST['newName'];
    $msg =renameFile($filename,$newFilename);
   alertMsg($msg,$url);
}

#复制文件
else if($mode == 'copyFile'){
    $str = "
        <form action='index.php?mode=doCopyFile' method ='post'>
            <input type='hidden' name='filename' value='{$filename}'/>
            <input type='hidden' name='path' value='{$path}'/>
               <label>文件复制</label>
            <input type='name'   name='newName'  placeholder='{$filename}'>
            <input type='submit' value='确定' />
</form>";
    echo $str;
}else if($mode =='doCopyFile'){
    $disname = $_REQUEST['newName'];
    $msg = copyFile($filename, $path."/".$disname);
   // echo $msg;
  alertMsg($msg,$url);
}
#剪切文件
if($mode=='cutFile'){
    $str ="
        <form action='index.php?mode=doCutFile' method='post'>
            <input type='hidden' name='filename' value='{$filename}' />
            <input type='hidden' name='path' value='{$path}' />
            <label>前切：</label>
            <input type='text' name='disFile' placeholder='前切到'>
            <input type='submit' value='确定'/>
        </form>";
    echo $str;
}else if($mode =='doCutFile'){
    $src = $filename;
    $dist = $_REQUEST['disFile'];

    $msg =cuteFile($src, $path."/".$dist);
    alertMsg($msg, $url);
}

#删除文件
else if($mode=='deleteFile'){
    $msg = deleteFile($filename);
    alertMsg($msg, $url);
}
#下载文件
elseif($mode =='downFile'){
    downloadFile($filename);
}
/******文件操作结束****/


#################文件夹操作####################
#重命名
else if($mode =='renameFolder'){
    $str="
        <form action='index.php?mode=doRenameFolder' method='post'>
            <input type='text' name='path' value='{$path}' />
            <input type='text' name='filename' value='{$filename}' />
            <label>请输入新的文件夹名称：</label>
            <input type='text' name='disFile' >
            <input type='submit' value='确定'>
        </form>
    ";
    echo $str;
}
else if($mode =='doRenameFolder'){
    $src = $filename;
    $dis =$_REQUEST['disFile'];
    $msg = renameFolder($src,"file/".$dis);
    alertMsg($msg, $url);
}

#复制文件夹
elseif($mode == 'copyFolder'){
    $str ="
        <form action='index.php?mode=doCopyFolder' method='post'>
            <input type='hidden' name='filename' value='{$filename}' />
            <input type='hidden' name='path' value='{$path}' />
            <label>复制到：</label>
            <input type='text' name='disFile' placeholder='复制到'>
            <input type='submit' value='确定'/>
        </form>";
    echo $str;
}else if($mode == 'doCopyFolder'){
    $srcFile= $filename;
    $dis = $_REQUEST['disFile'];

    $msg = copyFolder($srcFile, 'file'."/".$dis);
    alertMsg($msg, $urlt);
}

#剪切文件夹
elseif($mode =='cutFolder'){
    $str ="
        <form action='index.php?mode=doCutFolder' method='post'>
            <input type='hidden' name='filename' value='{$filename}' />
            <input type='hidden' name='path' value='{$path}' />
            <label>前切：</label>
            <input type='text' name='disFile' placeholder='前切到'>
            <input type='submit' value='确定'/>
        </form>";
    echo $str;
}else if($mode =='doCutFolder'){
    $dirpath = $filename;
    $distpath = $_REQUEST['disFile'];
   $msg = cutFolder($dirpath, $path."/".$distpath);
   alertMsg($msg,$url);
}

else if($mode =='deleteFolder'){
    $msg = deleteFolder($filename);
    alertMsg($msg,$url);
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>文件管理系统</title>
    <link rel="stylesheet" href="resource/css/cikonss.css">
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.min.css">
    <script src="jquery-ui/js/jquery-1.10.2.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
    <style type="text/css">
        ul,li{
            display: inline-block;
        }
        table {
            width: 100%;  border: 1px solid #000;cellpadding: 5px; cellspacing: 0px; background-color: #1c94c4; align:center;
        }
        .small{
            width:26px;  height:30px;
        }

    </style>
</head>
<body>
<div id="showDetail" style="display:none"><img src="" id="showImg" alt=""></div>
<h1>慕课网</h1>
<div id="top">
    <ul id="navi">
        <li><a href="index.php" title="主目录" ><span  class="icon icon-small icon-square"><span class="icon-home"></span></span></a></li>
        <li><a href="#" onclick="show('createFile');" title="新建文件"><span class="icon icon-small icon-square"><span class="icon-file"></span></span></a> </li>
        <li><a href="#" onclick="show('createFloder');" title="新建文件夹" title="创建文件夹"><span class="icon icon-small icon-square"><span class="icon-folder"></span></span></a> </li>
        <li><a href="#" onclick="show('uploadFile');" title="上传文件"><span class="icon icon-small icon-square"><span class="icon-upload"></span></span></a> </li>
        <?php $back = $path=="file" ? "file": dirname($path);?>
        <li><a href="#" onclick="getBack(<?php echo "'".$back."'";?>);" title="返回上级目录"><span class="icon icon-small icon-square"><span class="icon-arrowLeft"></span></span></a></li>
    </ul>
</div>
<form modeion="index.php" method="post" enctype="multipart/form-data" >
    <table>
        <tr id="createFile" style="display: none">
            <td>请输入文件名称</td>
            <td>
                <input type="text" name="filename" />
                <input type="hidden" name="path" value="<?php echo $path;?>" />
                <!--  <input type="hidden" name="mode" value="createFile" />-->
            </td>
            <td>
                <input type="submit"  name="mode"  value="创建文件" />
            </td>
        </tr>
        <tr id="createFloder" style="display:none">
            <td>请输入文件夹名称</td>
            <td>
                <input type="text" name="dirname" />
                <input type="hidden" name="path" value="<?php echo $path; ?>" />
                <!--  <input type="hidden" name="mode" value="createFloder" />-->
            </td>
            <td>
                <input type="submit" name="mode"  value="创建文件夹" />
            </td>
        </tr>
        <tr id="uploadFile" style="display:none">
            <td>请选择要上传的文件</td>
            <td>
                <input type="file" name="myFile">
            </td>
            <td>
                <input type="submit" name="mode" value="上传文件" />
            </td>
        </tr>
        <tr>
            <td>编号</td>
            <td>名称</td>
            <td>类型</td>
            <td>大小</td>
            <td>可读</td>
            <td>可写</td>
            <td>可执行</td>
            <td>创建时间</td>
            <td>修改时间</td>
            <td>访问时间</td>
            <td>操作</td>
        </tr>

        <?php
        if($arr['file']){
            $i =1;
            foreach($arr['file'] as $file){
                $p = $path."/".$file;

                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $file; ?></td>
                    <td><?php $src = filetype($p)=="file"?"file": "folder"; ?>
                        <span class="icon icon-mid "><span class="icon-<?php echo $src;?>" title="<?php $src =filetype($p)=="file"?"文件":"文件夹"; echo $src; ?>"></span></span>
                      </td>
                    <td><?php $size =filesize($p); echo transByte($size) ; ?></td>
                    <td><?php $r =is_readable($p)?"correct.png":"error.png"; ?> <img src="images/<?php echo $r?>"  class="small" /></td>
                    <td><?php $w =is_writable($p)?"correct.png":"error.png"; ?> <img src="images/<?php echo $w?>"  class="small"/></td>
                    <td><?php $e =is_executable($p)?"correct.png":"error.png"; ?><img src="images/<?php echo $e?>" class="small"/> </td>
                    <td><?php echo date("Y-m-d H:i:m",filectime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:m",filemtime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:m",fileatime($p)); ?></td>
                    <td>
                        <?php
                        $ext=strtolower(end(explode(".",$p)));
                        $imageExt=array("gif","jpg","jpeg","png");
                        if(in_array($ext, $imageExt)){
                            ?>
                            <a href="#" onclick="viewImage(<?php echo "'".$p."'"; ?>)"><img class="small" src="images/show.png"  alt="" title="查看"/></a>
                        <?php
                            }else{
                        ?>
                            <a href="index.php?mode=viewFile&path=<?php echo $path;?>&filename=<?php echo $p;?>" ><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
                        <?php } ?>
                        <a href="index.php?mode=editFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/edit.png"  alt="" title="修改"/></a>|
                        <a href="index.php?mode=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
                        <a href="index.php?mode=copyFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|

                      <!--  <a href="index.php?mode=cutFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|-->
                      <!---->  <a href="index.php?mode=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
                        <a href="#"  onclick="delFile('<?php echo $p;?>','<?php echo $path;?>')"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
                        <a href="index.php?mode=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>
                    </td>

                    </td>
                    <td></td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>

        <?php
        if($arr['dir']){
            foreach($arr['dir'] as $file){
                $p = $path."/".$file;

                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $file; ?></td>
                    <td><?php $src = filetype($p)=="file"?"file": "folder"; ?>

                        <span class="icon icon-mid "><span class="icon-<?php echo $src;?>" title="<?php $src =filetype($p)=="file"?"文件":"文件夹"; echo $src; ?>"></span></span>
                        </td>
                    <td><?php $sum =0;$size =dirsize($p); echo transByte($size) ; ?></td>
                    <td><?php $r =is_readable($p)?"correct.png":"error.png"; ?> <img src="images/<?php echo $r?>"  class="small" /></td>
                    <td><?php $w =is_writable($p)?"correct.png":"error.png"; ?> <img src="images/<?php echo $w?>"  class="small"/></td>
                    <td><?php $e =is_executable($p)?"correct.png":"error.png"; ?><img src="images/<?php echo $e?>" class="small"/> </td>
                    <td><?php echo date("Y-m-d H:i:m",filectime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:m",filemtime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:m",fileatime($p)); ?></td>
                    <td>
                        <a href="index.php?path=<?php echo $p;?>"><img class="small" src="images/show.png"  alt="" title="查看"/></a>
                        <a href="index.php?mode=renameFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
                        <a href="index.php?mode=copyFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|
                        <a href="index.php?mode=cutFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
                        <a href="#"  onclick="delFolder('<?php echo $p;?>','<?php echo $path;?>')"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
                        <a href="index.php?mode=downFolder&path=<?php echo $path;?>&filename=<?php echo $p;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>
                    </td>

                    </td>
                    <td></td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>

    </table>


</form>

<script type="text/javascript">
    function show(dis){
        document.getElementById(dis).style.display="block";
    }

    function viewImage(img){
        $("#showImg").attr('src', img);
        $( "#showDetail" ).dialog({
            height:"auto",
            width: "auto",
            position: {my: "center", at: "center",  collision:"fit"},
            show:"slide",
            hide:"slide"
        });
    }
    function delFile(file, path){
        if(window.confirm("要删除该为文件吗？删除后无法恢复！")){
            window.location.href='index.php?mode=deleteFile&filename='+file+'&path='+path;
        }
    }
    function delFolder(file, path){
        if(window.confirm("要删除该为文件吗？删除后无法恢复！")){
            window.location.href='index.php?mode=deleteFolder&filename='+file+'&path='+path;
        }
    }
    function getBack(backUrl){
        window.location.href='index.php?path='+backUrl;
    }

</script>
</body>
</html>
