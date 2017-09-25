<?php
namespace Home\Controller;
header("Content-Type:text/html; charset=utf8");//命名空间后面
use Think\Controller;
class FeedbackController extends Controller {
    public function index(){
    	
		$Feed=M('Feedback');
		$count=$Feed->order('add_time desc')->count();
		$Page =getpage($count,10);      //引用自定义函数 定义分页样式
		$show = $Page->show();//  分页显示输出
		$list=$Feed->order('add_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('feed',$list);
        $this->assign('page',$show);//  赋值分页输出
        //$this->assign('cate',$cate);
        $this->display();
    	
    		
    	}
    	
    	//$said=M('said')->order('top asc,create_time asc')->select();
    	//var_dump($said);
    	//exit();
    public function do_addFeed(){
        if (!IS_AJAX) {
            $data['info']='小伙子别干坏事啊!';
            $data['status']=0;
        }else{
            $email=I('email');
            //判断邮箱格式
            if(!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$email)){
                $data['info']='请输入正确的邮箱!';
                $data['status']=0;
               
            }else{
                $feed=M('feedback');
                $data1['add_time']=time();
                $data1['ip']=get_client_ip();
                $data1['username']=trim(I('username', '', 'htmlspecialchars'));
                $data1['email']=trim(I('email', '', 'htmlspecialchars'));
                $data1['content']=trim(I('content', '', 'htmlspecialchars'));

                if ($feed->add($data1)) {
                    $data['info']='谢谢,留言成功!';
                    $data['status']=1;
                    $data['url']=U("index");
                }else{
                    $data['info']='抱歉,留言失败,请稍后重试!';
                    $data['status']=0;
                    
                }
            }
        }
        $this->AjaxReturn($data);
    }	
    
    
    
}