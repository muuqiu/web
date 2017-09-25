<?php
namespace Home\Controller;
header("Content-Type:text/html; charset=utf8");//命名空间后面
use Think\Controller;
class SaidController extends Controller {
    public function index(){
    	
		$said=M('said');
		$count=$said->order('create_time desc')->where('s_view=1')->count();
		$Page =getpage($count,10);      //引用自定义函数 定义分页样式
		$show = $Page->show();//  分页显示输出
		$list=$said->order('create_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('said',$list);
        $this->assign('page',$show);//  赋值分页输出
        //$this->assign('cate',$cate);
        $this->display();
    	
    		
    	}
    	
    	//$said=M('said')->order('top asc,create_time asc')->select();
    	//var_dump($said);
    	//exit();
    
   
}