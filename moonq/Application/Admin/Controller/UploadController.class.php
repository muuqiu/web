<?php

namespace Admin\Controller;
use Think\Controller;
class UploadController extends Controller{
    //缩略图上传后台处理代码
   public function upload() {

        $upload = new \Think\Upload();          // 实例化上传类
        $upload->maxSize   =     2048000 ;       // 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Upload/';     
        $upload->savePath = '/images/';
        $upload->savename=array('uniqid','');

        $upload->autoSub=true;
        $upload->subName=array('date','Ymd');


        $info=$upload->upload();
        if (!$info) {
           	echo $upload->getError();
        }else{
          echo $info['Filedata']['savepath'].$info['Filedata']['savename']; 
        }

       //
   }


   //用户头像上传处理代码
   public function uploadimg() {

        $upload = new \Think\Upload();          // 实例化上传类
        $upload->maxSize   =     2048000 ;       // 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Upload/';     
        $upload->savePath = '/user/img/';
        $upload->savename=array('uniqid','');

        $upload->autoSub=true;                      //开启子文件夹
        $upload->subName=array('date','Ymd');    //子文件夹名字


        $info=$upload->upload();
        if (!$info) {
            echo $upload->getError();
        }else{
          echo $info['Filedata']['savepath'].$info['Filedata']['savename']; 
        }

       //
   }
}

?>