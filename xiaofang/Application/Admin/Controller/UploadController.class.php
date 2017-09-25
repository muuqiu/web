<?php

namespace Admin\Controller;
use Think\Controller;
class UploadController extends Controller{

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
           	$this->error($upload->getError());
        }else{
          echo $info['Filedata']['savepath'].$info['Filedata']['savename']; 
        }

       //
   }
}

?>