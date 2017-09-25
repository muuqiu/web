<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    protected $_admin = array();
    protected function _initialize(){
        $_admin['uname']=$_SESSION['username'];
        if (!($_admin['uname'])) {
            $this->redirect('Public/my404');
           // $this->error('错误');
        }
    }
        
   
}
